<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Mi Carrito</h1>

        @if(count($items) > 0)
            <div class="bg-white rounded-lg shadow overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Producto</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Precio</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Cantidad</th>
                            <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900">Subtotal</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-900">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($items as $item)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-start space-x-4">
                                    <img src="{{ $item['image'] }}" class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900">{{ $item['nombre'] }}</p>
                                        
                                        @if($item['customization'] ?? false)
                                            <div class="mt-2 space-y-1">
                                                @if($item['customization']['size'] ?? false)
                                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Talla: {{ $item['customization']['size'] }}</span>
                                                @endif
                                                @if($item['customization']['color'] ?? false)
                                                    <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded ml-1">Color: {{ $item['customization']['color'] }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Sección de imagen de referencia -->
                                        @if($item['customization']['image'] ?? false)
                                            <div class="mt-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                                <p class="text-xs font-bold text-purple-800 mb-2">Imagen de referencia subida por ti</p>
                                                <div class="flex items-center gap-2">
                                                    <img src="{{ $item['customization']['image'] }}" 
                                                         class="w-16 h-16 object-cover rounded cursor-pointer hover:ring-2 hover:ring-purple-400"
                                                         onclick="openPreviewModal('{{ $item['customization']['image'] }}')">
                                                    <button wire:click="clearReferenceImage({{ $item['id'] }})" 
                                                            class="text-xs text-red-600 hover:text-red-800 font-medium">
                                                        Cambiar
                                                    </button>
                                                </div>
                                            </div>
                                        @elseif($uploadingForItem === $item['id'])
                                            <div class="mt-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                                <label class="text-xs font-bold text-purple-800 block mb-2">Cargar imagen de referencia</label>
                                                <input type="file" accept="image/*" wire:model="customizationFile" class="w-full text-xs border rounded p-1">
                                                @error('customizationFile') <span class="text-red-600 text-xs block mt-1">{{ $message }}</span> @enderror
                                                
                                                @if($customizationFile)
                                                    <div class="mt-2">
                                                        <img src="{{ $customizationFile->temporaryUrl() }}" class="w-16 h-16 object-cover rounded mb-2">
                                                        <div class="flex gap-1">
                                                            <button wire:click="saveReferenceImage({{ $item['id'] }})" 
                                                                    class="flex-1 text-xs bg-purple-600 text-white py-1 rounded hover:bg-purple-700">
                                                                Guardar
                                                            </button>
                                                            <button wire:click="$set('uploadingForItem', null); $set('customizationFile', null)" 
                                                                    class="flex-1 text-xs bg-gray-400 text-white py-1 rounded hover:bg-gray-500">
                                                                Cancelar
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <button wire:click="$set('uploadingForItem', {{ $item['id'] }})" 
                                                    class="mt-2 text-xs bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700">
                                                Cargar imagen de referencia
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">${{ number_format($item['precio'], 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <input type="number" min="1" value="{{ $item['cantidad'] }}" wire:change="actualizarCantidad({{ $item['id'] }}, $event.target.value)" class="w-20 px-2 py-1 border rounded text-center">
                            </td>
                            <td class="px-6 py-4 text-right font-semibold">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="eliminar({{ $item['id'] }})" class="text-red-600 hover:text-red-800 transition">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Resumen del Carrito -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2"></div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Resumen del Carrito</h2>
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Envío:</span>
                            <span class="text-green-600 font-semibold">Gratis</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-blue-600">${{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    <button class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold hover:bg-blue-700 transition mb-3">
                        Proceder al Checkout
                    </button>
                    <a href="{{ route('customer.catalog') }}" class="block text-center text-blue-600 hover:text-blue-800 text-sm">
                        ← Continuar comprando
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Tu carrito está vacío</h2>
                <p class="text-gray-600 mb-6">¡Empieza a personalizar tus camisetas ahora!</p>
                <a href="{{ route('customer.catalog') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    Explorar Catálogo
                </a>
            </div>
        @endif
    </div>

    <!-- Modal para ampliar imagen -->
    <script>
        function openPreviewModal(src) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-95 z-[9999] flex items-center justify-center p-4 cursor-pointer';
            modal.innerHTML = `
                <div class="relative max-w-4xl w-full">
                    <img src="${src}" class="w-full max-h-screen object-contain rounded-2xl shadow-2xl">
                    <button onclick="this.parentElement.parentElement.remove()" 
                            class="absolute top-4 right-4 bg-white/90 hover:bg-white rounded-full p-4 shadow-2xl transition-all hover:scale-110">
                        <svg class="w-8 h-8 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            document.body.appendChild(modal);
            modal.addEventListener('click', (e) => e.target === modal && modal.remove());
        }
    </script>
</div>