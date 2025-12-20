<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        // Aggregations for product sales - use same simple pattern as AdminVentas (works reliably)
        $ventas = Sale::with(['user', 'details.product'])
            ->when($this->startDate, function($query) {
                $query->whereDate('created_at', '>=', $this->startDate);
            })
            ->when($this->endDate, function($query) {
                $query->whereDate('created_at', '<=', $this->endDate);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Debugging: log counts
        Log::info('AdminReports - Total sales found: ' . count($ventas));
        foreach ($ventas as $sale) {
            Log::info('  Sale #' . $sale->id . ' has ' . count($sale->details) . ' details');
        }

        // Extract all details from sales
        $details = collect();
        foreach ($ventas as $sale) {
            foreach ($sale->details as $detail) {
                $detail->sale = $sale;
                $details->push($detail);
            }
        }

        Log::info('AdminReports - Total details collected: ' . count($details));

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
                'usuario' => $d->sale && $d->sale->user ? $d->sale->user->name : '-',
                'fecha' => $d->sale ? $d->sale->created_at->format('Y-m-d H:i:s') : '-',
                'estado' => $d->sale ? $d->sale->status : '-',
            ];
        })->values();

        return view('livewire.admin-reports', [
            'summary' => $summary,
            'products' => $products,
            'productSales' => $productSales,
            'soldDetails' => $soldDetails,
        ]);
    }
}
