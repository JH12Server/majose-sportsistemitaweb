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
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    protected $messages = [
        'name.required' => 'El nombre es requerido.',
        'name.string' => 'El nombre debe ser texto.',
        'name.max' => 'El nombre no puede exceder 255 caracteres.',
        'avatar.required' => 'Por favor selecciona una imagen.',
        'avatar.image' => 'El archivo debe ser una imagen.',
        'avatar.mimes' => 'La imagen debe estar en formato JPEG, PNG, JPG o GIF.',
        'avatar.max' => 'La imagen no puede exceder 2MB.',
        'new_password.required' => 'La contraseña es requerida.',
        'new_password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'new_password.confirmed' => 'Las contraseñas no coinciden.',
        'current_password.required_with' => 'Debes ingresar tu contraseña actual.',
        'current_password.current_password' => 'La contraseña actual es incorrecta.',
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
        if (!$this->avatar) {
            $this->addError('avatar', 'Por favor selecciona una imagen.');
            return;
        }

        $this->validate(['avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'], $this->messages);

        try {
            $user = Auth::user();
            
            // Delete old avatar if exists
            if (!empty($user->avatar_path) && Storage::disk('public')->exists($user->avatar_path)) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            // Store new avatar
            $path = $this->avatar->store('avatars', 'public');
            $user->update(['avatar_path' => $path]);
            
            // Set avatar URL for display
            $this->avatar_url = '/storage/' . ltrim($path, '/');
            $this->avatar = null;
            
            $this->dispatch('notify', message: 'Avatar actualizado exitosamente');
        } catch (\Exception $e) {
            $this->addError('avatar', 'Error al subir la imagen: ' . $e->getMessage());
        }
    }

    public function clearAvatar()
    {
        $this->avatar = null;
        $this->resetErrorBag('avatar');
    }

    public function changePassword()
    {
        $this->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ], $this->messages);

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
