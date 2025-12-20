<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-red-700 via-red-600 to-red-800 rounded-xl shadow-2xl p-10 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-3">
                    <div class="bg-white p-3 rounded-lg">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-black text-black">REPORTES Y ANÁLISIS</h1>
                        <p class="text-black text-sm mt-1 font-semibold">Panel de control integral de ventas y productos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 border-l-4 border-red-600">
        <h3 class="text-lg font-bold text-black mb-4 flex items-center gap-2">
            <div class="bg-red-600 p-2 rounded">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
            </div>
            FILTROS
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-bold text-black mb-2">Fecha de inicio</label>
                <input type="date" wire:model="startDate" class="w-full px-4 py-2 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600">
            </div>
            <div>
                <label class="block text-sm font-bold text-black mb-2">Fecha de fin</label>
                <input type="date" wire:model="endDate" class="w-full px-4 py-2 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600">
            </div>
            <div>
                <label class="block text-sm font-bold text-black mb-2">Buscar producto</label>
                <input type="text" wire:model.debounce.500ms="search" class="w-full px-4 py-2 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-red-600" placeholder="Nombre, marca o categoría...">
            </div>
            <div class="flex items-end">
                <button wire:click="updatedSearch()" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2 shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    FILTRAR
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Productos -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-red-600 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-black text-sm font-bold">PRODUCTOS TOTALES</p>
                    <p class="text-4xl font-black text-red-600 mt-2">{{ $summary['total_products'] }}</p>
                </div>
                <div class="bg-red-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Productos Activos -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-green-600 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-black text-sm font-bold">PRODUCTOS ACTIVOS</p>
                    <p class="text-4xl font-black text-green-600 mt-2">{{ $summary['active_products'] }}</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ingresos Totales -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-t-4 border-blue-600 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-black text-sm font-bold">INGRESOS TOTALES</p>
                    <p class="text-3xl font-black text-blue-600 mt-2">$ {{ number_format($summary['total_sales'], 2) }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Ingresos Hoy -->
        <div class="bg-red-600 rounded-lg shadow-lg p-6 border-t-4 border-red-700 hover:shadow-xl transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-black text-sm font-bold">INGRESOS HOY</p>
                    <p class="text-3xl font-black text-black mt-2">$ {{ number_format($summary['sales_today'], 2) }}</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
