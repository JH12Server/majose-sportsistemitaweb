# 🚀 Instrucciones para Probar el Sistema

## 👥 Usuarios de Prueba Creados

### Cliente
- **Email**: `cliente@test.com`
- **Password**: `password`
- **Acceso**: Vista de cliente con catálogo, carrito y pedidos

### Trabajadores
- **Admin**: `admin@test.com` / `password`
- **Diseñador**: `disenador@test.com` / `password`
- **Bordador**: `bordador@test.com` / `password`
- **Entregas**: `entregas@test.com` / `password`
- **Supervisor**: `supervisor@test.com` / `password`

## 🎯 Cómo Probar el Sistema

### 1. **Iniciar el Servidor**
```bash
php artisan serve
```

### 2. **Acceder al Sistema**
- Ir a: `http://localhost:8000`
- Hacer login con cualquiera de los usuarios de prueba

### 3. **Probar Vista de Cliente**
- Login con: `cliente@test.com` / `password`
- **Funcionalidades a probar**:
  - ✅ Explorar catálogo de productos
  - ✅ Usar filtros y búsqueda
  - ✅ Agregar productos al carrito
  - ✅ Personalizar productos (talla, color, texto)
  - ✅ Ver carrito y proceder al checkout
  - ✅ Ver dashboard con estadísticas

### 4. **Probar Vista de Trabajadores**
- Login con cualquier trabajador (ej: `admin@test.com` / `password`)
- **Funcionalidades a probar**:
  - ✅ Ver dashboard con estadísticas
  - ✅ Gestionar pedidos
  - ✅ Cambiar estados de pedidos
  - ✅ Asignar trabajadores
  - ✅ Usar filtros y búsqueda

## 📱 Rutas Principales

### Clientes
- `/customer/dashboard` - Dashboard del cliente
- `/customer/catalog` - Catálogo de productos
- `/customer/cart` - Carrito de compras
- `/customer/orders` - Mis pedidos

### Trabajadores
- `/worker/dashboard` - Dashboard de trabajadores
- `/worker/orders` - Gestión de pedidos
- `/worker/products` - Gestión de productos
- `/worker/users` - Gestión de usuarios

## 🎨 Productos de Prueba Disponibles

El sistema incluye 8 productos de prueba:
1. **Camiseta Personalizada** - $25.00
2. **Gorra Bordada** - $18.00
3. **Chaqueta con Bordado** - $45.00
4. **Bolsa Tote Personalizada** - $15.00
5. **Polo Empresarial** - $35.00
6. **Mochila Bordada** - $40.00
7. **Delantal de Cocina** - $20.00
8. **Toalla Personalizada** - $30.00

## 🔧 Funcionalidades Destacadas

### Para Clientes
- **Catálogo interactivo** con filtros por categoría, material y precio
- **Búsqueda en tiempo real** por nombre de producto
- **Personalización completa** de productos (talla, color, texto, fuente)
- **Carrito persistente** que mantiene los productos
- **Seguimiento de pedidos** con estados claros

### Para Trabajadores
- **Dashboard personalizado** según el rol
- **Gestión de pedidos** con filtros avanzados
- **Cambio de estados** con validación de permisos
- **Asignación de trabajadores** para distribución de carga
- **Sistema de roles** con permisos específicos

## 🎯 Flujo de Prueba Recomendado

1. **Login como cliente** → Explorar catálogo → Agregar productos al carrito
2. **Personalizar productos** → Proceder al checkout
3. **Login como trabajador** → Ver pedidos → Cambiar estados
4. **Probar diferentes roles** → Verificar permisos específicos

## 📊 Estados de Pedido

- **Pendiente** - Recién creado
- **En Revisión** - Revisando especificaciones
- **En Producción** - Siendo fabricado
- **Listo para Entrega** - Completado
- **Enviado** - En camino
- **Entregado** - Completado
- **Cancelado** - Cancelado por cliente

¡El sistema está listo para probar! 🎉
