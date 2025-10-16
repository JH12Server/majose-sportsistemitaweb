document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle estilo CodArt
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    // Select all sidebar toggler buttons (navbar and sidebar)
    const toggleBtns = document.querySelectorAll('.sidebar-toggler');
    function toggleSidebar(forceState) {
        let isActive;
        if (typeof forceState === 'boolean') {
            isActive = forceState;
            sidebar.classList.toggle('active', isActive);
            mainContent && mainContent.classList.toggle('full', !isActive);
        } else {
            isActive = sidebar.classList.toggle('active');
            mainContent && mainContent.classList.toggle('full', !isActive);
        }
        document.body.classList.toggle('sidebar-open', isActive);
        toggleBtns.forEach(btn => btn.setAttribute('aria-expanded', isActive));
        if (isActive) {
            sidebar.focus && sidebar.focus();
        }
    }
    toggleBtns.forEach(function(toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            toggleSidebar();
        });
    });
    // Cerrar sidebar al hacer click en el overlay
    document.addEventListener('click', function(e) {
        if (document.body.classList.contains('sidebar-open')) {
            // Detecta si se hizo click en el overlay
            if (e.target === document.body && getComputedStyle(document.body, '::after').content !== 'none') {
                toggleSidebar(false);
            }
            // Alternativamente, si el overlay es visible y se hace click fuera del sidebar
            if (e.target === document.body && window.innerWidth <= 992) {
                toggleSidebar(false);
            }
        }
    });

    // Tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.forEach(function (el) {
        new bootstrap.Tooltip(el);
    });

    // Navigation
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links and sections
            document.querySelectorAll('.nav-links a').forEach(l => l.classList.remove('active'));
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            
            // Add active class to clicked link and corresponding section
            this.classList.add('active');
            const sectionId = this.getAttribute('data-section');
            document.getElementById(sectionId).classList.add('active');
        });
    });
    function renderizarTabla() {
        var tabla = document.getElementById("tablaProductos");
        tabla.innerHTML = ""; // Limpiar tabla antes de renderizar

        productosData.forEach(producto => {
            var fila = tabla.insertRow();
            fila.insertCell(0).textContent = producto.id;
            fila.insertCell(1).textContent = producto.producto;
            fila.insertCell(2).textContent = producto.categoria;
            fila.insertCell(3).textContent = `$${producto.precio}`;
            fila.insertCell(4).textContent = producto.stock;
            fila.insertCell(5).textContent = producto.estado;
        });
    }

    function agregarProducto() {
        var producto = document.getElementById("producto").value;
        var categoria = document.getElementById("categoria").value;
        var precio = document.getElementById("precio").value;
        var stock = document.getElementById("stock").value;
        var estado = document.getElementById("estado").value;

        if (producto && categoria && precio && stock && estado) {
            // Crear nuevo objeto producto
            var nuevoProducto = {
                id: productosData.length + 1,
                producto: producto,
                categoria: categoria,
                precio: parseFloat(precio),
                stock: parseInt(stock),
                estado: estado
            };

            // Agregar el nuevo producto a la lista
            productosData.push(nuevoProducto);

            // Limpiar formulario
            document.getElementById("producto").value = "";
            document.getElementById("categoria").value = "";
            document.getElementById("precio").value = "";
            document.getElementById("stock").value = "";

            // Volver a renderizar la tabla
            renderizarTabla();

            // Ocultar formulario después de agregar
            toggleForm();
        } else {
            alert("Por favor, completa todos los campos.");
        }
    }

    // Sample data for each section
    let productosData = [
        { id: 1, producto: 'Producto A', categoria: 'Categoría 1', precio: 1000, stock: 50, estado: 'Disponible' },
        { id: 2, producto: 'Producto B', categoria: 'Categoría 2', precio: 1500, stock: 30, estado: 'Bajo Stock' },
        { id: 3, producto: 'Producto C', categoria: 'Categoría 1', precio: 2000, stock: 0, estado: 'Agotado' },
    ];

    const ventasData = [
        { id: 1, producto: 'Producto 1', cliente: 'Cliente 1', fecha: '2024-01-15', monto: 1500, estado: 'Completado' },
        { id: 2, producto: 'Producto 2', cliente: 'Cliente 2', fecha: '2024-01-14', monto: 2500, estado: 'Pendiente' },
        { id: 3, producto: 'Producto 3', cliente: 'Cliente 3', fecha: '2024-01-13', monto: 3500, estado: 'Completado' }
    ];

    const pedidosData = [
        { id: 1, cliente: 'Cliente A', productos: 'Producto 1, Producto 2', fecha: '2024-01-15', total: 3000, estado: 'En Proceso' },
        { id: 2, cliente: 'Cliente B', productos: 'Producto 3', fecha: '2024-01-14', total: 1500, estado: 'Completado' },
        { id: 3, cliente: 'Cliente C', productos: 'Producto 1', fecha: '2024-01-13', total: 1000, estado: 'Pendiente' }
    ];

    const enviosData = [
        { id: 1, pedido: 'PED001', cliente: 'Cliente A', direccion: 'Dirección 1', fecha: '2024-01-15', estado: 'En Camino' },
        { id: 2, pedido: 'PED002', cliente: 'Cliente B', direccion: 'Dirección 2', fecha: '2024-01-14', estado: 'Entregado' },
        { id: 3, pedido: 'PED003', cliente: 'Cliente C', direccion: 'Dirección 3', fecha: '2024-01-13', estado: 'Pendiente' }
    ];

    const configData = [
        { id: 1, parametro: 'Impuesto', valor: '12%', descripcion: 'IVA', ultimaModificacion: '2024-01-15', estado: 'Activo' },
        { id: 2, parametro: 'Envío Gratis', valor: '$50', descripcion: 'Monto mínimo', ultimaModificacion: '2024-01-14', estado: 'Activo' },
        { id: 3, parametro: 'Descuento', valor: '10%', descripcion: 'Descuento general', ultimaModificacion: '2024-01-13', estado: 'Inactivo' }
    ];

    // Function to populate tables
    function populateTable(tableId, data, columns) {
        const tableBody = document.getElementById(tableId);
        if (!tableBody) return;
        
        tableBody.innerHTML = '';
        
        data.forEach(item => {
            const row = document.createElement('tr');
            let html = '';
            
            columns.forEach(col => {
                if (col === 'estado') {
                    const statusClass = getStatusClass(item[col]);
                    html += `<td><span class="badge ${statusClass}">${item[col]}</span></td>`;
                } else {
                    html += `<td>${item[col]}</td>`;
                }
            });
            
            html += `
                <td>
                    <button class="btn btn-sm btn-primary" onclick="editItem('${tableId}', ${item.id})">
                        <i class='bx bx-edit-alt'></i>
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteItem('${tableId}', ${item.id})">
                        <i class='bx bx-trash'></i>
                    </button>
                </td>
            `;
            
            row.innerHTML = html;
            tableBody.appendChild(row);
        });
    }

    // Function to get status class for badges
    function getStatusClass(status) {
        const statusMap = {
            'Completado': 'bg-success',
            'Pendiente': 'bg-warning',
            'En Proceso': 'bg-info',
            'Disponible': 'bg-success',
            'Bajo Stock': 'bg-warning',
            'Agotado': 'bg-danger',
            'En Camino': 'bg-info',
            'Entregado': 'bg-success',
            'Activo': 'bg-success',
            'Inactivo': 'bg-danger'
        };
        return statusMap[status] || 'bg-secondary';
    }

    // Initialize all tables
    function initializeTables() {
        populateTable('productosTableBody', productosData, ['id', 'producto', 'categoria', 'precio', 'stock', 'estado']);
        populateTable('ventasTableBody', ventasData, ['id', 'producto', 'cliente', 'fecha', 'monto', 'estado']);
        populateTable('pedidosTableBody', pedidosData, ['id', 'cliente', 'productos', 'fecha', 'total', 'estado']);
        populateTable('enviosTableBody', enviosData, ['id', 'pedido', 'cliente', 'direccion', 'fecha', 'estado']);
        populateTable('configTableBody', configData, ['id', 'parametro', 'valor', 'descripcion', 'ultimaModificacion', 'estado']);
    }

    

    // Search functionality for all tables
    function setupSearch(inputId, tableId, data, columns) {
        const searchInput = document.getElementById(inputId);
        if (!searchInput) return;

        searchInput.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filteredData = data.filter(item => 
                columns.some(col => 
                    item[col].toString().toLowerCase().includes(searchTerm)
                )
            );
            populateTable(tableId, filteredData, columns);
        });
    }

    

    function updateTable() {
        const tbody = document.getElementById('tableBody');
        tbody.innerHTML = productosData.map(item => `
            <tr>
                <td>${productosData.id}</td>
                <td>${productosData.producto}</td>
                <td>${productosData.categoria}</td>
                <td>${productosData.precio}</td>
                <td>${productosData.stock}</td>
                <td>${productosData.estado}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-primary btn-sm" onclick="editItem(${productosData.id})">
                            Editar
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteItem(${productosData.id})">
                            Eliminar
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }


    function editItem(id) {
        const item = productosData.find(i => i.id === id);
        if (productosData) {
            document.getElementById('modalTitle').textContent = 'Editar Item';
            document.getElementById('itemproducto').value = productosData.producto
            document.getElementById('itemcategoria').value = pedidosData.categoria
            document.getElementById('itemprecio').value = productosData.precio
            document.getElementById('itemstock').value = productosData.stock
            document.getElementById('itemestado').value = productosData.estado
            document.getElementById('itemForm').dataset.editId = id;
            new bootstrap.Modal(document.getElementById('itemModal')).show();
        }
    }

    function saveItem(){
        const producto = document.getElementById('itemproducto').value;
        const categoria = document.getElementById('itemcategoria').value;
        const precio = document.getElementById('itemprecio').value;
        const stock = document.getElementById('itemstock').value;
        const estado = document.getElementById('itemestado').value;
        const editId = document.getElementById('itemForm').dataset.editId;

        if (editId) {
            const index = productosData.findIndex(i => i.id === parseInt(editId));
            if (index !== -1) {
                items[index] = {
                    ...productosData[index],
                    producto,
                    categoria,
                    precio,
                    stock,
                    estado
                };
            }
        } else {
            const newId = Math.max(...productosData.map(i => i.id)) + 1;
            productosData.push({
                id: newId,
                producto,
                    categoria,
                    precio,
                    stock,
                    estado,
                //date: new Date().toISOString().split('T')[0]
            });
        }
        bootstrap.Modal.getInstance(document.getElementById('itemModal')).hide();
        updateTable();
    }


    // Setup search for all tables
    function initializeSearch() {
        setupSearch('searchProductos', 'productosTableBody', productosData, ['producto', 'categoria']);
        setupSearch('searchVentas', 'ventasTableBody', ventasData, ['producto', 'cliente']);
        setupSearch('searchPedidos', 'pedidosTableBody', pedidosData, ['cliente', 'productos']);
        setupSearch('searchEnvios', 'enviosTableBody', enviosData, ['pedido', 'cliente']);
        setupSearch('searchConfig', 'configTableBody', configData, ['parametro', 'descripcion']);
    }

    // Initialize everything
    initializeTables();
    initializeSearch();

    // Sales Chart
    const ventasCtx = document.getElementById('ventasChart').getContext('2d');
    new Chart(ventasCtx, {
        type: 'bar',
        data: {
            labels: ['2019', '2020', '2021', '2022', '2023'],
            datasets: [{
                label: 'Ventas Anuales',
                data: [65000, 59000, 80000, 81000, 95000],
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Products Chart
    const productosCtx = document.getElementById('productosChart').getContext('2d');
    new Chart(productosCtx, {
        type: 'doughnut',
        data: {
            labels: ['Producto A', 'Producto B', 'Producto C', 'Producto D'],
            datasets: [{
                data: [300, 250, 200, 150],
                backgroundColor: [
                    'rgba(13, 110, 253, 0.5)',
                    'rgba(25, 135, 84, 0.5)',
                    'rgba(255, 193, 7, 0.5)',
                    'rgba(220, 53, 69, 0.5)'
                ],
                borderColor: [
                    'rgba(13, 110, 253, 1)',
                    'rgba(25, 135, 84, 1)',
                    'rgba(255, 193, 7, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});

// Edit and Delete functions
window.editItem = function(tableId, id) {
    console.log(`Editing item ${id} from table ${tableId}`);
    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
    editModal.show();
};

window.deleteItem = function(tableId, id) {
    if (confirm('¿Está seguro de que desea eliminar este elemento?')) {
        console.log(`Deleting item ${id} from table ${tableId}`);
    }
};
