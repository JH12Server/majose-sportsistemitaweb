<?php
<div class="min-h-screen bg-white">
    <link rel="stylesheet" href="{{ asset('assets/css/customer-dashboard.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a2e0f1f3a6.js" crossorigin="anonymous"></script>
    
    <!-- 🔝 Barra Superior -->
    <header class="top-bar">
        <div class="logo">Confecciones<span>&</span>Bordados</div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Buscar productos...">
            <button id="searchBtn"><i class="fas fa-search"></i></button>
        </div>
        <div class="icons">
            <i class="fas fa-shopping-cart"></i>
            <i class="fas fa-bell"></i>
            <i class="fas fa-user-circle"></i>
        </div>
    </header>

    <!-- 🔻 Barra de Categorías -->
    <nav class="category-bar">
        <div class="categories">
            <button onclick="filterCategory('todos')">Todos</button>
            <button onclick="filterCategory('ropa')">Ropa</button>
            <button onclick="filterCategory('accesorios')">Accesorios</button>
        </div>
    </nav>

    <!-- ⚡ Acciones Rápidas -->
    <section class="quick-actions container">
        <h2>Acciones Rápidas</h2>
        <div class="actions">
            <button onclick="showCart()">Ver Carrito</button>
            <button onclick="showSupport()">Soporte</button>
            <button onclick="showSettings()">Configuración</button>
        </div>
    </section>

    <!-- 🛍️ Productos -->
    <main class="container products">
        <h2>Productos Recomendados</h2>
        <div class="product-grid">
            @foreach($products ?? [] as $product)
                <div class="product-card">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}">
                    <h3>{{ $product->name }}</h3>
                    <p>${{ number_format($product->price, 2) }}</p>
                    <button onclick="addToCart('{{ $product->name }}', {{ $product->price }})">
                        Añadir al carrito
                    </button>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Modal del Carrito -->
    <div id="cartModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed right-0 top-0 h-full w-96 bg-white shadow-xl transform translate-x-full transition-transform duration-300" id="cartSidebar">
            <div class="p-4">
                <h3>Carrito de Compras</h3>
                <div id="cartItems"></div>
                <div class="mt-4">
                    <strong>Total: </strong>
                    <span id="cartTotal">$0.00</span>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById("searchInput");
            const searchBtn = document.getElementById("searchBtn");

            if (searchBtn) {
                searchBtn.addEventListener("click", () => {
                    const query = searchInput.value.trim();
                    if (query !== "") {
                        handleSearch(query);
                    }
                });
            }

            // Initialize cart functionality
            let cartItems = [];
            let cartTotal = 0;

            window.addToCart = function(productName, price) {
                const existingItem = cartItems.find(item => item.name === productName);
                
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cartItems.push({
                        name: productName,
                        price: price,
                        quantity: 1
                    });
                }
                
                updateCartDisplay();
                showCartNotification();
            };

            window.updateCartDisplay = function() {
                const cartItemsContainer = document.getElementById('cartItems');
                const cartTotalElement = document.getElementById('cartTotal');
                
                cartTotal = cartItems.reduce((total, item) => total + (item.price * item.quantity), 0);
                
                if (cartItemsContainer) {
                    cartItemsContainer.innerHTML = cartItems.map(item => `
                        <div class="cart-item">
                            <span>${item.name}</span>
                            <span>x${item.quantity}</span>
                            <span>$${(item.price * item.quantity).toFixed(2)}</span>
                            <button onclick="removeFromCart('${item.name}')">×</button>
                        </div>
                    `).join('');
                }
                
                if (cartTotalElement) {
                    cartTotalElement.textContent = `$${cartTotal.toFixed(2)}`;
                }
            };
        });
    </script>
    @endpush
</div>