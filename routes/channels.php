<?php

// Broadcast Channels Configuration
// Register authorization callbacks for broadcast channels

// User-specific channel
\Illuminate\Support\Facades\Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin channel
\Illuminate\Support\Facades\Broadcast::channel('admin', function ($user) {
    if (!$user) {
        return false;
    }
    
    if (method_exists($user, 'isAdmin')) {
        return $user->isAdmin();
    }
    
    return isset($user->role) && $user->role === 'admin';
});
