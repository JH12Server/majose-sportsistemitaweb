<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Notification as NotificationModel;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public NotificationModel $notification;
    public string $newStatus;

    public function __construct(Order $order, NotificationModel $notification, string $newStatus)
    {
        $this->order = $order;
        $this->notification = $notification;
        $this->newStatus = $newStatus;
    }

    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.' . $this->order->user_id),
            new PrivateChannel('admin'),
        ];
    }

    public function broadcastAs()
    {
        return 'order.status_changed';
    }

    public function broadcastWith()
    {
        return [
            'notification' => [
                'id' => $this->notification->id,
                'order_id' => $this->notification->order_id,
                'type' => $this->notification->type,
                'title' => $this->notification->title,
                'message' => $this->notification->message,
                'data' => $this->notification->data,
                'created_at' => $this->notification->created_at->toDateTimeString(),
            ],
            'order' => [
                'id' => $this->order->id,
                'order_number' => $this->order->order_number,
                'total_amount' => $this->order->total_amount,
                'status' => $this->order->status,
            ],
            'new_status' => $this->newStatus,
        ];
    }
}
