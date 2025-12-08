# 🛒 Implementación Completa: Sistema de Carrito con Imágenes de Referencia

## 📋 Resumen de Cambios

Se ha implementado un sistema completo de carrito que permite a los usuarios cargar, guardar y visualizar imágenes de referencia para sus pedidos personalizados. El sistema utiliza base de datos en lugar de sesión para mayor persistencia y confiabilidad.

---

## 📁 Archivos Modificados/Creados

### 1. **Componente Livewire** 
📄 `app/Livewire/Carrito.php` - **REESCRITO**

**Características:**
- ✅ Usa `WithFileUploads` para manejo de archivos
- ✅ Carga datos desde base de datos (tabla `carritos`)
- ✅ Soporta tanto usuarios autenticados como sesión (fallback)
- ✅ Gestiona guardado, eliminación y actualización de imágenes
- ✅ Integrado con Storage de Laravel

**Métodos principales:**
```php
loadCart()                           // Carga items de BD
saveReferenceImage($itemId)         // Guarda imagen en storage
clearReferenceImage($itemId)        // Elimina imagen
eliminar($id)                       // Elimina item del carrito
actualizarCantidad($id, $cantidad)  // Actualiza cantidad
```

### 2. **Vista Blade**
📄 `resources/views/livewire/carrito.blade.php` - **ACTUALIZADA**

**Cambios:**
- ✅ Interfaz mejorada para visualización de items
- ✅ Sección para cargar imagen de referencia
- ✅ Vista previa de imagen antes de guardar
- ✅ Botones: "Cargar imagen", "Guardar", "Cancelar", "Cambiar"
- ✅ Muestra imagen guardada con miniatura
- ✅ Actualización en tiempo real con Livewire

### 3. **Modelo**
📄 `app/Models/Carrito.php` - **YA CONFIGURADO**

**Accessors (Propiedades virtuales):**
```php
$carrito->reference_image_url    // URL pública de la imagen
$carrito->customization          // Array con toda personalización
```

**Relaciones:**
- `belongsTo(Product)` - Relación con producto
- `belongsTo(User)` - Relación con usuario

### 4. **Base de Datos**
📄 `database/migrations/2025_12_05_153614_create_carritos_table.php` - **YA EXISTE**

**Tabla `carritos`:**
```
id                          INT PRIMARY
user_id                     INT FK → users
product_id                  INT FK → products
quantity                    INT
unit_price                  DECIMAL(10,2)
size                        VARCHAR(255) NULL
color                       VARCHAR(255) NULL
text                        VARCHAR(255) NULL
font                        VARCHAR(255) NULL
text_color                  VARCHAR(255) NULL
additional_specifications   TEXT NULL
reference_file              VARCHAR(255) NULL ← NUEVA COLUMNA
created_at, updated_at      TIMESTAMP
```

### 5. **Seeder de Prueba** (Opcional)
📄 `database/seeders/CarritoSeeder.php` - **NUEVO**

Para crear items de prueba en el carrito.

---

## 🔐 Seguridad & Validación

- ✅ **Validación de archivo:** `nullable|image|max:4096` (máx 4MB)
- ✅ **Tipos de archivo:** JPG, PNG, GIF, BMP, etc.
- ✅ **Almacenamiento:** Archivos en `/storage/app/public/customizations/`
- ✅ **Permisos:** Solo usuario autenticado puede cargar imágenes
- ✅ **Limpieza:** Archivos se eliminan cuando se elimina el item del carrito

---

## 🚀 Cómo Usar

### Para Usuarios

1. **Ir al carrito:** `/carrito`
2. **Ver items:** Se muestran todos los productos en el carrito
3. **Cargar imagen:**
   - Click en botón "Cargar imagen de referencia"
   - Seleccionar archivo (máx 4MB)
   - Click en "Guardar"
4. **Ver imagen guardada:** Se muestra en miniatura con botón "Cambiar"
5. **Cambiar imagen:** Click en "Cambiar" y repetir proceso

### Para Desarrolladores

#### Agregar manualmente un item al carrito:
```php
Carrito::create([
    'user_id' => Auth::id(),
    'product_id' => $product->id,
    'quantity' => 1,
    'unit_price' => $product->price,
    'size' => 'M',
    'color' => 'Negro',
    'reference_file' => null, // Se rellena cuando usuario carga imagen
]);
```

#### Obtener URL de imagen de un item:
```php
$carrito = Carrito::find($id);
$imageUrl = $carrito->reference_image_url; // /storage/customizations/xxx.jpg
```

#### Acceder a toda la personalización:
```php
$customization = $carrito->customization; // Array con size, color, text, image, etc.
```

---

## 📊 Diagrama de Flujo

```
Usuario Autenticado → /carrito
    ↓
Carrito::mount() → loadCart()
    ↓
Obtiene items de BD (tabla carritos)
    ↓
Muestra lista de items
    ↓
Para cada item:
    ├─ Si tiene imagen guardada:
    │  └─ Muestra miniatura + botón "Cambiar"
    │
    └─ Si NO tiene imagen:
       ├─ Click "Cargar imagen"
       ├─ Select file → Livewire carga temporariamente
       ├─ Preview de imagen
       ├─ Click "Guardar" → saveReferenceImage()
       │  ├─ Guarda en /storage/app/public/customizations/
       │  ├─ Actualiza BD con path
       │  └─ Recarga items (loadCart)
       └─ Imagen aparece automáticamente
```

---

## 🧪 Testing Manual

### Paso a paso:

1. **Verificar tabla en BD:**
   ```bash
   php artisan migrate:status
   # Debería mostrar: 2025_12_05_153614_create_carritos_table ........... [1] Ran
   ```

2. **Crear usuario de prueba:**
   ```bash
   php artisan tinker
   >>> User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => bcrypt('123456')])
   ```

3. **Agregar item al carrito:**
   ```bash
   >>> Carrito::create(['user_id' => 1, 'product_id' => 1, 'quantity' => 1, 'unit_price' => 25.00])
   >>> exit
   ```

4. **Acceder a `/carrito` como el usuario**

5. **Cargar imagen de referencia:**
   - Click en "Cargar imagen de referencia"
   - Seleccionar imagen
   - Click "Guardar"
   - ✅ Debería guardar y mostrar automáticamente

### Verificar archivos guardados:
```bash
ls -la storage/app/public/customizations/
```

---

## ⚙️ Configuración Requerida

✅ **Completa** - Todo está configurado:

- ✅ Migración ejecutada
- ✅ Modelo con accessors
- ✅ Componente Livewire actualizado
- ✅ Vista Blade actualizada
- ✅ Storage configurado (`config/filesystems.php`)
- ✅ Vínculo simbólico creado (`php artisan storage:link`)
- ✅ Rutas definidas (`/carrito` en routes/web.php)
- ✅ Permisos de carpeta (`storage/app/public/` debe ser escribible)

---

## 🐛 Troubleshooting

### Problema: "Imagen de referencia no se muestra"
**Solución:**
```bash
# Verificar vínculo simbólico
php artisan storage:link

# O crear manualmente si falla:
ln -s storage/app/public public/storage  # Linux/Mac
mklink /D public\storage storage\app\public  # Windows CMD
```

### Problema: "Error al guardar imagen"
**Verificar permisos:**
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Problema: "Imagen no carga después de guardar"
**Verificar:**
1. Archivo existe: `storage/app/public/customizations/`
2. BD tiene el path en columna `reference_file`
3. Accessor `reference_image_url` devuelve URL correcta
4. Vínculo simbólico existe: `public/storage/`

---

## 📝 Notas Importantes

- **Persistencia:** Los items se guardan en BD, no en sesión
- **Archivos:** Se almacenan en `/storage/app/public/customizations/`
- **URLs públicas:** `/storage/customizations/nombre.jpg`
- **Validación:** Máximo 4MB por imagen, formatos de imagen estándar
- **Limpieza:** Archivos se eliminan cuando se elimina el item del carrito
- **Respaldo:** Los datos están en BD, los archivos en storage

---

## 📚 Referencia de Código

### Cargar imagen en componente:
```blade
@if($uploadingForItem === $item['id'])
    <input type="file" accept="image/*" wire:model="customizationFile">
    @if($customizationFile)
        <img src="{{ $customizationFile->temporaryUrl() }}">
        <button wire:click="saveReferenceImage({{ $item['id'] }})">Guardar</button>
    @endif
@endif
```

### Mostrar imagen guardada:
```blade
@if($item['customization']['image'])
    <img src="{{ $item['customization']['image'] }}">
@endif
```

---

## ✨ Características Adicionales

- ✅ Vista previa en tiempo real
- ✅ Cambiar imagen sin eliminar item
- ✅ Actualización automática de UI
- ✅ Validación de tipos de archivo
- ✅ Límite de tamaño (4MB)
- ✅ Eliminación automática de archivos
- ✅ Compatible con múltiples items

---

**Estado:** ✅ **COMPLETAMENTE IMPLEMENTADO Y LISTO PARA USAR**

Para cualquier pregunta o problema, revisar la sección "Troubleshooting" o consultar los archivos de configuración.
