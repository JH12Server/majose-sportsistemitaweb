<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MajoseSport - Sistema de Gestión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-details">
            <img src="Icons/majose logo.png" alt="Descripción de la imagen" style="width: 3rem; height: 3rem;">
            <span class="logo_name">MajoseSport</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="#" class="active" data-section="dashboard">
                    <img src="icons/dashboard.png" alt="Descripción de la imagen" style="width: 3rem; height: 3rem;">
                    <span class="link_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="productos">
                    <img src="icons/productos.png" alt="Descripción de la imagen" style="width: 3rem; height: 3rem;">
                    <span class="link_name">Productos</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="ventas">
                    <img src="icons/ventas.png" alt="Descripción de la imagen" style="width: 3rem; height: 3rem;">
                    <span class="link_name">Ventas</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="pedidos">
                    <img src="icons/pedidos.png" alt="Descripción de la imagen" style="width: 3rem; height: 3rem;">
                    <span class="link_name">Pedidos</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="envios">
                    <img src="icons/envios.png" alt="Descripción de la imagen" style="width: 3rem; height: 3rem;">
                    <span class="link_name">Envíos</span>
                </a>
            </li>
            <li>
                <a href="#" data-section="configuracion">
                    <img src="icons/conf.png" alt="Descripción de la imagen" style="width: 3rem; height: 3rem;">
                <span class="link_name">Configuración</span>
                </a>
            </li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            const sidebarToggle = document.getElementById('sidebarCollapse');
        
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });
        });
        </script>

    <!-- Main Content -->
    <div class="main-content">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button id="sidebarCollapse" class="btn">
                    <i class='bx bx-menu'></i>
                </button>
                <div class="d-flex align-items-center">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            <i class='bx bx-user'></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="login.html">Salir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Dashboard Section -->
        <div class="content-section active" id="dashboard">
            <div class="container-fluid">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class='bx bx-dollar-circle'></i>
                            </div>
                            <div class="stats-info">
                                <h3>$25,000</h3>
                                <p>Ventas Totales</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class='bx bx-package'></i>
                            </div>
                            <div class="stats-info">
                                <h3>150</h3>
                                <p>Productos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class='bx bx-cart'></i>
                            </div>
                            <div class="stats-info">
                                <h3>75</h3>
                                <p>Pedidos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-icon">
                                <i class='bx bx-user'></i>
                            </div>
                            <div class="stats-info">
                                <h3>250</h3>
                                <p>Clientes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Ventas Anuales</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="ventasChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Productos Más Vendidos</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="productosChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Other Sections -->
        <div class="content-section" id="productos">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Lista de Productos</h5>
                        <div class="actions">
                            <input type="text" class="form-control" placeholder="Buscar..." id="searchProductos">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarproducto">
                                <i class='bx bx-plus'></i> Nueva Venta
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productosTableBody">
                                    <!-- Content will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-section" id="ventas">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Registro de Ventas</h5>
                        <div class="actions">
                            <input type="text" class="form-control" placeholder="Buscar..." id="searchVentas">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVentaModal">
                                <i class='bx bx-plus'></i> Nueva Venta
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="ventasTableBody">
                                    <!-- Content will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-section" id="pedidos">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Pedidos Actuales</h5>
                        <div class="actions">
                            <input type="text" class="form-control" placeholder="Buscar..." id="searchPedidos">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPedidoModal">
                                <i class='bx bx-plus'></i> Nuevo Pedido
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Cliente</th>
                                        <th>Productos</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="pedidosTableBody">
                                    <!-- Content will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-section" id="envios">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Control de Envíos</h5>
                        <div class="actions">
                            <input type="text" class="form-control" placeholder="Buscar..." id="searchEnvios">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEnvioModal">
                                <i class='bx bx-plus'></i> Nuevo Envío
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Pedido</th>
                                        <th>Cliente</th>
                                        <th>Dirección</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="enviosTableBody">
                                    <!-- Content will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-section" id="configuracion">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Configuración del Sistema</h5>
                        <div class="actions">
                            <input type="text" class="form-control" placeholder="Buscar..." id="searchConfig">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addConfigModal">
                                <i class='bx bx-plus'></i> Nueva Configuración
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Parámetro</th>
                                        <th>Valor</th>
                                        <th>Descripción</th>
                                        <th>Última Modificación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="configTableBody">
                                    <!-- Content will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addForm">
                        <div class="mb-3">
                            <label class="form-label">Producto</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Monto</label>
                            <input type="number" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-3">
                            <label class="form-label">Producto</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <input type="text" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Monto</label>
                            <input type="number" class="form-control" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>


    <!-- AGREGAR PRODUCTO -->
    <div class="modal fade" id="agregarproducto" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">AGREGAR PRODUCTO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-3">
                            <label class="form-label">ID</label>
                            <input type="text" class="form-control" id="itemid" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PRODUCTO</label>
                            <input type="text" class="form-control" id="itemproducto" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">CATEGORIA</label>
                            <input type="text" class="form-control" id="itemcategoria" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">PRECIO</label>
                            <input type="text" class="form-control" id="itemprecio" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">STOCK</label>
                            <input type="text" class="form-control" id="itemstock" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ESTADO</label>
                            <input type="text" class="form-control" id="itemestado" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="saveItem()">Guardar</button>
                </div>
            </div>
        </div>
    </div>



    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>