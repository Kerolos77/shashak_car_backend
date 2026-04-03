<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Notifications\PushNotification;
use Illuminate\Support\Facades\Notification;
use Kreait\Firebase\Messaging\CloudMessage;

class SendNotification extends Component
{
    use WithFileUploads;

    public $target = 'all_users';
    public $user_id = '';
    public $title = '';
    public $body = '';
    public $image; // File upload
    
    public $search = ''; // For autocomplete

    public $successMessage = '';
    public $errorMessage = '';

    protected $rules = [
        'target' => 'required|in:all_users,all_drivers,specific_user',
        'user_id' => 'required_if:target,specific_user',
        'title' => 'required|string|max:255',
        'body' => 'required|string',
        'image' => 'nullable|image|max:2048' // 2MB Max
    ];

    public function send()
    {
        $this->validate();
        $this->successMessage = '';
        $this->errorMessage = '';

        $users = collect();

        if ($this->target === 'all_users') {
            $users = User::whereDoesntHave('profile')
                         ->whereDoesntHave('roles', fn($q) => $q->where('title', 'Admin'))
                         ->whereNotNull('fcm_token')->get();
        } elseif ($this->target === 'all_drivers') {
            $users = User::whereHas('profile')
                         ->whereNotNull('fcm_token')->get();
        } elseif ($this->target === 'specific_user') {
            if (is_numeric($this->user_id)) {
                $user = User::find($this->user_id);
            } else {
                $user = User::where('phone_number', $this->user_id)->orWhere('email', $this->user_id)->first();
            }
            if ($user && $user->fcm_token) {
                $users->push($user);
            }
        }

        if ($users->isEmpty()) {
            $this->errorMessage = trans('global.notification_page.target_not_found');
            return;
        }

        // Handle Image Upload
        $imageUrl = null;
        if ($this->image) {
            $path = $this->image->store('notifications', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        // Save to database
        Notification::send($users, new PushNotification(
            $this->title,
            $this->body,
            $imageUrl
        ));

        // Push to FCM
        $tokens = $users->pluck('fcm_token')->filter()->toArray();
        if (!empty($tokens)) {
            try {
                $messaging = app('firebase.messaging');
                $message = CloudMessage::new()
                    ->withNotification([
                        'title' => $this->title,
                        'body' => $this->body,
                        'image' => $imageUrl,
                    ]);
                $messaging->sendMulticast($message, $tokens);
            } catch (\Exception $e) {
                \Log::error("Livewire FCM Send Error: " . $e->getMessage());
            }
        }

        $this->successMessage = trans('global.notification_page.sent_successfully', ['count' => $users->count()]);
        $this->reset(['title', 'body', 'image', 'user_id', 'search']);
    }

    public function updatedTarget()
    {
        $this->user_id = '';
        $this->search = '';
    }

    public function updatedSearch()
    {
        $this->user_id = '';
    }

    public function selectUser($id, $name, $phone)
    {
        $this->user_id = $id;
        $this->search = $name . ' (' . $phone . ')';
    }

    public function render()
    {
        return view('livewire.admin.send-notification')->extends('layouts.admin')->section('content');
    }
}
