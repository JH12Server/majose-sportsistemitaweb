<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Notification as NotificationModel;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;
    public NotificationModel $notification;

    public function __construct(Order $order, NotificationModel $notification)
    {
        $this->order = $order;
        $this->notification = $notification;
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
        return 'order.created';
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
        ];
    }
}
