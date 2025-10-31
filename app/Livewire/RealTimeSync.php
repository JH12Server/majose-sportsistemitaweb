<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RealTimeSync extends Component
{
    public $lastUpdate = null;
    public $isConnected = false;
    public $syncInterval = 30000; // 30 segundos

    protected $listeners = [
        'order-status-updated' => 'handleOrderUpdate',
        'new-order-created' => 'handleNewOrder',
        'customer-notification' => 'handleCustomerNotification'
    ];

    public function mount()
    {
        $this->lastUpdate = now();
        $this->isConnected = true;
    }

    public function handleOrderUpdate($data)
    {
        try {
            $orderId = $data['order_id'] ?? null;
            $newStatus = $data['status'] ?? null;
            $workerId = $data['worker_id'] ?? null;

            if ($orderId && $newStatus) {
                // Notificar a todos los trabajadores conectados
                $this->dispatch('worker-notification', [
                    'type' => 'order_updated',
                    'title' => 'Pedido actualizado',
                    'message' => "El pedido #{$orderId} ha cambiado a estado: {$newStatus}",
                    'order_id' => $orderId,
                    'status' => $newStatus,
                    'worker_id' => $workerId
                ]);

                // Notificar al cliente específico
                $this->dispatch('customer-notification', [
                    'order_id' => $orderId,
                    'status' => $newStatus,
                    'message' => $this->getStatusMessage($newStatus)
                ]);

                Log::info('Sincronización en tiempo real - Pedido actualizado', [
                    'order_id' => $orderId,
                    'status' => $newStatus,
                    'worker_id' => $workerId,
                    'timestamp' => now()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error en sincronización de pedido: ' . $e->getMessage());
        }
    }

    public function handleNewOrder($data)
    {
        try {
            $orderId = $data['order_id'] ?? null;
            $customerName = $data['customer_name'] ?? 'Cliente';
            $priority = $data['priority'] ?? 'normal';

            if ($orderId) {
                // Notificar a todos los trabajadores
                $this->dispatch('worker-notification', [
                    'type' => 'new_order',
                    'title' => 'Nuevo pedido',
                    'message' => "Nuevo pedido #{$orderId} de {$customerName}",
                    'order_id' => $orderId,
                    'priority' => $priority,
                    'urgent' => $priority === 'urgent'
                ]);

                Log::info('Sincronización en tiempo real - Nuevo pedido', [
                    'order_id' => $orderId,
                    'customer_name' => $customerName,
                    'priority' => $priority,
                    'timestamp' => now()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error en sincronización de nuevo pedido: ' . $e->getMessage());
        }
    }

    public function handleCustomerNotification($data)
    {
        try {
            $orderId = $data['order_id'] ?? null;
            $message = $data['message'] ?? 'Tu pedido ha sido actualizado';
            $status = $data['status'] ?? null;

            if ($orderId) {
                // Aquí se implementaría la notificación real al cliente
                // Por ejemplo, usando WebSockets, Pusher, o Firebase
                
                Log::info('Notificación enviada al cliente', [
                    'order_id' => $orderId,
                    'message' => $message,
                    'status' => $status,
                    'timestamp' => now()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error enviando notificación al cliente: ' . $e->getMessage());
        }
    }

    private function getStatusMessage($status)
    {
        $messages = [
            'pending' => 'Tu pedido está siendo procesado',
            'review' => 'Tu pedido está siendo revisado',
            'production' => 'Tu pedido está en producción',
            'ready' => 'Tu pedido está listo para envío',
            'shipped' => 'Tu pedido ha sido enviado',
            'delivered' => 'Tu pedido ha sido entregado',
            'cancelled' => 'Tu pedido ha sido cancelado',
        ];

        return $messages[$status] ?? 'El estado de tu pedido ha sido actualizado';
    }

    public function checkConnection()
    {
        $this->isConnected = true;
        $this->lastUpdate = now();
        
        // Disparar evento para verificar conexión
        $this->dispatch('connection-checked');
    }

    public function disconnect()
    {
        $this->isConnected = false;
        Log::info('Sincronización en tiempo real desconectada', [
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
    }

    public function reconnect()
    {
        $this->isConnected = true;
        $this->lastUpdate = now();
        
        Log::info('Sincronización en tiempo real reconectada', [
            'user_id' => Auth::id(),
            'timestamp' => now()
        ]);
    }

    public function render()
    {
        return view('livewire.real-time-sync');
    }
}
