<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $subcategoria->nombre }} - HeavyMarket</title>
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
                    <h1 class="product-detail-title" id="product-title">{{ $subcategoria->nombre }}</h1>
                    
                    <!-- Frame 1984078259 - Primera sección -->
                    <div class="product-detail-section">
                        <!-- Frame 1984078261 - Texto izquierdo -->
                        <div class="product-detail-text">
                            <p class="product-detail-description">
                                {{ $subcategoria->descripcion }}
                            </p>
                        </div>
                        <!-- Imagen derecha -->
                        <div class="product-detail-image">
                            <img src="{{ $subcategoria->imagen_url ?: asset('images/no-image.png') }}" 
                                 alt="{{ $subcategoria->nombre }}" 
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                        </div>
                    </div>
                    
                    <!-- Frame 1984078260 - Segunda sección -->
                    <div class="product-detail-section reverse">
                        <!-- Imagen izquierda -->
                        <div class="product-detail-image">
                            <img src="{{ $subcategoria->imagen_url ?: asset('images/no-image.png') }}" 
                                 alt="{{ $subcategoria->nombre }}" 
                                 onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                        </div>
                        <!-- Frame 1984078261 - Texto y botones derecho -->
                        <div class="product-detail-text">
                            <p class="product-detail-description">
                                {{ $subcategoria->categoria->descripcion_general }}
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
    </body>
</html>
