<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Sale;
use Illuminate\Support\Facades\Auth;

class HistorialCompras extends Component
{
    public function render()
    {
        $compras = Sale::where('user_id', Auth::id())
            ->with('details.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.historial-compras', [
            'compras' => $compras
        ]);
    }
} 