<?php

namespace App\Livewire;

use App\Models\Notification;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;

class NotificationCenter extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showPanel = false;

    public function mount()
    {
        $this->loadNotifications();
    }

    // Listen for notification-created event from the observer
    #[\Livewire\Attributes\On('notification-created')]
    public function onNotificationCreated()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (Auth::check()) {
            // Get all notifications for this user
            $this->notifications = Notification::where('user_id', Auth::id())
                ->with('order')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'user_id' => $notification->user_id,
                        'order_id' => $notification->order_id,
                        'type' => $notification->type,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'is_read' => $notification->is_read,
                        'data' => $notification->data,
                        'created_at' => $notification->created_at,
                    ];
                })
                ->toArray();

            // Count unread notifications
            $this->unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', false)
                ->count();
        }
    }

    public function togglePanel()
    {
        $this->showPanel = !$this->showPanel;
        if ($this->showPanel) {
            $this->loadNotifications();
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->update(['is_read' => true]);
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->delete();
            $this->loadNotifications();
        }
    }

    public function deleteAllNotifications()
    {
        Notification::where('user_id', Auth::id())->delete();
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notification-center');
    }
}
