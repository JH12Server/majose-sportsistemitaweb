<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-red-700 via-red-600 to-red-800 rounded-xl shadow-2xl p-10 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-5 rounded-full -mr-16 -mt-16"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-3">
                    <div class="bg-white p-3 rounded-lg">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-black">EDITOR DE PÁGINA WEB</h1>
                        <p class="text-white text-sm mt-1 font-semibold">Edita el contenido de MajoseSport de forma visual y sencilla</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensajes de Estado -->
    @if($successMessage)
        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-600 text-green-700 rounded-lg shadow-md">
            <p class="font-bold">{{ $successMessage }}</p>
        </div>
    @endif

    @if($errorMessage)
        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-600 text-red-700 rounded-lg shadow-md">
            <p class="font-bold">{{ $errorMessage }}</p>
        </div>
    @endif

    <!-- Tabs de Navegación -->
    <div class="bg-white rounded-lg shadow-lg mb-8 border-b border-gray-200">
        <div class="flex overflow-x-auto">
            <button wire:click="$set('activeTab', 'hero')" 
                    class="px-6 py-4 font-bold transition {{ $activeTab === 'hero' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-600 hover:text-red-600' }}">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                SECCIÓN HERO
            </button>
            <button wire:click="$set('activeTab', 'products')" 
                    class="px-6 py-4 font-bold transition {{ $activeTab === 'products' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-600 hover:text-red-600' }}">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                PRODUCTOS
            </button>
            <button wire:click="$set('activeTab', 'about')" 
                    class="px-6 py-4 font-bold transition {{ $activeTab === 'about' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-600 hover:text-red-600' }}">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                NOSOTROS
            </button>
            <button wire:click="$set('activeTab', 'contact')" 
                    class="px-6 py-4 font-bold transition {{ $activeTab === 'contact' ? 'border-b-4 border-red-600 text-red-600' : 'text-gray-600 hover:text-red-600' }}">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                CONTACTO
            </button>
        </div>
    </div>

    <!-- Contenido de Tabs -->
    <div class="space-y-6">
        <!-- TAB: HERO -->
        @if($activeTab === 'hero')
        <div class="bg-white rounded-lg shadow-lg p-8 border-l-4 border-red-600">
            <h2 class="text-2xl font-black text-red-600 mb-6">✨ Editar Sección Hero (Banner Principal)</h2>
            
            <form wire:submit="saveHero()" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Título Principal</label>
                    <input type="text" wire:model="hero_title" 
                           class="w-full px-4 py-3 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600"
                           placeholder="Ej: Bienvenido a MajoseSport">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Subtítulo</label>
                    <input type="text" wire:model="hero_subtitle" 
                           class="w-full px-4 py-3 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600"
                           placeholder="Ej: Los mejores artículos deportivos">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Texto del Botón</label>
                    <input type="text" wire:model="hero_button_text" 
                           class="w-full px-4 py-3 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600"
                           placeholder="Ej: Explorar Tienda">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Imagen de Fondo (Opcional)</label>
                    <input type="file" wire:model="hero_image" accept="image/*"
                           class="w-full px-4 py-3 border-2 border-red-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-600">
                    @if($hero_image_url)
                        <div class="mt-4">
                            <img src="{{ $hero_image_url }}" alt="Hero" class="h-32 rounded-lg shadow-lg">
                        </div>
                    @endif
                </div>

                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-lg">
                    💾 GUARDAR SECCIÓN HERO
                </button>
            </form>
        </div>
        @endif

        <!-- TAB: PRODUCTOS -->
        @if($activeTab === 'products')
        <div class="bg-white rounded-lg shadow-lg p-8 border-l-4 border-blue-600">
            <h2 class="text-2xl font-black text-blue-600 mb-6">🛍️ Editar Sección Productos</h2>
            
            <form wire:submit="saveProducts()" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Descripción de Productos</label>
                    <textarea wire:model="products_description" rows="5"
                              class="w-full px-4 py-3 border-2 border-blue-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600"
                              placeholder="Describe tus productos destacados..."></textarea>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <p class="text-sm text-gray-600">
                        <strong>💡 Nota:</strong> Los productos (Mancuernas, Zapatillas, Balones, Raquetas) 
                        se muestran automáticamente. Puedes editarlos directamente en la base de datos si necesitas cambios más avanzados.
                    </p>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-lg">
                    💾 GUARDAR SECCIÓN PRODUCTOS
                </button>
            </form>
        </div>
        @endif

        <!-- TAB: NOSOTROS -->
        @if($activeTab === 'about')
        <div class="bg-white rounded-lg shadow-lg p-8 border-l-4 border-green-600">
            <h2 class="text-2xl font-black text-green-600 mb-6">ℹ️ Editar Sección Nosotros</h2>
            
            <form wire:submit="saveAbout()" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Descripción de la Empresa</label>
                    <textarea wire:model="about_description" rows="5"
                              class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600"
                              placeholder="Cuéntanos sobre tu empresa..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Imagen (Opcional)</label>
                    <input type="file" wire:model="about_image" accept="image/*"
                           class="w-full px-4 py-3 border-2 border-green-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600">
                    @if($about_image_url)
                        <div class="mt-4">
                            <img src="{{ $about_image_url }}" alt="About" class="h-32 rounded-lg shadow-lg">
                        </div>
                    @endif
                </div>

                <hr class="my-4">

                <h3 class="text-lg font-bold text-gray-700">🎯 Características (Features)</h3>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <label class="block text-sm font-bold text-black mb-2">Característica 1 - Título</label>
                    <input type="text" wire:model="feature_1_title" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg mb-2"
                           placeholder="Ej: ✅ Calidad Premium">
                    <label class="block text-sm font-bold text-black mb-2">Característica 1 - Descripción</label>
                    <input type="text" wire:model="feature_1_desc" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                           placeholder="Ej: Productos de las mejores marcas del mundo">
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <label class="block text-sm font-bold text-black mb-2">Característica 2 - Título</label>
                    <input type="text" wire:model="feature_2_title" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg mb-2"
                           placeholder="Ej: 🚚 Envío Rápido">
                    <label class="block text-sm font-bold text-black mb-2">Característica 2 - Descripción</label>
                    <input type="text" wire:model="feature_2_desc" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                           placeholder="Ej: Entrega en 24-48 horas">
                </div>

                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <label class="block text-sm font-bold text-black mb-2">Característica 3 - Título</label>
                    <input type="text" wire:model="feature_3_title" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg mb-2"
                           placeholder="Ej: 💯 Garantía">
                    <label class="block text-sm font-bold text-black mb-2">Característica 3 - Descripción</label>
                    <input type="text" wire:model="feature_3_desc" 
                           class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg"
                           placeholder="Ej: 100% garantía de satisfacción">
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-lg">
                    💾 GUARDAR SECCIÓN NOSOTROS
                </button>
            </form>
        </div>
        @endif

        <!-- TAB: CONTACTO -->
        @if($activeTab === 'contact')
        <div class="bg-white rounded-lg shadow-lg p-8 border-l-4 border-purple-600">
            <h2 class="text-2xl font-black text-purple-600 mb-6">📞 Editar Sección Contacto</h2>
            
            <form wire:submit="saveContact()" class="space-y-6">
                <div>
                    <label class="block text-sm font-bold text-black mb-2">Descripción de Contacto</label>
                    <textarea wire:model="contact_description" rows="4"
                              class="w-full px-4 py-3 border-2 border-purple-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600"
                              placeholder="¿Preguntas o comentarios? Estamos aquí para ayudarte"></textarea>
                </div>

                <hr class="my-4">

                <h3 class="text-lg font-bold text-gray-700">📋 Información de Contacto</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">Teléfono</label>
                        <input type="text" wire:model="phone" 
                               class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg"
                               placeholder="+51 999 999 999">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-black mb-2">Email</label>
                        <input type="email" wire:model="email" 
                               class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg"
                               placeholder="contacto@majosesport.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">Dirección</label>
                    <input type="text" wire:model="address" 
                           class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg"
                           placeholder="Calle Principal 123, Ciudad">
                </div>

                <hr class="my-4">

                <h3 class="text-lg font-bold text-gray-700">🌐 Redes Sociales</h3>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">URL de Facebook</label>
                    <input type="url" wire:model="facebook_url" 
                           class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg"
                           placeholder="https://facebook.com/majosesport">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">URL de Instagram</label>
                    <input type="url" wire:model="instagram_url" 
                           class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg"
                           placeholder="https://instagram.com/majosesport">
                </div>

                <div>
                    <label class="block text-sm font-bold text-black mb-2">URL de Twitter</label>
                    <input type="url" wire:model="twitter_url" 
                           class="w-full px-4 py-2 border-2 border-purple-300 rounded-lg"
                           placeholder="https://twitter.com/majosesport">
                </div>

                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition shadow-lg">
                    💾 GUARDAR SECCIÓN CONTACTO
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Botón de Vista Previa -->
    <div class="mt-8 text-center">
        <a href="{{ route('pagina-web.preview') }}" target="_blank" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition shadow-lg">
            👁️ VER VISTA PREVIA
        </a>
    </div>

    <style>
        .space-y-6 > * + * {
            margin-top: 1.5rem;
        }
    </style>
</div>
