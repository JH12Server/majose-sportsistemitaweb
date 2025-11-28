<div class="container-fluid py-4">
    <div class="p-4 rounded-3 mb-3 shadow-sm report-hero admin-theme-hero">
        <h2 class="mb-1 fw-semibold text-dark">Productos en Informes <i class="bi bi-exclamation-triangle ms-1 text-secondary"></i></h2>
        <nav class="small" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item">Producto</li>
                <li class="breadcrumb-item active" aria-current="page">Productos Informes</li>
            </ol>
        </nav>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-sm-3">
                    <label class="form-label small">Fecha de inicio</label>
                    <input type="date" wire:model="startDate" class="form-control">
                </div>
                <div class="col-sm-3">
                    <label class="form-label small">Fecha de fin</label>
                    <input type="date" wire:model="endDate" class="form-control">
                </div>
                <div class="col-sm-4">
                    <label class="form-label small">Buscar productos</label>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Buscar por nombre, marca o categoría...">
                </div>
                <div class="col-sm-2 d-grid">
                    <label class="form-label small invisible">Filtrar</label>
                    <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filtrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas resumen -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-black text-white shadow-sm admin-card">
                <div class="card-body">
                    <div class="small">Productos totales</div>
                    <div class="display-6 fw-bold">{{ $summary['total_products'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white text-dark shadow-sm admin-card-outline">
                <div class="card-body">
                    <div class="small">Productos activos</div>
                    <div class="display-6 fw-bold">{{ $summary['active_products'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white text-dark shadow-sm admin-card-outline">
                <div class="card-body">
                    <div class="small">Ingresos totales</div>
                    <div class="display-6 fw-bold">S/. {{ number_format($summary['total_sales'], 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white text-dark shadow-sm admin-card-outline">
                <div class="card-body">
                    <div class="small">Ingresos hoy</div>
                    <div class="display-6 fw-bold">S/. {{ number_format($summary['sales_today'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de productos -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center admin-card-header">
            <div class="fw-semibold text-dark"><i class="bi bi-graph-up-arrow me-2" style="color:#dc3545"></i> Informe de Productos, Ventas y Márgenes</div>
            <div class="text-end d-flex align-items-center" style="gap:.5rem">
                <button id="exportExcel" class="btn btn-dark btn-sm d-flex align-items-center"><i class="bi bi-file-earmark-excel me-2"></i>Excel</button>
                <button id="exportPdf" class="btn btn-danger btn-sm d-flex align-items-center"><i class="bi bi-filetype-pdf me-2"></i>PDF</button>
                <div id="exportBtnsPlaceholder" class="text-end"></div>
            </div>
        </div>
        <div class="p-3">
            <div class="table-responsive">
                <table id="tblProductSales" class="table table-bordered table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Presentación</th>
                            <th>Categorías</th>
                            <th>Cant. Venta</th>
                            <th>Total de Ventas</th>
                            <th>Precio de Compra</th>
                            <th>Precio de Venta</th>
                            <th>% Margen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productSales as $row)
                            <tr>
                                <td>{{ $row['codigo'] }}</td>
                                <td>{{ $row['nombre'] }}</td>
                                <td>{{ $row['marca'] }}</td>
                                <td>{{ $row['presentacion'] }}</td>
                                <td>{{ $row['categorias'] }}</td>
                                <td><span class="badge bg-info">{{ $row['cantidad'] }}</span></td>
                                <td><span class="fw-semibold">S/. {{ number_format($row['total'], 2) }}</span></td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mt-4 g-3">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold"><i class="bi bi-person-lines-fill me-2 text-secondary"></i>Gráfico de Ventas por Usuario</div>
                <div class="card-body"><canvas id="chartSalesUsers" height="140"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-semibold"><i class="bi bi-people me-2 text-secondary"></i>Gráfico de Ventas por Cliente</div>
                <div class="card-body"><canvas id="chartSalesClients" height="140"></canvas></div>
            </div>
        </div>
    </div>

    <!-- Lista de Productos Vendidos -->
    <div class="card shadow-sm mt-4">
        <div class="card-header fw-semibold">Lista de Productos Vendidos</div>
        <div class="p-3">
            <div class="table-responsive">
                <table id="tblSold" class="table table-bordered table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio de Venta</th>
                            <th>Total Venta</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($soldDetails as $d)
                            <tr>
                                <td>{{ $d['id'] }}</td>
                                <td>{{ $d['producto'] }}</td>
                                <td>{{ $d['cantidad'] }}</td>
                                <td><span class="fw-semibold">S/. {{ number_format($d['precio'], 2) }}</span></td>
                                <td><span class="fw-semibold">S/. {{ number_format($d['total'], 2) }}</span></td>
                                <td><span class="badge bg-light text-dark">{{ $d['usuario'] }}</span></td>
                                <td>{{ $d['fecha'] }}</td>
                                <td>
                                    @php($estado = strtolower($d['estado']))
                                    <span class="badge {{ $estado === 'completado' || $estado === 'emitido' ? 'bg-success' : ($estado === 'pendiente' ? 'bg-warning text-dark' : 'bg-danger') }}">{{ ucfirst($d['estado']) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Assets necesarios para DataTables y Chart.js (ubicados dentro del root para evitar múltiples elementos raíz) -->
    <style>
        @import url('https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css');
        @import url('https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css');
        @import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css');
        .card-header { background: #fff; }
        .dataTables_wrapper .dt-buttons .btn { margin-right: .25rem; }
        .table thead th { white-space: nowrap; }
        /* Admin theme: rojo / blanco / negro */
        :root { --admin-red: #dc3545; --admin-black: #000000; --admin-white: #ffffff; }
        .report-hero{ background: linear-gradient(90deg, #fff 0%, rgba(220,53,69,0.03) 100%); border-left: 6px solid var(--admin-red); }
        .admin-theme-hero h2 { color: var(--admin-black); }
        .admin-card { background: var(--admin-black); color: var(--admin-white); }
        .admin-card-outline { background: var(--admin-white); color: var(--admin-black); border: 1px solid rgba(0,0,0,0.06); }
        .admin-card-header { background: var(--admin-white); border-bottom: 1px solid rgba(0,0,0,0.06); }
        /* DataTable export buttons styling overrides */
        .dt-buttons .btn.btn-dark { background: var(--admin-black) !important; border-color: var(--admin-black) !important; color: var(--admin-white) !important; }
        .dt-buttons .btn.btn-danger { background: var(--admin-red) !important; border-color: var(--admin-red) !important; color: var(--admin-white) !important; }

        /* Header and table fine-tuning to match mockup */
        .breadcrumb { margin-bottom: 0.25rem; }
        .report-hero .breadcrumb, .report-hero p, .report-hero .small { color: rgba(0,0,0,0.7); }
        .table thead th { font-weight: 700; color: var(--admin-black); background: var(--admin-white); }
        .table tbody td { vertical-align: middle; }
        .badge.bg-info { background: var(--admin-red) !important; color: var(--admin-white) !important; }
        /* Make first summary card a solid black bar with centered numbers */
        .admin-card .card-body { padding: 1rem !important; }
        .admin-card .display-6 { color: var(--admin-white) !important; }

        /* Strong overrides to ensure theme applies even if global styles interfere */
        .report-hero { padding: 1.25rem !important; border-left: 6px solid var(--admin-red) !important; background: linear-gradient(90deg, #fff 0%, rgba(220,53,69,0.03) 100%) !important; }
        .report-hero h2 { font-size: 1.25rem !important; color: var(--admin-black) !important; margin-bottom: .25rem !important; }
        .admin-card-header { display:flex; align-items:center; justify-content:space-between; gap:.5rem; }
        #exportExcel, #exportPdf { display:inline-flex !important; }
        /* Ensure DataTables buttons container is visible */
        #exportBtnsPlaceholder { display:inline-block !important; }
        /* Table visual adjustments */
        #tblProductSales thead th, #tblSold thead th { background: var(--admin-white) !important; color: var(--admin-black) !important; }
        #tblProductSales, #tblSold { font-size: .92rem !important; }
    </style>
    <script>
        // Diagnostic: log computed styles for hero and export buttons
        document.addEventListener('DOMContentLoaded', function(){
            try {
                const hero = document.querySelector('.report-hero');
                const hStyle = hero ? window.getComputedStyle(hero) : null;
                console.log('REPORTS DEBUG: hero computed background, border-left, padding:', hStyle ? [hStyle.backgroundImage, hStyle.borderLeft, hStyle.padding] : 'no-hero');
                const ex = document.getElementById('exportExcel');
                const ep = document.getElementById('exportPdf');
                console.log('REPORTS DEBUG: export buttons exist?', !!ex, !!ep);
            } catch(e) { console.error('REPORTS DEBUG ERR', e); }
        });
    </script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
        document.addEventListener('livewire:load', function(){
            function initTables(){
                if ($.fn.dataTable.isDataTable('#tblProductSales')) $('#tblProductSales').DataTable().destroy();
                if ($.fn.dataTable.isDataTable('#tblSold')) $('#tblSold').DataTable().destroy();
                const domLayout = "<'row'<'col-sm-6'l><'col-sm-6'f>>"+
                                   "<'row'<'col-12'tr>>"+
                                   "<'row'<'col-sm-6'i><'col-sm-6'p>>";
                const productTable = $('#tblProductSales').DataTable({
                    dom: domLayout,
                    buttons: [
                        { extend: 'copyHtml5', className: 'btn btn-dark text-white', text: '<i class="bi bi-clipboard"></i>' },
                        { extend: 'excelHtml5', className: 'btn btn-dark text-white', text: '<i class="bi bi-file-earmark-excel"></i>' },
                        { extend: 'csvHtml5', className: 'btn btn-dark text-white', text: '<i class="bi bi-filetype-csv"></i>' },
                        { extend: 'pdfHtml5', className: 'btn btn-danger text-white', text: '<i class="bi bi-filetype-pdf"></i>' },
                        { extend: 'print', className: 'btn btn-outline-dark', text: '<i class="bi bi-printer"></i>' }
                    ],
                    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                    order: [[5,'desc']],
                    pageLength: 10,
                    responsive: true
                });
                // Mover botones a la derecha del header
                productTable.buttons().container().appendTo('#exportBtnsPlaceholder');
                // Bind our visible header buttons to the corresponding DataTable export actions
                $('#exportExcel').off('click').on('click', function(){
                    try { productTable.button(1).trigger(); } catch(e){ console.error('Excel export failed', e); }
                });
                $('#exportPdf').off('click').on('click', function(){
                    try { productTable.button(3).trigger(); } catch(e){ console.error('PDF export failed', e); }
                });

                $('#tblSold').DataTable({
                    dom: domLayout,
                    buttons: [
                        { extend: 'copyHtml5', className: 'btn btn-dark text-white', text: '<i class="bi bi-clipboard"></i>' },
                        { extend: 'excelHtml5', className: 'btn btn-dark text-white', text: '<i class="bi bi-file-earmark-excel"></i>' },
                        { extend: 'csvHtml5', className: 'btn btn-dark text-white', text: '<i class="bi bi-filetype-csv"></i>' },
                        { extend: 'pdfHtml5', className: 'btn btn-danger text-white', text: '<i class="bi bi-filetype-pdf"></i>' },
                        { extend: 'print', className: 'btn btn-outline-dark', text: '<i class="bi bi-printer"></i>' }
                    ],
                    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' },
                    order: [[0,'desc']],
                    pageLength: 10,
                    responsive: true
                });
            }
            initTables();
            Livewire.hook('message.processed', (message, component) => { initTables(); });

            // Charts
            const labelsUsers = @json($chartUsersLabels);
            const countsUsers = @json($chartUsersCounts);
            const totalsUsers = @json($chartUsersTotals);
            const ctx1 = document.getElementById('chartSalesUsers');
            const ctx2 = document.getElementById('chartSalesClients');

            if (ctx1) {
                new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: labelsUsers,
                        datasets: [
                            {
                                label: 'Ventas (cantidad)',
                                data: countsUsers,
                                borderWidth: 1,
                                backgroundColor: 'rgba(220,53,69,0.4)',
                                borderColor: 'rgba(220,53,69,1)'
                            },
                            {
                                label: 'Total S/.',
                                data: totalsUsers,
                                type: 'line',
                                yAxisID: 'y1',
                                borderColor: 'rgba(13,110,253,1)',
                                backgroundColor: 'rgba(13,110,253,0.2)',
                                tension: 0.3,
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true },
                            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } }
                        },
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: labelsUsers,
                        datasets: [{
                            data: totalsUsers,
                            backgroundColor: ['#dc3545','#0d6efd','#198754','#ffc107','#6f42c1','#20c997','#fd7e14']
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
