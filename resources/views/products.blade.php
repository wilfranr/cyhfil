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
                        <div class="products-categories-tabs">
                            <div class="products-category-tag active" data-category="all">Categoría</div>
                            <div class="products-category-tag" data-category="motores">Motores</div>
                            <div class="products-category-tag" data-category="trenes-rodaje">Trenes de rodaje</div>
                            <div class="products-category-tag" data-category="chasis">Chasis y articulaciones</div>
                            <div class="products-category-tag" data-category="hidraulicos">Hidráulicos</div>
                            <div class="products-category-tag" data-category="orugas">Orugas de goma</div>
                            <div class="products-category-tag" data-category="herramienta">Herramienta de corte</div>
                            <div class="products-category-tag" data-category="electronicos">Electrónicos</div>
                            <div class="products-category-tag" data-category="accesorios-motor">Accesorios de motor</div>
                            <div class="products-category-tag" data-category="aire">Aire acc</div>
                            <div class="products-category-tag" data-category="transmisiones">Transmisiones</div>
                            <div class="products-category-tag" data-category="filtros">Filtros</div>
                            <div class="products-category-tag" data-category="lubricantes">Lubricantes</div>
                            <div class="products-category-tag" data-category="electrico">Sistema eléctrico</div>
                            <div class="products-category-tag" data-category="refrigeracion">Refrigeración</div>
                            <div class="products-category-tag" data-category="combustible">Sistema de combustible</div>
                            <div class="products-category-tag" data-category="escape">Sistema de escape</div>
                            <div class="products-category-tag" data-category="arranque">Sistema de arranque</div>
                            <div class="products-category-tag" data-category="carga">Sistema de carga</div>
                            <div class="products-category-tag" data-category="direccion">Sistema de dirección</div>
                            <div class="products-category-tag" data-category="frenos">Sistema de frenos</div>
                            <div class="products-category-tag" data-category="suspension">Suspensión</div>
                            <div class="products-category-tag" data-category="neumaticos">Neumáticos</div>
                            <div class="products-category-tag" data-category="ruedas">Ruedas</div>
                            <div class="products-category-tag" data-category="mas">Más</div>
                            <button class="products-category-arrow">
                                <svg width="41" height="41" viewBox="0 0 41 41" fill="none">
                                    <path d="M15 10L25 20.5L15 31" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="products-search-container">
                            <div class="products-search-box">
                                <div class="products-search-filter">
                                    <span>Categoría</span>
                                </div>
                                <div class="products-search-input-wrapper">
                                    <div class="products-search-input-inner">
                                        <span class="products-search-text">Motores</span>
                                        <span class="products-search-close">×</span>
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
                    <p class="products-breadcrumb">Motores / Productos</p>
                </div>

                <!-- Descripción -->
                <div class="products-description">
                    <p>El Motor es el corazón de su máquina. Aquí encontrará desde motores completos hasta los componentes esenciales para su óptimo funcionamiento y reparación. Entendemos que un motor eficiente es crucial, por eso ofrecemos productos que transforman la energía en la potencia mecánica que necesitas. Suministramos repuestos de las mejores marcas del mercado para la mayoría de Fabricantes existentes. Componentes fabricados con materiales de alta resistencia, estos están diseñados para soportar las más altas exigencias, asegurando rendimiento, durabilidad y la potencia necesaria.</p>
                </div>

                <!-- Grid de productos -->
                <div class="products-grid" id="products-grid">
                    <!-- Producto 1: Kits de reparación motor -->
                    <div class="product-card" data-category="motores">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ asset('images/kit-reparacion-motor.png') }}" alt="Kits de reparación motor" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Kits de reparación motor</h3>
                                        <span class="product-category-tag">Motores</span>
                                    </div>
                                </div>
                                <p class="product-description">Mantenga su motor en perfecto estado con los más completos Kits de Reparación para Motor. Kits con los componentes esenciales para realizar un mantenimiento preventivo.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'kits-reparacion-motor']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 2: Pistones -->
                    <div class="product-card" data-category="motores">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ asset('images/pistones.png') }}" alt="Pistones" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Pistones</h3>
                                        <span class="product-category-tag">Motores</span>
                                    </div>
                                </div>
                                <p class="product-description">Tenemos una amplia gama de Pistones, tanto enteros como articulados. Fabricados generalmente en aleaciones que brindan alta resistencia.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'pistones']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 3: Casquetes -->
                    <div class="product-card" data-category="motores">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ asset('images/casquetes.png') }}" alt="Casquetes" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Casquetes</h3>
                                        <span class="product-category-tag">Motores</span>
                                    </div>
                                </div>
                                <p class="product-description">Los Casquetes, también conocidos como cojinetes de biela o bancada, son elementos cruciales para la longevidad y el correcto funcionamiento del motor.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'casquetes']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 4: Empaquetaduras -->
                    <div class="product-card" data-category="motores">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ asset('images/empaquetaduras2.png') }}" alt="Empaquetaduras" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Empaquetaduras</h3>
                                        <span class="product-category-tag">Motores</span>
                                    </div>
                                </div>
                                <p class="product-description">Encuentre los juegos de empaques y sellos que necesita para garantizar la estanqueidad perfecta en todas las uniones críticas de tu motor.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'empaquetaduras']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 5: Válvulas -->
                    <div class="product-card" data-category="motores">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ asset('images/valvulas2.png') }}" alt="Válvulas" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Válvulas</h3>
                                        <span class="product-category-tag">Motores</span>
                                    </div>
                                </div>
                                <p class="product-description">Las Válvulas de Motor son componentes esenciales que controlan el flujo de gases dentro y fuera de los cilindros.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'valvulas']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 6: Cigüeñal -->
                    <div class="product-card" data-category="motores">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ asset('images/ciguenal.png') }}" alt="Cigüeñal" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Cigüeñal</h3>
                                        <span class="product-category-tag">Motores</span>
                                    </div>
                                </div>
                                <p class="product-description">El Cigüeñal es una de las piezas más importantes del motor, responsable de convertir el movimiento lineal y alternativo de los pistones en un movimiento rotativo.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'ciguenal']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 7: Árbol de levas -->
                    <div class="product-card" data-category="motores">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ asset('images/arbol-levas.png') }}" alt="Árbol de levas" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Árbol de levas</h3>
                                        <span class="product-category-tag">Motores</span>
                                    </div>
                                </div>
                                <p class="product-description">Un árbol de levas de alta calidad controlará adecuadamente la apertura y el cierre de las válvulas de admisión y escape.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'arbol-levas']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Producto 8: Culatas -->
                    <div class="product-card" data-category="motores">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <img src="{{ asset('images/culatas.png') }}" alt="Culatas" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Culatas</h3>
                                        <span class="product-category-tag">Motores</span>
                                    </div>
                                </div>
                                <p class="product-description">Una culata de buena calidad, sellará apropiadamente el motor, formando una cámara de combustión de alta eficiencia.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'culatas']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Productos de otras categorías para pruebas -->
                    <!-- Transmisiones -->
                    <div class="product-card" data-category="transmisiones">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <!-- <img src="{{ asset('images/caja-cambios.png') }}" alt="Caja de cambios" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"> -->
                                <img src="{{ asset('images/no-image.png') }}" alt="Caja de cambios" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: transparent; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Caja de cambios</h3>
                                        <span class="product-category-tag">Transmisiones</span>
                                    </div>
                                </div>
                                <p class="product-description">Cajas de cambios de alta calidad para maquinaria pesada. Diseñadas para soportar las condiciones más exigentes.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'kits-reparacion-motor']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="product-card" data-category="transmisiones">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <!-- <img src="{{ asset('images/convertidor-par.png') }}" alt="Convertidor de par" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"> -->
                                <img src="{{ asset('images/no-image.png') }}" alt="Convertidor de par" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: transparent; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Convertidor de par</h3>
                                        <span class="product-category-tag">Transmisiones</span>
                                    </div>
                                </div>
                                <p class="product-description">Convertidores de par para transmisiones automáticas. Componentes esenciales para el funcionamiento suave de la maquinaria.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'kits-reparacion-motor']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="product-card" data-category="filtros">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <!-- <img src="{{ asset('images/filtro-aceite.png') }}" alt="Filtro de aceite" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"> -->
                                <img src="{{ asset('images/no-image.png') }}" alt="Filtro de aceite" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: transparent; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Filtro de aceite</h3>
                                        <span class="product-category-tag">Filtros</span>
                                    </div>
                                </div>
                                <p class="product-description">Filtros de aceite de alta eficiencia para proteger el motor. Filtración superior y durabilidad excepcional.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'kits-reparacion-motor']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="product-card" data-category="filtros">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <!-- <img src="{{ asset('images/filtro-aire.png') }}" alt="Filtro de aire" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"> -->
                                <img src="{{ asset('images/no-image.png') }}" alt="Filtro de aire" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: transparent; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Filtro de aire</h3>
                                        <span class="product-category-tag">Filtros</span>
                                    </div>
                                </div>
                                <p class="product-description">Filtros de aire para sistemas de admisión. Protegen el motor de partículas y contaminantes.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'kits-reparacion-motor']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Hidráulicos -->
                    <div class="product-card" data-category="hidraulicos">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <!-- <img src="{{ asset('images/bomba-hidraulica.png') }}" alt="Bomba hidráulica" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"> -->
                                <img src="{{ asset('images/no-image.png') }}" alt="Bomba hidráulica" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: transparent; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Bomba hidráulica</h3>
                                        <span class="product-category-tag">Hidráulicos</span>
                                    </div>
                                </div>
                                <p class="product-description">Bombas hidráulicas de alta presión para sistemas hidráulicos. Rendimiento confiable y eficiencia energética.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'kits-reparacion-motor']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="product-card" data-category="hidraulicos">
                        <div class="product-card-inner">
                            <div class="product-image">
                                <!-- <img src="{{ asset('images/cilindro-hidraulico.png') }}" alt="Cilindro hidráulico" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"> -->
                                <img src="{{ asset('images/no-image.png') }}" alt="Cilindro hidráulico" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: transparent; border-radius: 5.62727px;"></div>
                            </div>
                            <div class="product-info">
                                <div class="product-header-info">
                                    <div class="product-name-wrapper">
                                        <h3 class="product-name">Cilindro hidráulico</h3>
                                        <span class="product-category-tag">Hidráulicos</span>
                                    </div>
                                </div>
                                <p class="product-description">Cilindros hidráulicos de doble efecto. Potencia y precisión para aplicaciones industriales.</p>
                                <button class="product-button" onclick="window.location.href='{{ route('producto.detalle', ['slug' => 'kits-reparacion-motor']) }}'">
                                    <span class="button-text">Saber más</span>
                                </button>
                            </div>
                        </div>
                    </div>
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
                            <button class="products-cta-button-primary">Cotizar ahora</button>
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
            const categoryTabs = document.querySelectorAll('.products-category-tag');
            const productCards = document.querySelectorAll('.product-card');
            const searchText = document.querySelector('.products-search-text');
            
            // Mapeo de categorías a nombres para mostrar
            const categoryNames = {
                'all': 'Categoría',
                'motores': 'Motores',
                'trenes-rodaje': 'Trenes de rodaje',
                'chasis': 'Chasis y articulaciones',
                'hidraulicos': 'Hidráulicos',
                'orugas': 'Orugas de goma',
                'herramienta': 'Herramienta de corte',
                'electronicos': 'Electrónicos',
                'accesorios-motor': 'Accesorios de motor',
                'aire': 'Aire acc',
                'transmisiones': 'Transmisiones',
                'filtros': 'Filtros',
                'lubricantes': 'Lubricantes',
                'electrico': 'Sistema eléctrico',
                'refrigeracion': 'Refrigeración',
                'combustible': 'Sistema de combustible',
                'escape': 'Sistema de escape',
                'arranque': 'Sistema de arranque',
                'carga': 'Sistema de carga',
                'direccion': 'Sistema de dirección',
                'frenos': 'Sistema de frenos',
                'suspension': 'Suspensión',
                'neumaticos': 'Neumáticos',
                'ruedas': 'Ruedas',
                'mas': 'Más'
            };

            // Función para filtrar productos
            function filterProducts(category) {
                // Remover clase active de todas las tabs
                categoryTabs.forEach(tab => {
                    tab.classList.remove('active');
                });

                // Agregar clase active a la tab seleccionada
                const selectedTab = document.querySelector(`[data-category="${category}"]`);
                if (selectedTab) {
                    selectedTab.classList.add('active');
                }

                // Filtrar productos
                productCards.forEach(card => {
                    const cardCategory = card.getAttribute('data-category');
                    
                    if (category === 'all' || cardCategory === category) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Actualizar texto de búsqueda
                if (searchText && categoryNames[category]) {
                    searchText.textContent = categoryNames[category];
                }
            }

            // Agregar event listeners a las tabs
            categoryTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const category = this.getAttribute('data-category');
                    filterProducts(category);
                });
            });

            // Inicializar con "all" (mostrar todos)
            filterProducts('all');
        });
        </script>
    </body>
</html>

