<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">Mi Perfil de Trabajador</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('worker.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                        ← Volver al dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información del perfil -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="text-center">
                        <div class="w-24 h-24 rounded-full overflow-hidden mx-auto mb-4 bg-blue-100 flex items-center justify-center">
                            @if($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" alt="avatar preview" class="w-24 h-24 object-cover">
                            @elseif(!empty($user->foto))
                                <img src="{{ $user->foto ? asset('storage/' . $user->foto) : asset('images/placeholder.jpg') }}" alt="avatar" class="w-24 h-24 object-cover">
                            @else
                                <svg class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            @endif
                        </div>
                        
                        <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <p class="text-blue-600 font-medium">{{ $user->role ?? 'Trabajador' }}</p>
                        
                        @if($user->work_area)
                            <p class="text-sm text-gray-500 mt-2">{{ $user->work_area }}</p>
                        @endif
                        
                        <div class="mt-6 space-y-2">
                            @if($editMode)
                                <button 
                                    wire:click="saveProfile"
                                    class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    Guardar Cambios
                                </button>
                                <button 
                                    wire:click="toggleEditMode"
                                    class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors"
                                >
                                    Cancelar
                                </button>
                            @else
                                <button 
                                    wire:click="toggleEditMode"
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    Editar Perfil
                                </button>
                                <button 
                                    wire:click="showChangePasswordModal"
                                    class="w-full bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition-colors"
                                >
                                    Cambiar Contraseña
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Estadísticas del trabajador -->
                <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Mis Estadísticas</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pedidos Gestionados</span>
                            <span class="text-lg font-semibold text-blue-600">{{ $workerStats['total_orders_managed'] ?? 0 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Pedidos Completados</span>
                            <span class="text-lg font-semibold text-green-600">{{ $workerStats['orders_completed'] ?? 0 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">En Producción</span>
                            <span class="text-lg font-semibold text-orange-600">{{ $workerStats['orders_in_production'] ?? 0 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Urgentes Atendidos</span>
                            <span class="text-lg font-semibold text-red-600">{{ $workerStats['urgent_orders_handled'] ?? 0 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Tiempo Promedio</span>
                            <span class="text-lg font-semibold text-purple-600">{{ $workerStats['average_completion_time'] ?? 0 }} días</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Este Mes</span>
                            <span class="text-lg font-semibold text-indigo-600">{{ $workerStats['orders_this_month'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulario de edición y historial -->
            <div class="lg:col-span-2 space-y-6">
                @if($editMode)
                    <!-- Formulario de edición -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Editar Información</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto de perfil</label>
                                <input 
                                    type="file" 
                                    accept="image/*" 
                                    wire:model="avatar" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2"
                                >
                                @error('avatar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 mt-1">Formatos: JPG/PNG, máx. 2MB.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                                <input 
                                    wire:model="name" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                >
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input 
                                    wire:model="email" 
                                    type="email" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                >
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input 
                                    wire:model="phone" 
                                    type="tel" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                >
                                @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                                <input 
                                    wire:model="user.role" 
                                    type="text" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user.role') border-red-500 @enderror"
                                >
                                @error('user.role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Área de Trabajo</label>
                                <select 
                                    wire:model="user.work_area" 
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('user.work_area') border-red-500 @enderror"
                                >
                                    <option value="">Seleccionar área</option>
                                    <option value="Producción">Producción</option>
                                    <option value="Bordado">Bordado</option>
                                    <option value="Confección">Confección</option>
                                    <option value="Control de Calidad">Control de Calidad</option>
                                    <option value="Embalaje">Embalaje</option>
                                    <option value="Envíos">Envíos</option>
                                </select>
                                @error('user.work_area') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Historial de pedidos -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Historial de Pedidos Gestionados</h3>
                    
                    @if($recentOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">Pedido #{{ $order->order_number }}</h4>
                                            <p class="text-sm text-gray-500">{{ $order->user->name }}</p>
                                            <p class="text-xs text-gray-400">
                                                Gestionado el {{ $order->status_updated_at ? $order->status_updated_at->format('d/m/Y H:i') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statuses[$order->status] ?? ucfirst($order->status) }}
                                        </span>
                                        <p class="text-sm text-gray-500 mt-1">${{ number_format($order->total_amount, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-6 text-center">
                            <a 
                                href="{{ route('worker.orders') }}" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Ver todos los pedidos
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay pedidos gestionados</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza gestionando pedidos desde el dashboard.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de cambio de contraseña -->
    @if($showPasswordModal)
        <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <!-- Encabezado del modal -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Cambiar Contraseña</h3>
                    <button wire:click="closePasswordModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Cuerpo del modal -->
                <form wire:submit.prevent="changePassword" class="p-6 space-y-6">
                    <!-- Contraseña Actual -->
                    <div>
                        <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Contraseña Actual</label>
                        <div class="relative">
                            <input 
                                id="currentPassword"
                                wire:model.lazy="currentPassword"
                                type="password" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('currentPassword') border-red-500 @enderror"
                                autocomplete="current-password"
                                required
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('currentPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('currentPassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nueva Contraseña -->
                    <div>
                        <label for="newPassword" class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                        <div class="relative">
                            <input 
                                id="newPassword"
                                wire:model.lazy="newPassword"
                                type="password" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('newPassword') border-red-500 @enderror"
                                autocomplete="new-password"
                                required
                                minlength="8"
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('newPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('newPassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                        @enderror
                    </div>

                    <!-- Confirmar Nueva Contraseña -->
                    <div>
                        <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña</label>
                        <div class="relative">
                            <input 
                                id="confirmPassword"
                                wire:model.lazy="confirmPassword"
                                type="password" 
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('confirmPassword') border-red-500 @enderror"
                                autocomplete="new-password"
                                required
                            >
                            <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700" onclick="togglePasswordVisibility('confirmPassword')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('confirmPassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mensajes de error/success -->
                    @if (session()->has('error'))
                        <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Botones -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button 
                            type="button"
                            wire:click="closePasswordModal"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                            wire:loading.attr="disabled"
                            wire:target="changePassword"
                        >
                            <span wire:loading.remove wire:target="changePassword">Cambiar Contraseña</span>
                            <span wire:loading wire:target="changePassword">
                                <i class="fas fa-spinner fa-spin"></i> Procesando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
        <script>
            function togglePasswordVisibility(fieldId) {
                const input = document.getElementById(fieldId);
                const icon = input.nextElementSibling.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }

            document.addEventListener('livewire:load', function () {
                Livewire.on('password-updated', function () {
                    setTimeout(() => {
                        window.location.href = "{{ route('login') }}";
                    }, 2000);
                });
            });
        </script>
        @endpush
    @endif
</div>
