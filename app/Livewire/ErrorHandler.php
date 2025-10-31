<?php

namespace App\Livewire;

use Livewire\Component;

class ErrorHandler extends Component
{
    public $errors = [];
    public $showErrorModal = false;

    protected $listeners = [
        'show-error' => 'showError',
        'show-success' => 'showSuccess',
        'show-info' => 'showInfo',
        'show-warning' => 'showWarning',
        'clear-errors' => 'clearErrors'
    ];

    public function showError($message)
    {
        $this->errors[] = [
            'type' => 'error',
            'message' => $message,
            'timestamp' => now()
        ];
        $this->showErrorModal = true;
        $this->dispatch('error-shown');
    }

    public function showSuccess($message)
    {
        $this->errors[] = [
            'type' => 'success',
            'message' => $message,
            'timestamp' => now()
        ];
        $this->dispatch('success-shown');
    }

    public function showInfo($message)
    {
        $this->errors[] = [
            'type' => 'info',
            'message' => $message,
            'timestamp' => now()
        ];
        $this->dispatch('info-shown');
    }

    public function showWarning($message)
    {
        $this->errors[] = [
            'type' => 'warning',
            'message' => $message,
            'timestamp' => now()
        ];
        $this->dispatch('warning-shown');
    }

    public function clearErrors()
    {
        $this->errors = [];
        $this->showErrorModal = false;
    }

    public function removeError($index)
    {
        unset($this->errors[$index]);
        $this->errors = array_values($this->errors);
        
        if (empty($this->errors)) {
            $this->showErrorModal = false;
        }
    }

    public function render()
    {
        return view('livewire.error-handler');
    }
}
