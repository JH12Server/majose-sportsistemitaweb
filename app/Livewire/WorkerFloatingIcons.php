<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WorkerFloatingIcons extends Component
{
    public $showNotifications = false;
    public $showProfile = false;
    public $notifications = [];
    public $unreadNotifications = 0;
    public $workerStats = [];

    protected $listeners = [
        'notification-received' => 'handleNotification',
        'order-updated' => 'loadNotifications',
        'refresh-notifications' => 'loadNotifications'
    ];

    public function mount()
    {
        $this->loadNotifications();
        $this->loadWorkerStats();
    }

    public function loadNotifications()
    {
        // Simular notificaciones - en producción vendrían de la base de datos
        $this->notifications = [
            [
                'id' => 1,
                'title' => 'Nuevo pedido urgente',
                'message' => 'Pedido #ORD-12345678 requiere atención inmediata',
                'time' => '5 minutos',
                'read' => false,
                'type' => 'urgent_order',
                'order_id' => 1
            ],
            [
                'id' => 2,
                'title' => 'Pedido listo para envío',
                'message' => 'Pedido #ORD-12345677 está listo para ser enviado',
                'time' => '1 hora',
                'read' => false,
                'type' => 'ready_to_ship',
                'order_id' => 2
            ],
            [
                'id' => 3,
                'title' => 'Recordatorio de entrega',
                'message' => 'Pedido #ORD-12345676 debe entregarse hoy',
                'time' => '2 horas',
                'read' => true,
                'type' => 'delivery_reminder',
                'order_id' => 3
            ],
            [
                'id' => 4,
                'title' => 'Nuevo pedido asignado',
                'message' => 'Se te ha asignado el pedido #ORD-12345675',
                'time' => '3 horas',
                'read' => true,
                'type' => 'assigned_order',
                'order_id' => 4
            ]
        ];

        $this->unreadNotifications = collect($this->notifications)->where('read', false)->count();
    }

    public function loadWorkerStats()
    {
        $workerId = Auth::id();
        
        $this->workerStats = [
            'pending_orders' => Order::whereIn('status', ['pending', 'review'])->count(),
            'production_orders' => Order::where('status', 'production')->count(),
            'urgent_orders' => Order::where('priority', 'urgent')
                ->whereIn('status', ['pending', 'review', 'production'])
                ->count(),
            'completed_today' => Order::where('status', 'delivered')
                ->whereDate('delivered_at', today())
                ->count(),
        ];
    }

    public function toggleNotifications()
    {
        $this->showNotifications = !$this->showNotifications;
        $this->showProfile = false;
    }

    public function toggleProfile()
    {
        $this->showProfile = !$this->showProfile;
        $this->showNotifications = false;
    }

    public function closeAll()
    {
        $this->showNotifications = false;
        $this->showProfile = false;
    }

    public function markNotificationAsRead($notificationId)
    {
        $notification = collect($this->notifications)->firstWhere('id', $notificationId);
        if ($notification) {
            $notification['read'] = true;
            $this->unreadNotifications = collect($this->notifications)->where('read', false)->count();
        }
    }

    public function handleNotification($data)
    {
        // Agregar nueva notificación
        $newNotification = [
            'id' => count($this->notifications) + 1,
            'title' => $data['title'] ?? 'Nueva notificación',
            'message' => $data['message'] ?? 'Tienes una nueva notificación',
            'time' => 'Ahora',
            'read' => false,
            'type' => $data['type'] ?? 'general',
            'order_id' => $data['order_id'] ?? null
        ];

        array_unshift($this->notifications, $newNotification);
        $this->unreadNotifications = collect($this->notifications)->where('read', false)->count();
        
        $this->dispatch('show-info', $newNotification['message']);
    }

    public function goToOrder($orderId)
    {
        $this->dispatch('show-order', $orderId);
        $this->closeAll();
    }

    public function render()
    {
        return view('livewire.worker-floating-icons');
    }
}
