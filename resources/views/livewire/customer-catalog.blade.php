<div class="min-h-screen bg-gray-50">
    <!-- Header con búsqueda -->
    <div class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Catálogo de Productos</h1>
                    <span class="text-sm text-gray-500">{{ $products->total() }} productos</span>
                </div>
                
                <!-- Búsqueda -->
                <div class="flex-1 max-w-lg mx-8">
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

                <!-- Controles -->
                <div class="flex items-center space-x-4">
                    <!-- Filtros -->
                    <button 
                        wire:click="toggleFilters"
                        class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                    >
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                        </svg>
                        Filtros
                    </button>

                    <!-- Vista -->
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <button 
                            wire:click="toggleViewMode"
                            class="p-2 hover:bg-gray-100 transition-colors {{ $viewMode === 'grid' ? 'bg-gray-100' : '' }}"
                            title="Vista de cuadrícula"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                        </button>
                        <button 
                            wire:click="toggleViewMode"
                            class="p-2 hover:bg-gray-100 transition-colors {{ $viewMode === 'list' ? 'bg-gray-100' : '' }}"
                            title="Vista de lista"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar de filtros -->
            @if($showFilters)
                <div class="lg:w-64 flex-shrink-0">
                    <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Filtros</h3>
                            <button 
                                wire:click="clearFilters"
                                class="text-sm text-blue-600 hover:text-blue-800"
                            >
                                Limpiar
                            </button>
                        </div>

                        <!-- Categorías -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
                            <select wire:model.live="selectedCategory" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todas las categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Marca -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Marca</label>
                            <select wire:model.live="selectedBrand" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todas las marcas</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand }}">{{ $brand }}</option>
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
                            <div class="text-xs text-gray-500 mt-1">
                                Rango: ${{ number_format($priceRange['min'], 0) }} - ${{ number_format($priceRange['max'], 0) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Contenido principal -->
            <div class="flex-1">
                <!-- Ordenamiento -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">Ordenar por:</span>
                            <select wire:model.live="sortBy" class="border border-gray-300 rounded-md px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="name">Nombre</option>
                                <option value="base_price">Precio</option>
                                <option value="created_at">Más recientes</option>
                                <option value="category">Categoría</option>
                            </select>
                            <button 
                                wire:click="sortBy('{{ $sortBy }}')"
                                class="p-1 hover:bg-gray-100 rounded transition-colors"
                            >
                                <svg class="h-4 w-4 {{ $sortDirection === 'asc' ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="text-sm text-gray-600">
                            Mostrando {{ $products->count() }} de {{ $products->total() }} productos
                        </div>
                    </div>
                </div>

                <!-- Grid de productos -->
                @if($products->count() > 0)
                    <div class="{{ $viewMode === 'grid' ? 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6' : 'space-y-4' }}">
                        @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200 {{ $viewMode === 'list' ? 'flex items-center p-4' : 'p-4' }}">
                                @if($viewMode === 'grid')
                                    <!-- Vista de cuadrícula -->
                                    <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden mb-4">
                                        @if($product->main_image)
                                            <img 
                                                src="{{ asset('storage/' . $product->main_image) }}" 
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-200"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <h3 class="font-semibold text-gray-900 line-clamp-2">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $product->category }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-bold text-blue-600">${{ number_format($product->base_price, 2) }}</span>
                                            @if($product->allows_customization)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Personalizable
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex space-x-2">
                                            <button 
                                                wire:click="showProductDetail({{ $product->id }})"
                                                class="flex-1 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors"
                                            >
                                                Ver detalles
                                            </button>
                                            <button 
                                                wire:click="addToCart({{ $product->id }})"
                                                class="flex-1 px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 transition-colors"
                                            >
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <!-- Vista de lista -->
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 mr-4">
                                        @if($product->main_image)
                                            <img 
                                                src="{{ asset('storage/' . $product->main_image) }}" 
                                                alt="{{ $product->name }}"
                                                class="w-full h-full object-cover"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-500">{{ $product->category }}</p>
                                        @if($product->description)
                                            <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $product->description }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-blue-600">${{ number_format($product->base_price, 2) }}</div>
                                            @if($product->allows_customization)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    Personalizable
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex space-x-2">
                                            <button 
                                                wire:click="showProductDetail({{ $product->id }})"
                                                class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors"
                                            >
                                                Ver detalles
                                            </button>
                                            <button 
                                                wire:click="addToCart({{ $product->id }})"
                                                class="px-3 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 transition-colors"
                                            >
                                                Agregar
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Paginación -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Estado vacío -->
                    <div class="text-center py-12">
                        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No se encontraron productos</h3>
                        <p class="mt-2 text-gray-500">Intenta ajustar los filtros de búsqueda.</p>
                        <div class="mt-6">
                            <button 
                                wire:click="clearFilters"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200"
                            >
                                Limpiar filtros
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal de detalles del producto -->
    @if($showProductModal && $selectedProduct)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ $selectedProduct->name }}
                        </h3>
                        <button wire:click="closeProductModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Imagen del producto -->
                        <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                            @if($selectedProduct->main_image)
                                <img 
                                    src="{{ asset('storage/' . $selectedProduct->main_image) }}" 
                                    alt="{{ $selectedProduct->name }}"
                                    class="w-full h-full object-cover"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="h-16 w-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Información del producto -->
                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Categoría</h4>
                                <p class="text-sm text-gray-900">{{ $selectedProduct->category }}</p>
                            </div>

                            @if($selectedProduct->brand)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Marca</h4>
                                    <p class="text-sm text-gray-900">{{ $selectedProduct->brand }}</p>
                                </div>
                            @endif

                            <div>
                                <h4 class="text-sm font-medium text-gray-700">Precio</h4>
                                <p class="text-2xl font-bold text-blue-600">${{ number_format($selectedProduct->base_price, 2) }}</p>
                            </div>

                            @if($selectedProduct->description)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700">Descripción</h4>
                                    <p class="text-sm text-gray-900">{{ $selectedProduct->description }}</p>
                                </div>
                            @endif

                            @if($selectedProduct->allows_customization)
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3">
                                    <div class="flex items-center">
                                        <svg class="h-5 w-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-purple-800">Este producto es personalizable</span>
                                    </div>
                                    <p class="text-sm text-purple-700 mt-1">Puedes agregar texto, cambiar colores y especificar tallas.</p>
                                </div>
                            @endif

                            <div class="flex space-x-3">
                                <button 
                                    wire:click="addToCart({{ $selectedProduct->id }})"
                                    class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                >
                                    Agregar al carrito
                                </button>
                                <button 
                                    wire:click="closeProductModal"
                                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors"
                                >
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>