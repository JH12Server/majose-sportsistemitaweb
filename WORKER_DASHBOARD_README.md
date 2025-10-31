# 🏭 Dashboard de Trabajadores - MajoseSport

## 📋 Descripción General

El Dashboard de Trabajadores es una interfaz completa diseñada específicamente para los empleados de producción, bordado y confección de MajoseSport. Permite gestionar pedidos de clientes, actualizar estados de producción, recibir notificaciones en tiempo real y mantener un perfil personalizado.

## ✨ Características Principales

### 🎯 Gestión de Pedidos
- **Visualización completa**: Tabla dinámica con todos los pedidos de clientes
- **Filtros avanzados**: Por estado, prioridad, fecha y cliente
- **Búsqueda inteligente**: Por número de pedido, nombre de cliente o email
- **Ordenamiento**: Por fecha, estado, prioridad o cliente
- **Detalles completos**: Vista modal con información detallada del pedido

### 🔄 Flujo de Producción
- **Estados del pedido**: Pendiente → En Revisión → En Producción → Listo → Enviado → Entregado
- **Cambio de estados**: Con validación de transiciones permitidas
- **Notas de estado**: Campo opcional para comentarios
- **Registro de actividad**: Log completo de cambios con usuario y timestamp
- **Confirmación de cambios**: Modal de confirmación para evitar errores

### 🔔 Sistema de Notificaciones
- **Notificaciones en tiempo real**: Sin recargar la página
- **Tipos de notificación**:
  - Nuevos pedidos
  - Pedidos urgentes
  - Recordatorios de entrega
  - Pedidos asignados
- **Icono flotante**: Con contador de notificaciones no leídas
- **Sonidos de alerta**: Para notificaciones urgentes
- **Panel deslizable**: Vista completa de todas las notificaciones

### 👤 Perfil de Trabajador
- **Información personal**: Edición de datos básicos
- **Estadísticas personales**: Pedidos gestionados, completados, tiempo promedio
- **Historial de trabajo**: Lista de pedidos gestionados
- **Cambio de contraseña**: Con validación de seguridad
- **Área de trabajo**: Especialización del trabajador

### 📱 Diseño Responsive
- **Adaptable a móviles**: Optimizado para tablets y smartphones
- **Iconos flotantes**: Siempre accesibles en pantallas pequeñas
- **Navegación intuitiva**: Menú adaptativo según el tamaño de pantalla
- **Colores distintivos**: Tema naranja específico para trabajadores

## 🚀 Instalación y Configuración

### 1. Archivos Creados

#### Componentes Livewire
- `app/Livewire/WorkerDashboard.php` - Dashboard principal
- `app/Livewire/WorkerFloatingIcons.php` - Iconos flotantes
- `app/Livewire/WorkerProfile.php` - Perfil del trabajador
- `app/Livewire/RealTimeSync.php` - Sincronización en tiempo real

#### Vistas Blade
- `resources/views/livewire/worker-dashboard.blade.php` - Vista principal
- `resources/views/livewire/worker-floating-icons.blade.php` - Iconos flotantes
- `resources/views/livewire/worker-profile.blade.php` - Perfil
- `resources/views/livewire/real-time-sync.blade.php` - Sincronización
- `resources/views/layouts/worker.blade.php` - Layout específico

#### Estilos CSS
- `public/assets/css/worker-dashboard.css` - Estilos específicos

#### Middleware
- `app/Http/Middleware/WorkerMiddleware.php` - Control de acceso

### 2. Rutas Configuradas

```php
// Rutas para trabajadores
Route::middleware('worker')->group(function () {
    Route::get('/worker/dashboard', App\Livewire\WorkerDashboard::class)->name('worker.dashboard');
    Route::get('/worker/orders', App\Livewire\WorkerOrders::class)->name('worker.orders');
    Route::get('/worker/profile', App\Livewire\WorkerProfile::class)->name('worker.profile');
    // ... otras rutas
});
```

### 3. Middleware Registrado

```php
// En bootstrap/app.php
$middleware->alias([
    'worker' => \App\Http\Middleware\WorkerMiddleware::class,
]);
```

## 🎨 Características de Diseño

### Colores del Tema
- **Primario**: Naranja (#f59e0b) - Representa energía y productividad
- **Secundario**: Naranja oscuro (#d97706) - Para elementos destacados
- **Éxito**: Verde (#10b981) - Estados completados
- **Error**: Rojo (#ef4444) - Alertas y errores
- **Advertencia**: Amarillo (#f59e0b) - Estados pendientes

### Estados de Pedidos
- **Pendiente**: Amarillo - Esperando procesamiento
- **En Revisión**: Azul - Siendo evaluado
- **En Producción**: Naranja - En proceso de fabricación
- **Listo**: Verde - Preparado para envío
- **Enviado**: Púrpura - En tránsito
- **Entregado**: Gris - Completado
- **Cancelado**: Rojo - Cancelado

### Prioridades
- **Normal**: Gris - Prioridad estándar
- **Urgente**: Rojo con animación - Requiere atención inmediata
- **Alta**: Naranja - Prioridad elevada

## 🔧 Funcionalidades Técnicas

### Sincronización en Tiempo Real
- **Eventos Livewire**: Comunicación entre componentes
- **Notificaciones automáticas**: Al cambiar estados de pedidos
- **Actualización de estadísticas**: En tiempo real
- **Indicador de conexión**: Estado de sincronización

### Validaciones y Seguridad
- **Middleware de acceso**: Solo trabajadores autenticados
- **Validación de transiciones**: Estados válidos según flujo
- **Logs de actividad**: Registro completo de cambios
- **Confirmación de acciones**: Evitar errores accidentales

### Responsive Design
- **Breakpoints**: Móvil (640px), Tablet (768px), Desktop (1024px+)
- **Iconos adaptativos**: Tamaños según dispositivo
- **Paneles deslizables**: Optimizados para móviles
- **Navegación móvil**: Menú colapsable

## 📊 Estadísticas del Trabajador

### Métricas Personales
- **Total de pedidos gestionados**: Contador general
- **Pedidos completados**: Estados entregados
- **Pedidos en producción**: Estados activos
- **Pedidos urgentes atendidos**: Prioridad alta
- **Tiempo promedio de completado**: Eficiencia
- **Pedidos del mes**: Productividad mensual

### Métricas Generales
- **Total de pedidos**: Todos los pedidos del sistema
- **Pedidos pendientes**: Requieren atención
- **Pedidos en producción**: En proceso
- **Pedidos listos**: Preparados para envío
- **Pedidos urgentes**: Prioridad alta
- **Completados hoy**: Productividad diaria

## 🔔 Sistema de Notificaciones

### Tipos de Notificación
1. **Nuevo pedido**: Cuando se crea un pedido
2. **Pedido urgente**: Prioridad alta asignada
3. **Listo para envío**: Pedido completado
4. **Recordatorio de entrega**: Fecha límite próxima
5. **Pedido asignado**: Asignación específica

### Características
- **Contador visual**: Badge con número de no leídas
- **Sonidos de alerta**: Para notificaciones urgentes
- **Panel deslizable**: Vista completa desde icono flotante
- **Marcado como leído**: Al hacer clic en la notificación
- **Navegación directa**: Enlace al pedido específico

## 🎯 Flujo de Trabajo

### 1. Acceso al Dashboard
- Login como trabajador
- Redirección automática a `/worker/dashboard`
- Verificación de permisos

### 2. Gestión de Pedidos
- Visualizar lista de pedidos
- Aplicar filtros según necesidad
- Ver detalles completos del pedido
- Cambiar estado según progreso

### 3. Actualización de Estados
- Seleccionar nuevo estado
- Agregar notas opcionales
- Confirmar cambio
- Notificación automática al cliente

### 4. Seguimiento de Progreso
- Ver estadísticas personales
- Revisar historial de trabajo
- Monitorear notificaciones
- Actualizar perfil personal

## 🔒 Seguridad y Validaciones

### Control de Acceso
- **Middleware WorkerMiddleware**: Verificación de rol
- **Autenticación requerida**: Login obligatorio
- **Permisos específicos**: Solo trabajadores autorizados

### Validación de Datos
- **Transiciones de estado**: Solo cambios válidos
- **Campos requeridos**: Validación de formularios
- **Sanitización**: Limpieza de datos de entrada
- **Confirmación**: Para acciones críticas

### Logs y Auditoría
- **Registro de cambios**: Quién, qué, cuándo
- **Trazabilidad completa**: Historial de modificaciones
- **Manejo de errores**: Logs detallados
- **Monitoreo**: Seguimiento de actividad

## 📱 Uso en Dispositivos Móviles

### Optimizaciones Móviles
- **Iconos flotantes**: Siempre visibles y accesibles
- **Paneles deslizables**: Optimizados para touch
- **Navegación simplificada**: Menús colapsables
- **Formularios adaptativos**: Campos optimizados

### Gestos Táctiles
- **Deslizar**: Para abrir/cerrar paneles
- **Tocar**: Para seleccionar elementos
- **Pellizcar**: Para zoom en imágenes
- **Scroll**: Para navegar listas largas

## 🚀 Próximas Mejoras

### Funcionalidades Planificadas
- **WebSockets**: Notificaciones en tiempo real más robustas
- **Pusher/Firebase**: Integración con servicios externos
- **Reportes avanzados**: Análisis de productividad
- **Integración con calendario**: Programación de entregas
- **Chat interno**: Comunicación entre trabajadores

### Optimizaciones Técnicas
- **Caché inteligente**: Mejora de rendimiento
- **Lazy loading**: Carga bajo demanda
- **PWA**: Aplicación web progresiva
- **Offline support**: Funcionamiento sin conexión

## 🛠️ Mantenimiento

### Archivos a Monitorear
- `app/Livewire/WorkerDashboard.php` - Lógica principal
- `resources/views/livewire/worker-dashboard.blade.php` - Interfaz
- `public/assets/css/worker-dashboard.css` - Estilos
- `app/Http/Middleware/WorkerMiddleware.php` - Seguridad

### Logs Importantes
- Cambios de estado de pedidos
- Errores de validación
- Accesos no autorizados
- Problemas de sincronización

## 📞 Soporte

Para soporte técnico o reportar problemas:
- **Desarrollador**: Equipo de desarrollo MajoseSport
- **Documentación**: Este archivo README
- **Logs**: Revisar archivos de log de Laravel
- **Base de datos**: Verificar integridad de datos

---

**Versión**: 1.0.0  
**Última actualización**: {{ date('Y-m-d') }}  
**Compatibilidad**: Laravel 11+, Livewire 3+, PHP 8.1+
