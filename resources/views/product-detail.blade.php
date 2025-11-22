<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Detalle de Producto - HeavyMarket</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&family=Montserrat:wght@600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
        <link rel="stylesheet" href="{{ asset('css/products.css') }}">
        <link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
    </head>
    <body>
        <div class="product-detail-wrapper">
            <x-navbar />
            
            <!-- Contenido principal - Frame 1984078232 -->
            <div class="product-detail-content">
                <!-- Frame 1984078260 -->
                <div class="product-detail-container">
                    <!-- Título - Medium length hero headline goes here -->
                    <h1 class="product-detail-title" id="product-title">Pistones</h1>
                    
                    <!-- Frame 1984078259 - Primera sección -->
                    <div class="product-detail-section">
                        <!-- Frame 1984078261 - Texto izquierdo -->
                        <div class="product-detail-text">
                            <p class="product-detail-description">
                                Tenemos una amplia gama de Pistones, tanto enteros como articulados. Fabricados generalmente en aleaciones que brindan alta resistencia. Un pistón de calidad es vital para la eficiencia de la combustión, la potencia del motor, la reducción de la fricción y la durabilidad general del motor.
                            </p>
                        </div>
                        <!-- Imagen derecha -->
                        <div class="product-detail-image">
                            <img src="{{ asset('images/products/piston.jpg') }}" alt="Pistones" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 24px;"></div>
                        </div>
                    </div>
                    
                    <!-- Frame 1984078260 - Segunda sección -->
                    <div class="product-detail-section reverse">
                        <!-- Imagen izquierda -->
                        <div class="product-detail-image">
                            <img src="{{ asset('images/products/piston.jpg') }}" alt="Pistones instalación" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <div class="product-image-placeholder" style="display: none; width: 100%; height: 100%; background: #E5E5E5; border-radius: 24px;"></div>
                        </div>
                        <!-- Frame 1984078261 - Texto y botones derecho -->
                        <div class="product-detail-text">
                            <p class="product-detail-description">
                                Suministramos pistones sueltos, kits de pistón y juegos de anillos. Tenemos para los diferentes rangos de motores Diesel para maquinaria pesada en marcas como IPD, Mahle, CTP, FP Diesel, Izumi y las marcas más confiables del mercado.
                            </p>
                            <!-- Actions - Botones -->
                            <div class="product-detail-actions">
                                <button class="product-detail-button primary" onclick="window.location.href='{{ route('cotizar') }}'">
                                    <span class="button-text">Cotizar ahora</span>
                                </button>
                                <button class="product-detail-button secondary" onclick="window.history.back()">
                                    <span class="button-text">Volver</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <x-footer-section />
        </div>

        <script>
        // Datos de productos (simulado)
        const productsData = {
            'kits-reparacion-motor': {
                title: 'Kits de reparación motor',
                description1: 'Mantenga su motor en perfecto estado con los más completos Kits de Reparación para Motor. Kits con los componentes esenciales para realizar un mantenimiento preventivo y garantizar el rendimiento óptimo de su maquinaria.',
                description2: 'Suministramos kits completos de reparación con empaques, sellos, anillos y componentes esenciales. Disponibles para diferentes rangos de motores Diesel en marcas confiables del mercado.',
                image1: '{{ asset("images/kit-reparacion-motor.png") }}',
                image2: '{{ asset("images/kit-reparacion-motor2.png") }}'
            },
            'pistones': {
                title: 'Pistones',
                description1: 'Tenemos una amplia gama de Pistones, tanto enteros como articulados. Fabricados generalmente en aleaciones que brindan alta resistencia. Un pistón de calidad es vital para la eficiencia de la combustión, la potencia del motor, la reducción de la fricción y la durabilidad general del motor.',
                description2: 'Suministramos pistones sueltos, kits de pistón y juegos de anillos. Tenemos para los diferentes rangos de motores Diesel para maquinaria pesada en marcas como IPD, Mahle, CTP, FP Diesel, Izumi y las marcas más confiables del mercado.',
                image1: '{{ asset("images/products/piston.jpg") }}',
                image2: '{{ asset("images/products/piston.jpg") }}'
            },
            'casquetes': {
                title: 'Casquetes',
                description1: 'Los Casquetes, también conocidos como cojinetes de biela o bancada, son elementos cruciales para la longevidad y el correcto funcionamiento del motor. Fabricados con materiales de alta calidad para soportar las condiciones más exigentes.',
                description2: 'Ofrecemos casquetes de precisión para bielas y bancadas. Componentes esenciales que garantizan el correcto funcionamiento y la durabilidad del motor en maquinaria pesada.',
                image1: '{{ asset("images/products/casquetes.jpg") }}',
                image2: '{{ asset("images/products/casquetes.jpg") }}'
            },
            'empaquetaduras': {
                title: 'Empaquetaduras',
                description1: 'Encuentre los juegos de empaques y sellos que necesita para garantizar la estanqueidad perfecta en todas las uniones críticas de tu motor. Componentes esenciales para prevenir fugas y mantener la presión adecuada.',
                description2: 'Suministramos empaquetaduras de alta calidad para culatas, múltiples de admisión y escape, y todas las uniones críticas del motor. Disponibles en diferentes materiales según la aplicación.',
                image1: '{{ asset("images/products/empaquetadura.jpg") }}',
                image2: '{{ asset("images/products/empaquetadura.jpg") }}'
            },
            'valvulas': {
                title: 'Válvulas',
                description1: 'Las Válvulas de Motor son componentes esenciales que controlan el flujo de gases dentro y fuera de los cilindros. Fabricadas con materiales de alta resistencia para soportar altas temperaturas y presiones.',
                description2: 'Ofrecemos válvulas de admisión y escape de alta calidad. Componentes diseñados para garantizar un sellado perfecto y un flujo óptimo de gases en el motor.',
                image1: '{{ asset("images/products/valvulas.jpg") }}',
                image2: '{{ asset("images/products/valvulas.jpg") }}'
            },
            'ciguenal': {
                title: 'Cigüeñal',
                description1: 'El Cigüeñal es una de las piezas más importantes del motor, responsable de convertir el movimiento lineal y alternativo de los pistones en un movimiento rotativo. Fabricado con materiales de alta resistencia.',
                description2: 'Suministramos cigüeñales de precisión para diferentes rangos de motores. Componentes esenciales que garantizan la transmisión eficiente de potencia en maquinaria pesada.',
                image1: '{{ asset("images/products/ciguenal.jpg") }}',
                image2: '{{ asset("images/products/ciguenal.jpg") }}'
            },
            'arbol-levas': {
                title: 'Árbol de levas',
                description1: 'Un árbol de levas de alta calidad controlará adecuadamente la apertura y el cierre de las válvulas de admisión y escape. Componente esencial para el funcionamiento eficiente del motor.',
                description2: 'Ofrecemos árboles de levas de precisión para diferentes configuraciones de motor. Diseñados para garantizar el timing perfecto y la eficiencia del sistema de válvulas.',
                image1: '{{ asset("images/products/eje-levas.jpg") }}',
                image2: '{{ asset("images/products/eje-levas.jpg") }}'
            },
            'culatas': {
                title: 'Culatas',
                description1: 'Una culata de buena calidad, sellará apropiadamente el motor, formando una cámara de combustión de alta eficiencia. Componente crítico para el rendimiento del motor.',
                description2: 'Suministramos culatas de alta calidad para diferentes rangos de motores. Fabricadas con materiales de alta resistencia para soportar las condiciones más exigentes.',
                image1: '{{ asset("images/products/culata.jpg") }}',
                image2: '{{ asset("images/products/culata.jpg") }}'
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const slug = '{{ $slug }}';
            const product = productsData[slug];
            
            if (product) {
                document.getElementById('product-title').textContent = product.title;
                const descriptions = document.querySelectorAll('.product-detail-description');
                if (descriptions[0]) descriptions[0].textContent = product.description1;
                if (descriptions[1]) descriptions[1].textContent = product.description2;
                
                const images = document.querySelectorAll('.product-detail-image img');
                if (images[0] && product.image1) images[0].src = product.image1;
                if (images[1] && product.image2) images[1].src = product.image2;
            }
        });
        </script>
    </body>
</html>

