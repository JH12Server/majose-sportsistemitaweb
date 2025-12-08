# Configuración Completa del Carrito con Imágenes de Referencia

## ✅ Cambios Implementados

### 1. Base de Datos
**Tabla:** `carritos` (ya existe desde migración `2025_12_05_153614_create_carritos_table.php`)

**Columnas:**
- `id` - ID del item en el carrito
- `user_id` - Usuario propietario del carrito
- `product_id` - Producto en el carrito
- `quantity` - Cantidad del producto
- `unit_price` - Precio unitario
- `size`, `color`, `text`, `font`, `text_color` - Personalización
- `additional_specifications` - Especificaciones adicionales
- **`reference_file`** - NUEVA: Ruta relativa al archivo de imagen de referencia (ej: `customizations/abc123.jpg`)
- `created_at`, `updated_at` - Timestamps

**Índice Único:** `['user_id', 'product_id', 'size', 'color', 'reference_file']` - Evita duplicados

### 2. Modelo `App\Models\Carrito`
✅ **Ya actualizado con:**
- `$fillable` incluyendo `reference_file`
- Relaciones: `belongsTo(Product)` y `belongsTo(User)`
- **Accessor `reference_image_url`**: Convierte `reference_file` a URL pública completa
  ```php
  // Ej: 'customizations/abc123.jpg' → '/storage/customizations/abc123.jpg'
  ```
- **Accessor `customization`**: Array con toda la personalización incluyendo la URL de imagen

### 3. Componente Livewire `App\Livewire\Carrito`
**Cambios:**
- ✅ Usa trait `WithFileUploads` para manejo de archivos
- ✅ Carga datos de BD: `CarritoModel::where('user_id', Auth::id())`
- ✅ Propiedades:
  - `$items` - Array de items del carrito con datos de BD
  - `$customizationFile` - Archivo temporal durante carga
  - `$uploadingForItem` - Rastrea qué item está cargando imagen
  
- ✅ Métodos:
  - `loadCart()` - Carga desde BD para usuarios autenticados
  - `saveReferenceImage($itemId)` - Guarda imagen en `/storage/app/public/customizations/`
  - `clearReferenceImage($itemId)` - Elimina imagen y limpia BD
  - `eliminar($id)` - Elimina item del carrito
  - `actualizarCantidad($id, $cantidad)` - Actualiza cantidad

### 4. Vista Blade `resources/views/livewire/carrito.blade.php`
**Cambios:**
- ✅ Para cada item:
  - Muestra personalización (talla, color, etc.)
  - **Si existe imagen guardada**: Muestra miniatura con botón "Cambiar"
  - **Si NO existe imagen**: Muestra botón "Cargar imagen de referencia"
    - Al hacer clic: aparece input de archivo + preview
    - Botones: "Guardar" (guarda en BD) y "Cancelar"
  - Input de cantidad actualiza automáticamente en BD
  - Botón eliminar elimina del carrito

### 5. Rutas
✅ Ya configuradas:
```php
Route::get('/carrito', Carrito::class)->name('carrito');  // Líneas 72 y 151 en routes/web.php
```

### 6. Storage
✅ Directorio: `storage/app/public/customizations/`
✅ Vínculo simbólico: `/public/storage` → `/storage/app/public`
- URLs públicas: `/storage/customizations/filename.jpg`

---

## 🔄 Flujo de Funcionamiento

### Para un usuario autenticado:

1. **Cargar carrito:**
   - `Carrito::mount()` ejecuta `loadCart()`
   - Carga desde BD todos los items del usuario
   - Cada item incluye `customization['image']` con la URL de la imagen

2. **Ver imagen guardada:**
   - Si el item tiene `reference_file` en BD
   - El accessor `reference_image_url` la convierte a URL pública
   - Se muestra en la vista con botón "Cambiar"

3. **Cargar nueva imagen:**
   - Click en "Cargar imagen de referencia"
   - `uploadingForItem` = ID del item (identifica cuál está cargando)
   - Input file → `customizationFile` (temporal)
   - Vista previa con Livewire

4. **Guardar imagen:**
   - Click en "Guardar"
   - `saveReferenceImage($itemId)`:
     - Guarda archivo en `/storage/app/public/customizations/`
     - Actualiza BD: `carritos.reference_file = 'customizations/xxx.jpg'`
     - Recarga los items con `loadCart()`
   - Imagen aparece automáticamente en la vista

5. **Cambiar/Eliminar imagen:**
   - Click en "Cambiar"
   - `clearReferenceImage($itemId)`:
     - Elimina archivo de storage
     - Limpia BD: `reference_file = null`
     - Vuelve a mostrar "Cargar imagen"

---

## 📂 Archivos Modificados

| Archivo | Cambio |
|---------|--------|
| `app/Livewire/Carrito.php` | ✅ Reescrito para usar BD en lugar de sesión |
| `resources/views/livewire/carrito.blade.php` | ✅ Actualizado para mostrar/cargar imágenes |
| `app/Models/Carrito.php` | ✅ Ya estaba correctamente configurado |
| `database/migrations/2025_12_05_153614_create_carritos_table.php` | ✅ Ya existe y tiene `reference_file` |

---

## 🧪 Testing

Para verificar que funciona:

1. Ir a `/carrito` (autenticado)
2. Ver items del carrito
3. Click en "Cargar imagen de referencia"
4. Seleccionar imagen (jpg, png, gif, etc. máx 4MB)
5. Click "Guardar"
6. ✅ Imagen aparece guardada
7. Click "Cambiar" para reemplazar

---

## 🛠 Notas Técnicas

- **Storage disk:** `public` → `/storage/app/public`
- **Ruta de imágenes:** `customizations/` subfolder
- **URLs públicas:** `/storage/customizations/filename.jpg`
- **Validación:** `nullable|image|max:4096` (máx 4MB)
- **Eliminación:** Cuando se elimina el item del carrito, la imagen se elimina automáticamente de storage
- **Base de datos:** Registra `reference_file` solo como path relativo para portabilidad

---

## ⚙️ Verificación de Requisitos

✅ Migración ejecutada  
✅ Modelo con accessors  
✅ Componente Livewire con BD  
✅ Vista actualizada  
✅ Storage configurado  
✅ Vínculo simbólico creado  
✅ Rutas definidas  

**LISTO PARA USAR** 🚀
