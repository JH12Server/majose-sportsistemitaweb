<div class="min-h-screen bg-gray-50">
    <!-- Header con búsqueda -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-1 flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900">Catálogo de Productos</h1>
                </div>
                <div class="flex-1 max-w-lg mx-4">
                    <div class="relative">
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="Buscar productos..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <button 
                    wire:click="toggleFilters" 
                    class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                >
                    <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtros
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar de filtros -->
            @if($showFilters)
            <div class="lg:w-64 bg-white rounded-lg shadow-sm p-6 h-fit">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Filtros</h3>
                    <button wire:click="clearFilters" class="text-sm text-blue-600 hover:text-blue-800">
                        Limpiar todo
                    </button>
                </div>

                <!-- Categoría -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                    <select wire:model.live="category" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todas las categorías</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Material -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Material</label>
                    <select wire:model.live="material" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Todos los materiales</option>
                        @foreach($materials as $mat)
                            <option value="{{ $mat }}">{{ $mat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Rango de precios -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rango de precios</label>
                    <div class="space-y-2">
                        <input 
                            wire:model.live.debounce.300ms="minPrice" 
                            type="number" 
                            placeholder="Precio mínimo" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                        <input 
                            wire:model.live.debounce.300ms="maxPrice" 
                            type="number" 
                            placeholder="Precio máximo" 
                            class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        >
                    </div>
                    @if($priceRange)
                        <p class="text-xs text-gray-500 mt-1">
                            Rango: ${{ number_format($priceRange->min_price, 2) }} - ${{ number_format($priceRange->max_price, 2) }}
                        </p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Contenido principal -->
            <div class="flex-1">
                <!-- Barra de ordenamiento -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Ordenar por:</span>
                            <button 
                                wire:click="sortBy('name')" 
                                class="px-3 py-1 text-sm rounded-md {{ $sortBy === 'name' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                            >
                                Nombre
                                @if($sortBy === 'name')
                                    <svg class="inline h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </button>
                            <button 
                                wire:click="sortBy('base_price')" 
                                class="px-3 py-1 text-sm rounded-md {{ $sortBy === 'base_price' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                            >
                                Precio
                                @if($sortBy === 'base_price')
                                    <svg class="inline h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </button>
                            <button 
                                wire:click="sortBy('production_days')" 
                                class="px-3 py-1 text-sm rounded-md {{ $sortBy === 'production_days' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}"
                            >
                                Tiempo de producción
                                @if($sortBy === 'production_days')
                                    <svg class="inline h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"></path>
                                    </svg>
                                @endif
                            </button>
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ $products->total() }} productos encontrados
                        </div>
                    </div>
                </div>

                <!-- Grid de productos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($products as $product)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
                            <!-- Imagen del producto -->
                            <div class="aspect-square bg-gray-100 relative overflow-hidden">
                                @if($product->main_image)
                                    <img 
                                        src="{{ asset('storage/' . $product->main_image) }}" 
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                <!-- Badge de personalización -->
                                @if($product->allows_customization)
                                    <div class="absolute top-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded-full">
                                        Personalizable
                                    </div>
                                @endif
                            </div>

                            <!-- Información del producto -->
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-2">
                                    {{ $product->name }}
                                </h3>
                                
                                <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                    {{ $product->description }}
                                </p>

                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-2xl font-bold text-blue-600">
                                        {{ $product->formatted_price }}
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        {{ $product->production_days }} días
                                    </span>
                                </div>

                                <!-- Categoría y material -->
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                    <span class="bg-gray-100 px-2 py-1 rounded">{{ $product->category }}</span>
                                    @if($product->material)
                                        <span class="bg-gray-100 px-2 py-1 rounded">{{ $product->material }}</span>
                                    @endif
                                </div>

                                <!-- Botón de agregar al carrito -->
                                <button 
                                    wire:click="addToCart({{ $product->id }})"
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium"
                                >
                                    Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No se encontraron productos</h3>
                            <p class="mt-1 text-sm text-gray-500">Intenta ajustar tus filtros de búsqueda.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Paginación -->
                @if($products->hasPages())
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>