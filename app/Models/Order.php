<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'status_updated_by',
        'status_updated_at',
        'status_notes',
        'total_amount',
        'customer_notes',
        'internal_notes',
        'estimated_delivery',
        'actual_delivery',
        'delivered_at',
        'assigned_worker_id',
        'priority',
    ];

    protected $casts = [
        'estimated_delivery' => 'date',
        'actual_delivery' => 'date',
        'status_updated_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedWorker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_worker_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(OrderFile::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pendiente',
            'review' => 'En Revisión',
            'production' => 'En Producción',
            'ready' => 'Listo para Entrega',
            'shipped' => 'Enviado',
            'delivered' => 'Entregado',
            'cancelled' => 'Cancelado',
            'paid' => 'Pagado',
            default => 'Desconocido'
        };
    }

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low' => 'Baja',
            'normal' => 'Normal',
            'high' => 'Alta',
            'urgent' => 'Urgente',
            default => 'Normal'
        };
    }

    /**
     * Crear una notificación para el cliente
     */
    public function createNotification(string $type, string $title, string $message, array $data = []): Notification
    {
        return Notification::create([
            'user_id' => $this->user_id,
            'order_id' => $this->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    /**
     * Notificar que el pedido ha sido creado
     */
    public function notifyOrderCreated(): void
    {
        $notification = $this->createNotification(
            type: 'order_created',
            title: 'Pedido Creado',
            message: "Tu pedido #{$this->order_number} ha sido creado exitosamente.",
            data: [
                'order_number' => $this->order_number,
                'total_amount' => $this->total_amount,
                'status' => $this->status,
            ]
        );

        // Broadcast real-time event
        try {
            event(new \App\Events\OrderCreated($this, $notification));
        } catch (\Throwable $e) {
            // Don't break flow if broadcasting not configured
            \Illuminate\Support\Facades\Log::debug('Broadcast OrderCreated failed: ' . $e->getMessage());
        }
    }

    /**
     * Notificar que el pedido ha sido pagado
     */
    public function notifyOrderPaid(): void
    {
        $notification = $this->createNotification(
            type: 'order_paid',
            title: 'Pago Recibido',
            message: "El pago de tu pedido #{$this->order_number} ha sido recibido y confirmado.",
            data: [
                'order_number' => $this->order_number,
                'total_amount' => $this->total_amount,
                'status' => $this->status,
            ]
        );

        // Broadcast real-time event
        try {
            event(new \App\Events\OrderPaid($this, $notification));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::debug('Broadcast OrderPaid failed: ' . $e->getMessage());
        }
    }

    /**
     * Notificar cambio de estado del pedido
     */
    public function notifyStatusChanged(string $newStatus): void
    {
        $statusLabel = $this->getStatusLabelAttribute();
        $messages = [
            'review' => "Tu pedido #{$this->order_number} está siendo revisado por nuestro equipo.",
            'production' => "Tu pedido #{$this->order_number} ha iniciado producción.",
            'ready' => "Tu pedido #{$this->order_number} está listo para entrega.",
            'shipped' => "Tu pedido #{$this->order_number} ha sido enviado.",
            'delivered' => "¡Tu pedido #{$this->order_number} ha sido entregado!",
            'cancelled' => "Tu pedido #{$this->order_number} ha sido cancelado.",
        ];

        $notification = $this->createNotification(
            type: 'order_status_changed',
            title: "Pedido: {$statusLabel}",
            message: $messages[$newStatus] ?? "El estado de tu pedido #{$this->order_number} ha cambiado a: {$statusLabel}",
            data: [
                'order_number' => $this->order_number,
                'old_status' => $this->status,
                'new_status' => $newStatus,
                'status_label' => $statusLabel,
            ]
        );

        // Broadcast real-time event
        try {
            event(new \App\Events\OrderStatusChanged($this, $notification, $newStatus));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::debug('Broadcast OrderStatusChanged failed: ' . $e->getMessage());
        }
    }
}