<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $title;
    protected $body;
    protected $imageUrl;

    /**
     * Create a new notification instance.
     */
    public function __construct($title, $body, $imageUrl = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->imageUrl = $imageUrl;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; 
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'image_url' => $this->imageUrl,
        ];
    }
}
