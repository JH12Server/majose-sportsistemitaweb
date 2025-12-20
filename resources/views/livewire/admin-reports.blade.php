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

    <!-- Products Table -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8 border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-black">Gestión de Productos</h3>
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Mostrando {{ $products->total() }} productos</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm">
                <thead class="text-xs text-gray-600 uppercase bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left">ID</th>
                        <th class="px-3 py-2 text-left">Imagen</th>
                        <th class="px-3 py-2 text-left">Nombre</th>
                        <th class="px-3 py-2 text-left">Descripción</th>
                        <th class="px-3 py-2 text-left">Categoría</th>
                        <th class="px-3 py-2 text-right">Precio</th>
                        <th class="px-3 py-2 text-left">Estado</th>
                        <th class="px-3 py-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @foreach($products as $product)
                        <tr>
                            <td class="px-3 py-3 align-top">{{ $product->id }}</td>
                            <td class="px-3 py-3 align-top">
                                <img src="{{ $product->main_image_url ?? asset('images/placeholder.jpg') }}" alt="{{ $product->name }}" class="w-16 h-12 object-cover rounded">
                            </td>
                            <td class="px-3 py-3 align-top font-semibold text-gray-900">{{ $product->name }}</td>
                            <td class="px-3 py-3 align-top text-gray-600">{{ \Illuminate\Support\Str::limit($product->description ?? '-', 80) }}</td>
                            <td class="px-3 py-3 align-top">
                                <span class="text-xs inline-block bg-blue-50 text-blue-700 px-2 py-1 rounded">{{ $product->category ?? '-' }}</span>
                            </td>
                            <td class="px-3 py-3 align-top text-right font-semibold text-gray-800">{{ $product->formatted_price ?? ('$' . number_format($product->base_price ?? 0, 2)) }}</td>
                            <td class="px-3 py-3 align-top">
                                @if($product->is_active)
                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">Activo</span>
                                @else
                                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">Inactivo</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 align-top text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="#" class="text-sm text-blue-600 hover:underline">Editar</a>
                                    <a href="#" class="text-sm text-red-600 hover:underline">Eliminar</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Charts
    function chartsInitialized() {
        const labelsUsers = @json($chartUsersLabels ?? []);
        const countsUsers = @json($chartUsersCounts ?? []);
        const totalsUsers = @json($chartUsersTotals ?? []);

        if (labelsUsers.length === 0) return;

        // Destroy existing charts
        if (window.chartSalesUsers) window.chartSalesUsers.destroy();
        if (window.chartSalesClients) window.chartSalesClients.destroy();

        const ctx1 = document.getElementById('chartSalesUsers');
        if (ctx1) {
            window.chartSalesUsers = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: labelsUsers,
                    datasets: [{
                        label: 'Cantidad de Ventas',
                        data: countsUsers,
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderColor: '#dc3545',
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: { 
                        y: { 
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        },
                        x: {
                            grid: { display: false }
                        }
                    },
                    plugins: { 
                        legend: { position: 'bottom', labels: { font: { size: 12, weight: 'bold' }, color: '#000' } }
                    }
                }
            });
        }

        const ctx2 = document.getElementById('chartSalesClients');
        if (ctx2) {
            window.chartSalesClients = new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: labelsUsers,
                    datasets: [{
                        data: totalsUsers,
                        backgroundColor: ['#dc3545', '#0d6efd', '#198754', '#ffc107', '#6f42c1', '#20c997', '#fd7e14', '#6c757d']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { 
                        legend: { position: 'bottom', labels: { font: { size: 12, weight: 'bold' }, color: '#000' } }
                    }
                }
            });
        }
    }

    chartsInitialized();

    // Export to Excel
    document.getElementById('exportExcel').addEventListener('click', function(e) {
        e.preventDefault();
        
        const rows = [];
        const cells = document.querySelectorAll('table:first tbody tr');
        
        cells.forEach(row => {
            const cols = row.querySelectorAll('td');
            if (cols.length >= 4) {
                rows.push([cols[0].textContent.trim(), cols[1].textContent.trim(), cols[2].textContent.trim(), cols[3].textContent.trim()]);
            }
        });

        let xlsx = '<?xml version="1.0" encoding="UTF-8"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet" xmlns:html="http://www.w3.org/TR/REC-html40"><Worksheet ss:Name="Ventas"><Table>';
        
        // Header
        xlsx += '<Row><Cell ss:StyleID="s1"><Data ss:Type="String">CÓDIGO</Data></Cell><Cell ss:StyleID="s1"><Data ss:Type="String">NOMBRE</Data></Cell><Cell ss:StyleID="s1"><Data ss:Type="String">CANTIDAD</Data></Cell><Cell ss:StyleID="s1"><Data ss:Type="String">TOTAL</Data></Cell></Row>';
        
        // Data rows
        rows.forEach(row => {
            xlsx += '<Row><Cell><Data ss:Type="String">' + escapeXml(row[0]) + '</Data></Cell><Cell><Data ss:Type="String">' + escapeXml(row[1]) + '</Data></Cell><Cell><Data ss:Type="Number">' + escapeXml(row[2]) + '</Data></Cell><Cell><Data ss:Type="String">' + escapeXml(row[3]) + '</Data></Cell></Row>';
        });
        
        xlsx += '</Table><WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel"><Print><ValidPrinterInfo/></Print></WorksheetOptions></Worksheet></Workbook>';
        
        const link = document.createElement('a');
        link.href = 'data:application/vnd.ms-excel;charset=UTF-8,' + encodeURIComponent(xlsx);
        link.download = 'Informe_Productos_' + new Date().toISOString().split('T')[0] + '.xls';
        link.click();
    });

    // Export to PDF
    document.getElementById('exportPdf').addEventListener('click', function(e) {
        e.preventDefault();
        
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF.jsPDF({
            orientation: 'landscape',
            unit: 'mm',
            format: 'a4'
        });
        
        const rows = [];
        const cells = document.querySelectorAll('table:first tbody tr');
        
        cells.forEach(row => {
            const cols = row.querySelectorAll('td');
            if (cols.length >= 4) {
                rows.push([cols[0].textContent.trim(), cols[1].textContent.trim(), cols[2].textContent.trim(), cols[3].textContent.trim()]);
            }
        });

        // Header
        doc.setFillColor(220, 53, 69);
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(18);
        doc.text('INFORME DE PRODUCTOS Y VENTAS', 15, 15);
        
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        doc.text('Fecha: ' + new Date().toLocaleDateString(), 15, 23);

        // Table
        let yPos = 35;
        const pageHeight = doc.internal.pageSize.getHeight();
        
        // Table header
        doc.setFillColor(0, 0, 0);
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(10);
        doc.setFont(undefined, 'bold');
        
        const headers = ['CÓDIGO', 'NOMBRE', 'CANTIDAD', 'TOTAL'];
        const colWidths = [30, 100, 40, 50];
        let xPos = 15;
        
        headers.forEach((header, i) => {
            doc.rect(xPos, yPos - 5, colWidths[i], 7, 'F');
            doc.text(header, xPos + 2, yPos, { maxWidth: colWidths[i] - 4 });
            xPos += colWidths[i];
        });
        
        yPos += 8;
        
        // Table body
        doc.setTextColor(0, 0, 0);
        doc.setFont(undefined, 'normal');
        doc.setFontSize(9);
        
        rows.forEach((row, idx) => {
            if (yPos > pageHeight - 15) {
                doc.addPage();
                yPos = 15;
            }
            
            xPos = 15;
            row.forEach((cell, i) => {
                doc.text(cell, xPos + 2, yPos, { maxWidth: colWidths[i] - 4 });
                xPos += colWidths[i];
            });
            
            if (idx % 2 === 1) {
                doc.setFillColor(240, 240, 240);
            } else {
                doc.setFillColor(255, 255, 255);
            }
            doc.rect(15, yPos - 4, 220, 6, 'F');
            
            yPos += 7;
        });

        doc.save('Informe_Productos_' + new Date().toISOString().split('T')[0] + '.pdf');
    });

    // Helper function to escape XML
    function escapeXml(str) {
        return str.replace(/[<>&'"]/g, function(c) {
            switch (c) {
                case '<': return '&lt;';
                case '>': return '&gt;';
                case '&': return '&amp;';
                case '\'': return '&apos;';
                case '"': return '&quot;';
            }
        });
    }

    // Reinitialize charts after Livewire updates
    if (typeof Livewire !== 'undefined') {
        Livewire.hook('message.processed', () => {
            chartsInitialized();
        });
    }
});
</script>