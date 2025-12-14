<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Nuestros Productos - HeavyMarket</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&family=Montserrat:wght@600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
        <link rel="stylesheet" href="{{ asset('css/products.css') }}">
    </head>
    <body>
        <div class="products-page-wrapper">
            <x-navbar />
            
            <!-- Hero Section con búsqueda -->
            <div class="products-hero-section">
                <div class="products-hero-content">
                    <div class="products-hero-overlay"></div>
                    <div class="products-hero-inner">
                        <div class="products-categories-tabs-wrapper">
                            <button class="products-category-arrow products-category-arrow-left" style="display: none;">
                                <svg width="41" height="41" viewBox="0 0 41 41" fill="none">
                                    <path d="M25 10L15 20.5L25 31" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <div class="products-categories-tabs">
                                <div class="products-category-tag active" data-category="all">Todas</div>
                                @foreach($categorias as $categoria)
                                <div class="products-category-tag" data-category="{{ $categoria->slug }}">{{ $categoria->nombre }}</div>
                                @endforeach
                            </div>
                            <button class="products-category-arrow products-category-arrow-right">
                                <svg width="41" height="41" viewBox="0 0 41 41" fill="none">
                                    <path d="M15 10L25 20.5L15 31" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="products-search-container">
                            <div class="products-search-box">
                                <div class="products-search-filter">
                                    <span class="products-search-filter-text">Categoría</span>
                                    <span class="products-search-category-name">Todas</span>
                                </div>
                                <div class="products-search-input-wrapper">
                                    <div class="products-search-input-inner">
                                        <input type="text" 
                                               class="products-search-input" 
                                               placeholder="Buscar productos..." 
                                               aria-label="Buscar productos">
                                        <span class="products-search-close" style="display: none;">×</span>
                                    </div>
                                    <button class="products-search-button">
                                        <span>Buscar</span>
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M9 2C12.866 2 16 5.134 16 9C16 10.7 15.4 12.3 14.4 13.5L17.7 16.8C18.1 17.2 18.1 17.8 17.7 18.2C17.3 18.6 16.7 18.6 16.3 18.2L13 14.9C11.8 15.9 10.2 16.5 8.5 16.5C4.634 16.5 1.5 13.366 1.5 9.5C1.5 5.634 4.634 2.5 8.5 2.5H9V2Z" fill="#FFFFFF"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="products-main-content">
                <!-- Header de productos -->
                <div class="products-header">
                    <h1 class="products-title">Nuestros productos</h1>
                    <p class="products-breadcrumb">Categorías / Productos</p>
                </div>

                <!-- Descripción -->
                <div class="products-description">
                    <p id="categoria-descripcion">{{ $categorias->first()->descripcion_general ?? 'Explore nuestro catálogo completo de productos para maquinaria pesada.' }}</p>
                </div>

                <!-- Grid de productos -->
                <div class="products-grid" id="products-grid">
                    @foreach($categorias as $categoria)
                        @foreach($categoria->subcategorias as $subcategoria)
                    <div class="product-card" data-category="{{ $categoria->slug }}">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ $subcategoria->imagen_url ?: asset('images/no-image.png') }}" 
                                     alt="{{ $subcategoria->nombre }}" 
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">{{ $subcategoria->nombre }}</h3>
                                        <span class="product-category-tag">{{ $categoria->nombre }}</span>
                                    </div>
                                </div>
                                <p class="product-description">{{ Str::limit($subcategoria->descripcion, 150) }}</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', [$categoria->slug, $subcategoria->slug]) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <!-- Sección CTA -->
            <div class="products-cta-section">
                <div class="products-cta-content">
                    <div class="products-cta-column">
                        <h2 class="products-cta-title">Obtén cotizaciones rápidas para piezas de maquinaria</h2>
                    </div>
                    <div class="products-cta-column">
                        <p class="products-cta-description">Nuestra plataforma te permite solicitar cotizaciones en solo tres pasos sencillos. Ahorra tiempo y obtén las mejores ofertas para tus necesidades de maquinaria.</p>
                        <div class="products-cta-actions">
                            <button class="products-cta-button-primary" onclick="window.location.href='{{ route('cotizar') }}'">Cotizar ahora</button>
                            <button class="products-cta-button-secondary">Aprender más</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <x-footer-section />
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryTabsContainer = document.querySelector('.products-categories-tabs');
            const categoryTabs = document.querySelectorAll('.products-categories-tabs .products-category-tag');
            const productCards = document.querySelectorAll('.product-card');
            const searchInput = document.querySelector('.products-search-input');
            const searchButton = document.querySelector('.products-search-button');
            const searchClose = document.querySelector('.products-search-close');
            const categoryNameDisplay = document.querySelector('.products-search-category-name');
            const categoriaDescripcion = document.getElementById('categoria-descripcion');
            const arrowLeft = document.querySelector('.products-category-arrow-left');
            const arrowRight = document.querySelector('.products-category-arrow-right');
            
            let currentCategory = 'all';
            let currentSearchTerm = '';
            
            // Mapeo de categorías a nombres y descripciones
            const categoryNames = {
                'all': 'Todas',
                @foreach($categorias as $categoria)
                '{{ $categoria->slug }}': '{{ $categoria->nombre }}',
                @endforeach
            };

            // Descripciones de categorías
            const categoriasDescripciones = {
                'all': '{{ $categorias->first()->descripcion_general ?? "Explore nuestro catálogo completo de productos para maquinaria pesada." }}',
                @foreach($categorias as $categoria)
                '{{ $categoria->slug }}': {!! json_encode($categoria->descripcion_general) !!},
                @endforeach
            };

            // Función para normalizar texto (quitar acentos, lowercase)
            function normalizeText(text) {
                return text.toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '');
            }

            // Función para actualizar visibilidad de flechas
            function updateArrows() {
                if (!categoryTabsContainer || !arrowLeft || !arrowRight) return;
                
                const scrollLeft = categoryTabsContainer.scrollLeft;
                const maxScroll = categoryTabsContainer.scrollWidth - categoryTabsContainer.clientWidth;
                
                // Mostrar/ocultar flecha izquierda
                if (scrollLeft > 10) {
                    arrowLeft.style.display = 'flex';
                } else {
                    arrowLeft.style.display = 'none';
                }
                
                // Mostrar/ocultar flecha derecha
                if (scrollLeft < maxScroll - 10) {
                    arrowRight.style.display = 'flex';
                } else {
                    arrowRight.style.display = 'none';
                }
            }

            // Función para hacer scroll
            function scrollTabs(direction) {
                if (!categoryTabsContainer) return;
                
                const scrollAmount = categoryTabsContainer.clientWidth * 0.8;
                const targetScroll = categoryTabsContainer.scrollLeft + (direction === 'right' ? scrollAmount : -scrollAmount);
                
                categoryTabsContainer.scrollTo({
                    left: targetScroll,
                    behavior: 'smooth'
                });
            }

            // Event listeners para las flechas
            if (arrowLeft) {
                arrowLeft.addEventListener('click', () => scrollTabs('left'));
            }
            
            if (arrowRight) {
                arrowRight.addEventListener('click', () => scrollTabs('right'));
            }

            // Event listener para actualizar flechas al hacer scroll
            if (categoryTabsContainer) {
                categoryTabsContainer.addEventListener('scroll', updateArrows);
                // Actualizar al cargar y al redimensionar
                window.addEventListener('resize', updateArrows);
                updateArrows();
            }

            // Función principal para filtrar productos
            function filterProducts(category = currentCategory, searchTerm = currentSearchTerm) {
                currentCategory = category;
                currentSearchTerm = searchTerm;
                
                const normalizedSearch = normalizeText(searchTerm);
                let visibleCount = 0;

                // Filtrar productos
                productCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    // Obtener texto del producto para búsqueda
                    const productName = normalizeText(card.querySelector('.product-name')?.textContent || '');
                    const productDescription = normalizeText(card.querySelector('.product-description')?.textContent || '');
                    const productCategoryTag = normalizeText(card.querySelector('.product-category-tag')?.textContent || '');
                    
                    // Verificar si cumple con el filtro de categoría
                    const matchesCategory = category === 'all' || cardCategory === category;
                    
                    // Verificar si cumple con el término de búsqueda
                    const matchesSearch = !normalizedSearch || 
                        productName.includes(normalizedSearch) || 
                        productDescription.includes(normalizedSearch) ||
                        productCategoryTag.includes(normalizedSearch);
                    
                    // Mostrar/ocultar producto
                    if (matchesCategory && matchesSearch) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Actualizar UI
                updateCategoryUI(category);
                
                // Mostrar mensaje si no hay resultados
                updateNoResultsMessage(visibleCount);
            }

            // Función para actualizar la UI de categorías
            function updateCategoryUI(category) {
                // Remover clase active de todas las tabs
                categoryTabs.forEach(tab => {
                    tab.classList.remove('active');
                });

                // Agregar clase active a la tab seleccionada
                const selectedTab = document.querySelector(`.products-categories-tabs .products-category-tag[data-category="${category}"]`);
                if (selectedTab) {
                    selectedTab.classList.add('active');
                }

                // Actualizar nombre de categoría
                if (categoryNameDisplay && categoryNames[category]) {
                    categoryNameDisplay.textContent = categoryNames[category];
                }

                // Actualizar descripción de categoría
                if (categoriaDescripcion && categoriasDescripciones[category]) {
                    categoriaDescripcion.textContent = categoriasDescripciones[category];
                }
            }

            // Función para mostrar mensaje de "sin resultados"
            function updateNoResultsMessage(visibleCount) {
                let noResultsMsg = document.querySelector('.no-results-message');
                
                if (visibleCount === 0) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-results-message';
                        noResultsMsg.innerHTML = `
                            <p style="text-align: center; padding: 40px; font-size: 18px; color: #666;">
                                No se encontraron productos que coincidan con tu búsqueda.
                            </p>
                        `;
                        const grid = document.getElementById('products-grid');
                        if (grid) {
                            grid.appendChild(noResultsMsg);
                        }
                    }
                } else {
                    if (noResultsMsg) {
                        noResultsMsg.remove();
                    }
                }
            }

            // Event listener para el input de búsqueda (tiempo real)
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.trim();
                    
                    // Mostrar/ocultar botón de cerrar
                    if (searchClose) {
                        searchClose.style.display = searchTerm ? 'flex' : 'none';
                    }
                    
                    // Filtrar productos
                    filterProducts(currentCategory, searchTerm);
                });

                // Búsqueda al presionar Enter
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        filterProducts(currentCategory, this.value.trim());
                    }
                });
            }

            // Event listener para el botón de búsqueda
            if (searchButton) {
                searchButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (searchInput) {
                        filterProducts(currentCategory, searchInput.value.trim());
                    }
                });
            }

            // Event listener para el botón de cerrar
            if (searchClose) {
                searchClose.addEventListener('click', function() {
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.focus();
                    }
                    this.style.display = 'none';
                    filterProducts(currentCategory, '');
                });
            }

            // Agregar event listeners a las tabs
            categoryTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    filterProducts(category, currentSearchTerm);
                });
            });

            // Intersection Observer para animar tarjetas al hacer scroll
            const observerOptions = {
                root: null,
                rootMargin: '50px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in');
                        // Una vez que la animación se ha aplicado, podemos dejar de observar
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Función para observar tarjetas visibles
            function observeVisibleCards() {
                productCards.forEach(card => {
                    // Solo observar tarjetas que están visibles (display: flex)
                    const isVisible = card.style.display !== 'none' && 
                                     (card.style.display === 'flex' || !card.style.display);
                    if (isVisible && !card.classList.contains('fade-in')) {
                        card.classList.remove('fade-in');
                        observer.observe(card);
                    }
                });
            }

            // Guardar la función original de filterProducts
            const originalFilterProducts = filterProducts;
            
            // Sobrescribir filterProducts para re-observar después del filtrado
            filterProducts = function(category, searchTerm) {
                originalFilterProducts(category, searchTerm);
                // Re-observar las tarjetas visibles después del filtrado
                setTimeout(observeVisibleCards, 100);
            };

            // Inicializar con "all" (mostrar todos)
            filterProducts('all', '');
            
            // Observar tarjetas iniciales
            observeVisibleCards();
        });
        </script>
    </body>
</html>
