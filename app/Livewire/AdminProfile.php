<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminProfile extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $avatar;
    public $avatar_url;

    public $edit_mode = false;
    public $change_password_mode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'new_password' => 'nullable|string|min:8|confirmed',
        'current_password' => 'required_with:new_password|current_password',
        'avatar' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->avatar_url = asset('assets/img/majose logo.png');
        if (!empty($user->avatar_path)) {
            $ap = str_replace('\\', '/', $user->avatar_path);
            if (str_starts_with($ap, 'http') || str_starts_with($ap, '//')) {
                $this->avatar_url = $ap;
            } elseif (file_exists(public_path('storage/' . ltrim($ap, '/')))) {
                $this->avatar_url = '/storage/' . ltrim($ap, '/');
            } elseif (file_exists(storage_path('app/public/' . ltrim($ap, '/')))) {
                $this->avatar_url = '/product-image/' . basename($ap);
            }
        }
    }

    public function updateProfile()
    {
        $this->validate(['name' => 'required|string|max:255']);

        $user = Auth::user();
        $user->update(['name' => $this->name]);

        $this->edit_mode = false;
        $this->dispatch('notify', message: 'Nombre actualizado exitosamente');
    }

    public function uploadAvatar()
    {
        $this->validate(['avatar' => 'required|image|max:2048']);

        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->avatar_path && Storage::disk('public')->exists($user->avatar_path)) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Store new avatar
        $path = $this->avatar->store('avatars', 'public');
        $user->update(['avatar_path' => $path]);
        
        // prefer public/storage path if present, else fallback to product-image route
        if (file_exists(public_path('storage/' . $path))) {
            $this->avatar_url = '/storage/' . ltrim($path, '/');
        } elseif (file_exists(storage_path('app/public/' . $path))) {
            $this->avatar_url = '/product-image/' . basename($path);
        } else {
            $this->avatar_url = Storage::disk('public')->url($path);
        }
        $this->avatar = null;
        
        $this->dispatch('notify', message: 'Avatar actualizado exitosamente');
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->update(['password' => Hash::make($this->new_password)]);

        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->current_password = '';
        $this->change_password_mode = false;

        $this->dispatch('notify', message: 'Contraseña cambiada exitosamente');
    }

    public function render()
    {
        return view('livewire.admin-profile');
    }
}
