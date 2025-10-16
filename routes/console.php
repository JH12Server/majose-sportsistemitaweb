<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Roles permitidos
$allowedRoles = ['admin', 'user'];

Artisan::command('user:create {name} {email} {password} {role}', function ($name, $email, $password, $role) use ($allowedRoles) {
    $userClass = app()->make('App\\Models\\User');
    if (!in_array($role, $allowedRoles)) {
        $this->error('Rol no permitido. Usa uno de: ' . implode(', ', $allowedRoles));
        return;
    }
    if ($userClass::where('email', $email)->exists()) {
        $this->error('Ya existe un usuario con ese email.');
        return;
    }
    $user = $userClass::create([
        'name' => $name,
        'email' => $email,
        'password' => bcrypt($password),
        'role' => $role,
    ]);
    $this->info("Usuario creado: {$user->name} ({$user->email}) con rol '{$user->role}'");
})->describe('Crear un usuario y asignar rol (user:create {name} {email} {password} {role})');

Artisan::command('user:list', function () {
    $userClass = app()->make('App\\Models\\User');
    $users = $userClass::all(['id', 'name', 'email', 'role']);
    if ($users->isEmpty()) {
        $this->info('No hay usuarios registrados.');
        return;
    }
    $this->table(['ID', 'Nombre', 'Email', 'Rol'], $users->toArray());
})->describe('Listar todos los usuarios y sus roles');

Artisan::command('user:role {email} {role}', function ($email, $role) use ($allowedRoles) {
    $userClass = app()->make('App\\Models\\User');
    if (!in_array($role, $allowedRoles)) {
        $this->error('Rol no permitido. Usa uno de: ' . implode(', ', $allowedRoles));
        return;
    }
    $user = $userClass::where('email', $email)->first();
    if (!$user) {
        $this->error('Usuario no encontrado.');
        return;
    }
    $user->role = $role;
    $user->save();
    $this->info("Rol actualizado para {$user->name} ({$user->email}): ahora es '{$user->role}'");
})->describe('Actualizar el rol de un usuario (user:role {email} {role})');
