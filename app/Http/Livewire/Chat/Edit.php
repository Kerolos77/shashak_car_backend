<?php

namespace App\Http\Livewire\Chat;

use App\Models\Chat;
use App\Models\Order;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public Chat $chat;

    public array $listsForFields = [];

    public function mount(Chat $chat)
    {
        $this->chat = $chat;
        $this->initListsForFields();
    }

    public function render()
    {
        return view('livewire.chat.edit');
    }

    public function submit()
    {
        $this->validate();

        $this->chat->save();

        return redirect()->route('admin.chats.index');
    }

    protected function rules(): array
    {
        return [
            'chat.customer_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'chat.customer_name' => [
                'string',
                'nullable',
            ],
            'chat.customer_profile_image' => [
                'string',
                'nullable',
            ],
            'chat.driver_id' => [
                'integer',
                'exists:users,id',
                'nullable',
            ],
            'chat.driver_name' => [
                'string',
                'nullable',
            ],
            'chat.driver_profile_image' => [
                'string',
                'nullable',
            ],
            'chat.last_message' => [
                'string',
                'nullable',
            ],
            'chat.last_sender' => [
                'string',
                'nullable',
            ],
            'chat.order_id' => [
                'integer',
                'exists:orders,id',
                'nullable',
            ],
        ];
    }

    protected function initListsForFields(): void
    {
        $this->listsForFields['customer'] = User::pluck('name', 'id')->toArray();
        $this->listsForFields['driver']   = User::pluck('name', 'id')->toArray();
        $this->listsForFields['order']    = Order::pluck('accepted_driver', 'id')->toArray();
    }
}
