<div class="navbar-wrapper">
    <div class="navbar-content">
        <div class="navbar-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="HEAVYMarket.NET" class="logo-image">
            </a>
        </div>
        <div class="navbar-frame">
            <div class="navbar-column">
                <div class="navbar-link-frame products-menu-wrapper">
                    <a href="{{ route('productos') }}" class="navbar-link products-link">
                        <span class="link-text">Nuestros productos</span>
                    </a>
                    <!-- Menú desplegable -->
                    <div class="dropdown-menu">
                        <!-- Frame 6 - Contenido principal -->
                        <div class="dropdown-main-content">
                            <!-- Menu - Sidebar izquierdo -->
                            <div class="dropdown-menu-sidebar">
                                <div class="dropdown-menu-inner">
                                    <h3 class="dropdown-menu-title">Productos por categorías</h3>
                                    <ul class="dropdown-menu-list">
                                        @foreach($categoriasNavbar as $categoria)
                                        <li class="dropdown-menu-item @if($loop->first) active @endif" data-category="{{ $categoria->slug }}">
                                            <a href="#" class="dropdown-menu-link" data-target="{{ $categoria->slug }}">{{ $categoria->nombre }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <!-- Frame 10 - Contenido de artículos -->
                            <div class="dropdown-articles-wrapper">
                                @foreach($categoriasNavbar as $index => $categoria)
                                <div class="dropdown-articles-content category-content" data-category="{{ $categoria->slug }}" @if($index > 0) style="display: none;" @endif>
                                    <div class="dropdown-articles-inner">
                                        <div class="dropdown-articles-header">
                                            <h4 class="dropdown-articles-title">{{ $categoria->nombre }}</h4>
                                        </div>
                                        <div class="dropdown-articles-grid">
                                            @php
                                                // Dividir subcategorías en dos columnas
                                                $subcategorias = $categoria->subcategorias;
                                                $mitad = ceil($subcategorias->count() / 2);
                                                $columna1 = $subcategorias->take($mitad);
                                                $columna2 = $subcategorias->slice($mitad);
                                            @endphp
                                            <div class="dropdown-articles-column">
                                                @foreach($columna1 as $subcategoria)
                                                <div class="dropdown-blog-item" onclick="window.location.href='{{ route('producto.detalle', [$categoria->slug, $subcategoria->slug]) }}'">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ $subcategoria->imagen_url }}" alt="{{ $subcategoria->nombre }}">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">{{ $subcategoria->nombre }}</h5>
                                                            <p class="dropdown-blog-description">{{ Str::limit($subcategoria->descripcion, 60) }}</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="dropdown-articles-column">
                                                @foreach($columna2 as $subcategoria)
                                                <div class="dropdown-blog-item" onclick="window.location.href='{{ route('producto.detalle', [$categoria->slug, $subcategoria->slug]) }}'">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ $subcategoria->imagen_url }}" alt="{{ $subcategoria->nombre }}">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">{{ $subcategoria->nombre }}</h5>
                                                            <p class="dropdown-blog-description">{{ Str::limit($subcategoria->descripcion, 60) }}</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="navbar-link-frame">
                    <a href="{{ route('cotizar') }}" class="navbar-link">
                        <span class="link-text">Cotiza ahora</span>
                    </a>
                </div>
                <div class="navbar-link-frame">
                    <a href="#quienes-somos" class="navbar-link">
                        <span class="link-text">Quienes somos</span>
                    </a>
                </div>
                <div class="navbar-link-frame">
                    <a href="#contacto" class="navbar-link">
                        <span class="link-text">Contáctanos</span>
                    </a>
                </div>
            </div>
            <div class="navbar-actions">
                <a href="https://wa.me/573208400279" target="_blank" class="navbar-button" style="text-decoration: none; display: inline-block;">
                    <span class="button-text">WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
    <x-banner />
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsWrapper = document.querySelector('.products-menu-wrapper');
    const dropdownMenu = productsWrapper?.querySelector('.dropdown-menu');
    const productsLink = productsWrapper?.querySelector('.products-link');
    
    let hoverTimeout = null;
    let isClickInProgress = false;
    
    if (productsWrapper && dropdownMenu) {
        // Abrir el menú con un delay para permitir clics rápidos
        productsWrapper.addEventListener('mouseenter', function() {
            // Si hay un clic en progreso, no abrir el menú
            if (isClickInProgress) return;
            
            // Delay de 200ms antes de abrir el menú
            hoverTimeout = setTimeout(function() {
                dropdownMenu.classList.add('active');
                productsWrapper.classList.add('menu-active');
            }, 600);
        });
        
        // Cerrar el menú cuando el mouse sale del wrapper
        productsWrapper.addEventListener('mouseleave', function(e) {
            // Cancelar el timeout si el mouse sale antes de que se abra
            if (hoverTimeout) {
                clearTimeout(hoverTimeout);
                hoverTimeout = null;
            }
            
            // Verificar si el mouse está saliendo hacia el dropdown
            const rect = dropdownMenu.getBoundingClientRect();
            const mouseY = e.clientY;
            const mouseX = e.clientX;
            
            // Si el mouse no está sobre el dropdown, cerrar el menú
            if (mouseY < rect.top || mouseY > rect.bottom || mouseX < rect.left || mouseX > rect.right) {
                dropdownMenu.classList.remove('active');
                productsWrapper.classList.remove('menu-active');
            }
        });
        
        // Mantener el menú abierto cuando el mouse está sobre él
        dropdownMenu.addEventListener('mouseenter', function() {
            if (hoverTimeout) {
                clearTimeout(hoverTimeout);
                hoverTimeout = null;
            }
            dropdownMenu.classList.add('active');
            productsWrapper.classList.add('menu-active');
        });
        
        // Cerrar el menú cuando el mouse sale del dropdown
        dropdownMenu.addEventListener('mouseleave', function() {
            dropdownMenu.classList.remove('active');
            productsWrapper.classList.remove('menu-active');
        });
        
        // Permitir que el enlace sea clickable
        if (productsLink) {
            productsLink.addEventListener('click', function(e) {
                // Marcar que hay un clic en progreso
                isClickInProgress = true;
                
                // Cancelar cualquier timeout pendiente
                if (hoverTimeout) {
                    clearTimeout(hoverTimeout);
                    hoverTimeout = null;
                }
                
                // Cerrar el menú si está abierto
                dropdownMenu.classList.remove('active');
                productsWrapper.classList.remove('menu-active');
                
                // Resetear el flag después de un momento
                setTimeout(function() {
                    isClickInProgress = false;
                }, 100);
                
                // Permitir la navegación normal
            });
        }
    }
    
    // Gestión de categorías
    const menuItems = document.querySelectorAll('.dropdown-menu-item');
    const categoryContents = document.querySelectorAll('.category-content');
    
    // Función para cambiar de categoría
    function switchCategory(targetCategory, activeItem) {
        // Ocultar todos los contenidos primero usando !important
        categoryContents.forEach(content => {
            content.style.setProperty('display', 'none', 'important');
        });
        
        // Remover clase active de todos los items
        menuItems.forEach(menuItem => {
            menuItem.classList.remove('active');
        });
        
        // Mostrar el contenido correspondiente usando !important
        const targetContent = document.querySelector(`.category-content[data-category="${targetCategory}"]`);
        if (targetContent) {
            targetContent.style.setProperty('display', 'flex', 'important');
        }
        
        // Agregar clase active al item activo
        if (activeItem) {
            activeItem.classList.add('active');
        }
    }
    
    menuItems.forEach(item => {
        const link = item.querySelector('.dropdown-menu-link');
        if (link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetCategory = link.getAttribute('data-target');
                switchCategory(targetCategory, item);
            });
            
            // Cambiar al hacer hover
            link.addEventListener('mouseenter', function() {
                const targetCategory = link.getAttribute('data-target');
                switchCategory(targetCategory, item);
            });
        }
    });
});
</script>
