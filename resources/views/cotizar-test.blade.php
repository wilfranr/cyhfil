<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cotiza Ahora - Test</title>
        <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cotizador.css') }}">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                console.log('Alpine.js initialized!');
            });
        </script>
    </head>
    <body>
        <div class="page-wrapper">
            <div class="cotizador-page-container" x-data="{ 
                activeTab: 'construccion',
                selectedCard: null
            }">
                <div class="cotizador-container">
                    <!-- Main Content -->
                    <div class="cotizador-main">
                        <!-- Tabs -->
                        <div class="cotizador-tabs">
                            <div class="cotizador-tab" 
                                 :class="{ 'active': activeTab === 'construccion' }" 
                                 @click="activeTab = 'construccion'; console.log('Clicked construccion', activeTab)">
                                <span class="cotizador-tab-text">Construccion</span>
                            </div>
                            <div class="cotizador-tab" 
                                 :class="{ 'active': activeTab === 'equipo-ligero' }" 
                                 @click="activeTab = 'equipo-ligero'; console.log('Clicked equipo-ligero', activeTab)">
                                <span class="cotizador-tab-text">Equipo Ligero</span>
                            </div>
                            <div class="cotizador-tab" 
                                 :class="{ 'active': activeTab === 'mineria' }" 
                                 @click="activeTab = 'mineria'; console.log('Clicked mineria', activeTab)">
                                <span class="cotizador-tab-text">Minería</span>
                            </div>
                        </div>

                        <!-- Grid -->
                        <div class="cotizador-grid-container">
                            <div class="cotizador-grid" x-show="activeTab === 'construccion'">
                                <h2 style="color: white;">Construcción Content</h2>
                            </div>
                            
                            <div class="cotizador-grid" x-show="activeTab === 'equipo-ligero'">
                                <h2 style="color: white;">Equipo Ligero Content</h2>
                            </div>

                            <div class="cotizador-grid" x-show="activeTab === 'mineria'">
                                <h2 style="color: white;">Minería Content</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
