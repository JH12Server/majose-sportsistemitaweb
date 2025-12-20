@extends('layouts.app')
@section('title', 'Productos')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Lista de Productos (Catálogo)</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('productos.create') }}" class="btn btn-primary">+ Nuevo Producto</a>
            <button id="exportCsv" class="btn btn-outline-secondary">Exportar CSV</button>
            <button id="exportPdf" class="btn btn-outline-secondary">Exportar PDF</button>
        </div>
    </div>

    <form method="GET" action="" class="mb-3 d-flex justify-content-end align-items-center gap-2">
        <input type="text" name="search" class="form-control w-50 me-2" placeholder="Buscar por nombre, descripción, categoría o marca..." value="{{ request('search') }}">
        <select name="estado" class="form-select w-auto me-2">
            <option value="">Todos</option>
            <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activos</option>
            <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Inactivos</option>
        </select>
        <button type="submit" class="btn btn-outline-secondary">Filtrar</button>
    </form>

    <div class="table-responsive shadow-sm rounded bg-white">
        <table id="productosTable" class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td class="fw-semibold">{{ $producto->name }}</td>
                    <td>{{ Str::limit($producto->description, 80) }}</td>
                    <td>{{ $producto->base_price ? '$' . number_format($producto->base_price,2) : '-' }}</td>
                    <td>{{ $producto->category ?? '-' }}</td>
                    <td>{{ $producto->brand ?? '-' }}</td>
                    <td>
                        @if($producto->is_active)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-primary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar producto?')" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">No hay productos en el catálogo.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        {{ $productos->links() }}
    </div>

    <script>
        function downloadCSV(filename, rows) {
            const csvContent = rows.map(r => r.map(c => '"' + String(c).replace(/"/g, '""') + '"').join(',')).join('\n');
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        document.getElementById('exportCsv')?.addEventListener('click', function() {
            const table = document.getElementById('productosTable');
            if (!table) return;
            const rows = [];
            const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());
            rows.push(headers);
            table.querySelectorAll('tbody tr').forEach(tr => {
                const cols = Array.from(tr.querySelectorAll('td')).map(td => td.innerText.trim());
                if (cols.length) rows.push(cols);
            });
            downloadCSV('productos.csv', rows);
        });

        document.getElementById('exportPdf')?.addEventListener('click', function() {
            // Simple print-to-PDF of the table area
            const printWindow = window.open('', '_blank');
            const tableHtml = document.querySelector('#productosTable').outerHTML;
            printWindow.document.write('<html><head><title>Productos</title>');
            printWindow.document.write('<link rel="stylesheet" href="' + location.origin + '/assets/css/app.css' + '">');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h3>Lista de Productos</h3>');
            printWindow.document.write(tableHtml);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => { printWindow.print(); printWindow.close(); }, 500);
        });
    </script>
</div>
@endsection 