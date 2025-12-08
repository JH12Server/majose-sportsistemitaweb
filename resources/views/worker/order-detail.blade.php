@php
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
$orderModel = is_numeric($order) ? Order::find($order) : (is_object($order) ? $order : null);
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    @if(!$orderModel)
        <div class="bg-white p-6 rounded-lg shadow">Pedido no encontrado.</div>
    @else
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold">Pedido #{{ $orderModel->order_number }}</h2>
                    <p class="text-sm text-gray-500">Cliente: {{ $orderModel->user->name ?? 'N/A' }} &middot; {{ $orderModel->user->email ?? '' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">Total: ${{ number_format($orderModel->total_amount,2) }}</p>
                    <p class="text-sm text-gray-500">Estado: {{ $orderModel->status_label ?? $orderModel->status }}</p>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @foreach($orderModel->items as $item)
                    @php
                        // Priorizar archivo guardado en la columna reference_file
                        $url = null;
                        try {
                            // accessor en OrderItem: getReferenceImageUrlAttribute()
                            $url = $item->reference_image_url ?? null;
                        } catch (\Throwable $e) {
                            $url = null;
                        }

                        // Si no hay reference_file, intentar cargar desde customizations (si existe)
                        if (!$url) {
                            $custom = is_array($item->customization ?? null) ? $item->customization : [];
                            // Si hay una URL completa en 'image' (asset), úsala
                            if (!empty($custom['image'])) {
                                $url = $custom['image'];
                            } elseif (!empty($custom['file']) && Storage::disk('public')->exists($custom['file'])) {
                                @php
                                    $file = str_replace('\\', '/', $custom['file']);
                                    if (str_starts_with($file, 'http') || str_starts_with($file, '//')) {
                                        $url = $file;
                                    } elseif (file_exists(public_path('storage/' . ltrim($file, '/')))) {
                                        $url = '/storage/' . ltrim($file, '/');
                                    } elseif (file_exists(storage_path('app/public/' . ltrim($file, '/')))) {
                                        $url = '/product-image/' . basename($file);
                                    } else {
                                        $url = null;
                                    }
                                @endphp
                            }
                        }
                    @endphp
                    <div class="py-4 flex items-start space-x-4">
                        <div class="w-20 h-20 bg-gray-100 rounded overflow-hidden flex-shrink-0">
                            <img src="{{ $item->product->main_image_url ?? asset('images/placeholder.jpg') }}" class="w-full h-full object-cover" alt="{{ $item->product->name }}">
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $item->product->name }}</h3>
                            <p class="text-sm text-gray-500">Cantidad: {{ $item->quantity }} &middot; ${{ number_format($item->unit_price,2) }}</p>

                            @if(!empty($custom))
                                <div class="mt-2 space-y-1">
                                    @if(!empty($custom['text']))
                                        <div class="text-sm text-gray-700">Texto: {{ $custom['text'] }}</div>
                                    @endif
                                    @if(!empty($custom['size']))
                                        <div class="text-sm text-gray-700">Talla: {{ $custom['size'] }}</div>
                                    @endif
                                    @if(!empty($custom['color']))
                                        <div class="text-sm text-gray-700">Color: {{ $custom['color'] }}</div>
                                    @endif

                                    @if($url)
                                        <div class="mt-2">
                                            <button onclick="openPreviewModal('{{ $url }}')" class="inline-flex items-center px-3 py-2 bg-gray-100 rounded hover:bg-gray-200">
                                                <img src="{{ $url }}" alt="Referencia" class="h-16 w-16 object-cover rounded mr-2" />
                                                <span class="text-sm">Ver imagen de referencia</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Modal global de vista previa -->
<div id="image-preview-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden items-center justify-center z-50">
    <div class="max-w-3xl w-full p-4">
        <div class="bg-white rounded-lg overflow-hidden">
            <div class="p-2 text-right">
                <button onclick="closePreviewModal()" class="text-gray-600 hover:text-gray-800">Cerrar</button>
            </div>
            <div class="p-4 flex items-center justify-center">
                <img id="image-preview-img" src="" alt="Preview" class="max-h-[80vh] w-auto object-contain" />
            </div>
        </div>
    </div>
</div>

<script>
function openPreviewModal(url) {
    const modal = document.getElementById('image-preview-modal');
    const img = document.getElementById('image-preview-img');
    img.src = url;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closePreviewModal() {
    const modal = document.getElementById('image-preview-modal');
    const img = document.getElementById('image-preview-img');
    img.src = '';
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
// Close on ESC
document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closePreviewModal(); });
</script>