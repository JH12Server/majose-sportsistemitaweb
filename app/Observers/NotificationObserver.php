<?php

namespace App\Observers;

use App\Models\Notification;

class NotificationObserver
{
    /**
     * Handle the Notification "created" event.
     */
    public function created(Notification $notification): void
    {
        // Broadcasting o real-time updates pueden ser implementados aquí si es necesario
        // Por ahora, el componente NotificationCenter cargará automáticamente las notificaciones
        // cuando el usuario interactúe con él
    }

    /**
     * Handle the Notification "updated" event.
     */
    public function updated(Notification $notification): void
    {
        // Nothing to do
    }

    /**
     * Handle the Notification "deleted" event.
     */
    public function deleted(Notification $notification): void
    {
        // Nothing to do
    }
}
