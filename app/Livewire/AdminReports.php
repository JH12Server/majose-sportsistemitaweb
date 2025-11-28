<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Servicio;

class AdminReports extends Component
{
    use WithPagination;

    public $startDate = '';
    public $endDate = '';
    public $search = '';
    public $perPage = 10;

    public function mount()
    {
        if (!Auth::user() || !Auth::user()->isAdmin()) {
            abort(403, 'Acceso denegado');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $summary = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_sales' => (float) Sale::when($this->startDate, fn($q)=>$q->whereDate('created_at','>=',$this->startDate))
                                         ->when($this->endDate, fn($q)=>$q->whereDate('created_at','<=',$this->endDate))
                                         ->sum('total'),
            'sales_today' => (float) Sale::whereDate('created_at', today())->sum('total'),
        ];

        $products = Product::query()
            ->when($this->search, function($q) {
                $q->where(function($qq){
                    $qq->where('name', 'like', '%'.$this->search.'%')
                       ->orWhere('category', 'like', '%'.$this->search.'%')
                       ->orWhere('brand', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        // Aggregations for product sales (based on Servicio via SaleDetail)
        $details = SaleDetail::with(['sale.user', 'product'])
            ->when($this->startDate, fn($q)=>$q->whereHas('sale', fn($s)=>$s->whereDate('created_at','>=',$this->startDate)))
            ->when($this->endDate, fn($q)=>$q->whereHas('sale', fn($s)=>$s->whereDate('created_at','<=',$this->endDate)))
            ->get();

        $groupedByProduct = $details->groupBy('product_id');
        $productSales = $groupedByProduct->map(function($rows, $productId){
            $first = $rows->first();
            $name = optional($first->product)->nombre ?? ('Producto #'.$productId);
            $qty = (int) $rows->sum('quantity');
            $total = (float) $rows->sum(function($r){ return (float)$r->price * (int)$r->quantity; });
            return [
                'codigo' => (string)$productId,
                'nombre' => $name,
                'marca' => '-',
                'presentacion' => '-',
                'categorias' => '-',
                'cantidad' => $qty,
                'total' => $total,
                'precio_compra' => null,
                'precio_venta' => null,
                'margen' => null,
            ];
        })->values();

        $soldDetails = $details->map(function($d){
            $productName = optional($d->product)->nombre ?? ('Producto #'.$d->product_id);
            $subtotal = (float) $d->price * (int) $d->quantity;
            return [
                'id' => $d->id,
                'producto' => $productName,
                'cantidad' => (int)$d->quantity,
                'precio' => (float)$d->price,
                'total' => $subtotal,
                'usuario' => optional(optional($d->sale)->user)->name ?? '-',
                'fecha' => optional($d->sale)->created_at ? $d->sale->created_at->format('Y-m-d H:i:s') : '-',
                'estado' => optional($d->sale)->status ?? '-',
            ];
        })->values();

        // Charts: ventas por usuario (cliente)
        $salesQuery = Sale::with('user')
            ->when($this->startDate, fn($q)=>$q->whereDate('created_at','>=',$this->startDate))
            ->when($this->endDate, fn($q)=>$q->whereDate('created_at','<=',$this->endDate));

        $byUser = $salesQuery->get()->groupBy(fn($s)=> optional($s->user)->name ?? 'Sin usuario');
        $chartUsersLabels = $byUser->keys()->values();
        $chartUsersCounts = $byUser->map->count()->values();
        $chartUsersTotals = $byUser->map(fn($g)=> (float) $g->sum('total'))->values();

        return view('livewire.admin-reports', [
            'summary' => $summary,
            'products' => $products,
            'chartUsersLabels' => $chartUsersLabels,
            'chartUsersCounts' => $chartUsersCounts,
            'chartUsersTotals' => $chartUsersTotals,
            'productSales' => $productSales,
            'soldDetails' => $soldDetails,
        ]);
    }
}
