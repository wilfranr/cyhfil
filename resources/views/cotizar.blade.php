<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cotiza Ahora - HEAVYMarket</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&family=Poppins:wght@400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ time() }}">
        <link rel="stylesheet" href="{{ asset('css/cotizador.css') }}?v={{ time() }}">
    </head>
    <body>
        <div class="page-wrapper">
            <x-navbar />
            
            <div class="cotizador-page-container">
                <div class="cotizador-container">
                    <!-- Sidebar -->
                    <div class="cotizador-sidebar">
                        <div class="cotizador-sidebar-header">
                            <p class="cotizador-sidebar-text">Selecciona una categoría, luego elige marca, tipo y modelo de la máquina para continuar.</p>
                        </div>

                        <div class="cotizador-filters">
                            <!-- Marca -->
                            <div class="cotizador-filter-group" x-data="{ open: false }" @click.away="open = false">
                                <button class="cotizador-select" @click="open = !open">
                                    <span class="cotizador-select-text" x-text="selectedBrand || 'Seleccionar una marca'"></span>
                                    <div class="cotizador-select-icon">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </div>
                                </button>
                                <div class="cotizador-dropdown" x-show="open" x-transition>
                                    <template x-for="brand in brands" :key="brand">
                                        <div class="cotizador-dropdown-item" @click="selectedBrand = brand; open = false" x-text="brand"></div>
                                    </template>
                                </div>
                            </div>

                            <!-- Modelo -->
                            <div class="cotizador-filter-group" x-data="{ open: false }" @click.away="open = false">
                                <button class="cotizador-select" @click="open = !open">
                                    <span class="cotizador-select-text" x-text="selectedModel || 'Seleccionar modelo de máquina'"></span>
                                    <div class="cotizador-select-icon">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </div>
                                </button>
                                <div class="cotizador-dropdown" x-show="open" x-transition>
                                    <template x-for="model in models" :key="model">
                                        <div class="cotizador-dropdown-item" @click="selectedModel = model; open = false" x-text="model"></div>
                                    </template>
                                </div>
                            </div>

                            <!-- Serie -->
                            <div class="cotizador-filter-group" x-data="{ open: false }" @click.away="open = false">
                                <button class="cotizador-select" @click="open = !open">
                                    <span class="cotizador-select-text" x-text="selectedSeries || 'Serie de la máquina'"></span>
                                </button>
                                <div class="cotizador-dropdown" x-show="open" x-transition>
                                    <template x-for="serie in series" :key="serie">
                                        <div class="cotizador-dropdown-item" @click="selectedSeries = serie; open = false" x-text="serie"></div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="cotizador-actions">
                            <button class="cotizador-btn-secondary" @click="selectedBrand = ''; selectedModel = ''; selectedSeries = ''">
                                Limpiar
                            </button>
                            <button class="cotizador-btn-primary">
                                Continuar
                            </button>
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="cotizador-main">
                        <!-- Tabs -->
                        <div class="cotizador-tabs">
                            <div class="cotizador-tab active">
                                <span class="cotizador-tab-text">Construccion</span>
                            </div>
                            <div class="cotizador-tab">
                                <span class="cotizador-tab-text">Equipo Ligero</span>
                            </div>
                            <div class="cotizador-tab">
                                <span class="cotizador-tab-text">Minería</span>
                            </div>
                            <div class="cotizador-tab">
                                <span class="cotizador-tab-text">Pavimentación</span>
                            </div>
                            <div class="cotizador-tab">
                                <span class="cotizador-tab-text">Subterráneo</span>
                            </div>
                            <div class="cotizador-tab">
                                <span class="cotizador-tab-text">Utilitarios</span>
                            </div>
                        </div>

                        <!-- Grid -->
                        <div class="cotizador-grid-container">
                            <div class="cotizador-grid">
                                <!-- Card 1: Bulldozer -->
                                <div class="cotizador-card">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/bulldozer.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Bulldozer</h3>
                                        <div class="cotizador-card-tag">
                                            <span>Construcción</span>
                                        </div>
                                        <p class="cotizador-card-description">Máquina pesada con una cuchilla frontal utilizada para mover, nivelar y excavar grandes cantidades de tierra o material en construcción y minería.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>

                                <!-- Card 2: Cargador Frontal -->
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'cargador' }" @click="selectedCard = 'cargador'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/cargador.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Cargador Frontal</h3>
                                        <div class="cotizador-card-tag">
                                            <span>Construcción</span>
                                        </div>
                                        <p class="cotizador-card-description">Máquina de construcción equipada con un cubo grande en la parte delantera, utilizada para cargar, mover y apilar materiales como tierra, grava o escombros.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>

                                <!-- Card 3: Compactador Sencillo -->
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'compactador' }" @click="selectedCard = 'compactador'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/compactador.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Compactador Sencillo</h3>
                                        <div class="cotizador-card-tag">
                                            <span>Construcción</span>
                                        </div>
                                        <p class="cotizador-card-description">Utilizada para compactar suelos y materiales. Tiene un rodillo que aplica presión para reducir el volumen y aumentar la densidad del terreno.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>

                                <!-- Card 4: Dumper Articulado -->
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'dumper' }" @click="selectedCard = 'dumper'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/dumper.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Dumper Articulado</h3>
                                        <div class="cotizador-card-tag">
                                            <span>Construcción</span>
                                        </div>
                                        <p class="cotizador-card-description">Vehículo de carga utilizado en terrenos difíciles. Cuenta con una cabina separada del cuerpo de carga y una articulación en el centro.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>

                                <!-- Card 5: Excavadora de Orugas -->
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'excavadora-orugas' }" @click="selectedCard = 'excavadora-orugas'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/excavadora-orugas.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Excavadora de Orugas</h3>
                                        <div class="cotizador-card-tag">
                                            <span>Construcción</span>
                                        </div>
                                        <p class="cotizador-card-description">Máquina con una pala o cuchara para excavar, remover y trasladar materiales. Su característica principal son las orugas.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>

                                <!-- Card 6: Excavadora de Ruedas -->
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'excavadora-ruedas' }" @click="selectedCard = 'excavadora-ruedas'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/excavadora-ruedas.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Excavadora de Ruedas</h3>
                                        <div class="cotizador-card-tag">
                                            <span>Construcción</span>
                                        </div>
                                        <p class="cotizador-card-description">Similar a la excavadora de orugas, pero con ruedas en lugar de orugas. Esto le permite moverse más rápido y con mayor maniobrabilidad.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Equipo Ligero Tab -->
                            <div class="cotizador-grid" style="display: none;">
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'miniexcavadora' }" @click="selectedCard = 'miniexcavadora'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/miniexcavadora.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Miniexcavadora</h3>
                                        <div class="cotizador-card-tag"><span>Equipo Ligero</span></div>
                                        <p class="cotizador-card-description">Excavadora compacta ideal para trabajos en espacios reducidos, jardines y proyectos urbanos.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'minicargador' }" @click="selectedCard = 'minicargador'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/minicargador.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Minicargador</h3>
                                        <div class="cotizador-card-tag"><span>Equipo Ligero</span></div>
                                        <p class="cotizador-card-description">Cargador compacto versátil con múltiples accesorios para diversas aplicaciones.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'plataforma-elevadora' }" @click="selectedCard = 'plataforma-elevadora'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/plataforma.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Plataforma Elevadora</h3>
                                        <div class="cotizador-card-tag"><span>Equipo Ligero</span></div>
                                        <p class="cotizador-card-description">Equipo para trabajos en altura con plataforma estable y segura.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Minería Tab -->
                            <div class="cotizador-grid" style="display: none;">
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'camion-minero' }" @click="selectedCard = 'camion-minero'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/camion-minero.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Camión Minero</h3>
                                        <div class="cotizador-card-tag"><span>Minería</span></div>
                                        <p class="cotizador-card-description">Camión de gran capacidad diseñado para transporte de material en operaciones mineras.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'pala-minera' }" @click="selectedCard = 'pala-minera'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/pala-minera.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Pala Minera</h3>
                                        <div class="cotizador-card-tag"><span>Minería</span></div>
                                        <p class="cotizador-card-description">Pala hidráulica de gran tamaño para excavación y carga en minería a cielo abierto.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'perforadora-minera' }" @click="selectedCard = 'perforadora-minera'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/perforadora-minera.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Perforadora Minera</h3>
                                        <div class="cotizador-card-tag"><span>Minería</span></div>
                                        <p class="cotizador-card-description">Equipo de perforación de alta potencia para operaciones de voladura en minería.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Pavimentación Tab -->
                            <div class="cotizador-grid" style="display: none;">
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'pavimentadora' }" @click="selectedCard = 'pavimentadora'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/pavimentadora.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Pavimentadora</h3>
                                        <div class="cotizador-card-tag"><span>Pavimentación</span></div>
                                        <p class="cotizador-card-description">Máquina para extender y nivelar asfalto en la construcción de carreteras.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'rodillo-vibratorio' }" @click="selectedCard = 'rodillo-vibratorio'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/rodillo-vibratorio.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Rodillo Vibratorio</h3>
                                        <div class="cotizador-card-tag"><span>Pavimentación</span></div>
                                        <p class="cotizador-card-description">Compactador vibratorio para asfalto y bases de carreteras.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'fresadora-asfalto' }" @click="selectedCard = 'fresadora-asfalto'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/fresadora.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Fresadora de Asfalto</h3>
                                        <div class="cotizador-card-tag"><span>Pavimentación</span></div>
                                        <p class="cotizador-card-description">Máquina para remover capas de asfalto en trabajos de repavimentación.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Subterráneo Tab -->
                            <div class="cotizador-grid" style="display: none;">
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'tuneladora' }" @click="selectedCard = 'tuneladora'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/tuneladora.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Tuneladora</h3>
                                        <div class="cotizador-card-tag"><span>Subterráneo</span></div>
                                        <p class="cotizador-card-description">Máquina especializada para excavación de túneles con sistema de soporte integrado.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'jumbo-perforacion' }" @click="selectedCard = 'jumbo-perforacion'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/jumbo.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Jumbo de Perforación</h3>
                                        <div class="cotizador-card-tag"><span>Subterráneo</span></div>
                                        <p class="cotizador-card-description">Equipo de perforación múltiple para túneles y minería subterránea.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'cargador-subterraneo' }" @click="selectedCard = 'cargador-subterraneo'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/cargador-subterraneo.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Cargador Subterráneo</h3>
                                        <div class="cotizador-card-tag"><span>Subterráneo</span></div>
                                        <p class="cotizador-card-description">Cargador diseñado para operar en espacios confinados de minería subterránea.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Utilitarios Tab -->
                            <div class="cotizador-grid" style="display: none;">
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'generador' }" @click="selectedCard = 'generador'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/generador.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Generador Eléctrico</h3>
                                        <div class="cotizador-card-tag"><span>Utilitarios</span></div>
                                        <p class="cotizador-card-description">Generador de energía portátil para suministro eléctrico en obra.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'compresor' }" @click="selectedCard = 'compresor'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/compresor.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Compresor de Aire</h3>
                                        <div class="cotizador-card-tag"><span>Utilitarios</span></div>
                                        <p class="cotizador-card-description">Compresor portátil para herramientas neumáticas y aplicaciones industriales.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                                <div class="cotizador-card" :class="{ 'selected': selectedCard === 'bomba-agua' }" @click="selectedCard = 'bomba-agua'">
                                    <div class="cotizador-card-image" style="background-image: url('{{ asset('images/machinery/bomba.jpg') }}'); background-size: cover; background-position: center;"></div>
                                    <div class="cotizador-card-content">
                                        <h3 class="cotizador-card-title">Bomba de Agua</h3>
                                        <div class="cotizador-card-tag"><span>Utilitarios</span></div>
                                        <p class="cotizador-card-description">Bomba de alta capacidad para drenaje y manejo de agua en construcción.</p>
                                        <button class="cotizador-card-btn">Seleccionar máquina</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <x-footer-section />
        </div>
        
        <script>
            // Vanilla JavaScript for all functionality
            document.addEventListener('DOMContentLoaded', function() {
                console.log('✅ DOM loaded, initializing...');
                
                // ============ TAB FUNCTIONALITY ============
                const tabs = document.querySelectorAll('.cotizador-tab');
                const tabContents = document.querySelectorAll('.cotizador-grid');
                
                console.log('Found tabs:', tabs.length);
                console.log('Found tab contents:', tabContents.length);
                
                tabs.forEach((tab, index) => {
                    tab.addEventListener('click', function() {
                        console.log('✅ Tab clicked:', index);
                        
                        // Remove active class from all tabs
                        tabs.forEach(t => t.classList.remove('active'));
                        
                        // Add active class to clicked tab
                        this.classList.add('active');
                        
                        // Hide all tab contents
                        tabContents.forEach(content => {
                            content.style.display = 'none';
                        });
                        
                        // Show corresponding tab content
                        if (tabContents[index]) {
                            tabContents[index].style.display = 'grid';
                            console.log('✅ Showing content:', index);
                        }
                    });
                });
                
                // Show first tab by default
                if (tabContents[0]) {
                    tabContents[0].style.display = 'grid';
                }
                
                // ============ CARD SELECTION FUNCTIONALITY ============
                const cards = document.querySelectorAll('.cotizador-card');
                let selectedCard = null;
                
                cards.forEach(card => {
                    card.addEventListener('click', function() {
                        // Remove selected class from all cards
                        cards.forEach(c => c.classList.remove('selected'));
                        
                        // Add selected class to clicked card
                        this.classList.add('selected');
                        selectedCard = this;
                        
                        console.log('✅ Card selected');
                    });
                });
                
                // ============ FILTER DROPDOWN FUNCTIONALITY ============
                const filterGroups = document.querySelectorAll('.cotizador-filter-group');
                
                filterGroups.forEach(group => {
                    const button = group.querySelector('.cotizador-select');
                    const dropdown = group.querySelector('.cotizador-dropdown');
                    
                    if (button && dropdown) {
                        // Toggle dropdown on button click
                        button.addEventListener('click', function(e) {
                            e.stopPropagation();
                            
                            // Close other dropdowns
                            filterGroups.forEach(g => {
                                const otherDropdown = g.querySelector('.cotizador-dropdown');
                                if (otherDropdown && otherDropdown !== dropdown) {
                                    otherDropdown.style.display = 'none';
                                }
                            });
                            
                            // Toggle current dropdown
                            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
                        });
                        
                        // Handle dropdown item clicks
                        const items = dropdown.querySelectorAll('.cotizador-dropdown-item');
                        items.forEach(item => {
                            item.addEventListener('click', function(e) {
                                e.stopPropagation();
                                const text = this.textContent;
                                const selectText = button.querySelector('.cotizador-select-text');
                                if (selectText) {
                                    selectText.textContent = text;
                                }
                                dropdown.style.display = 'none';
                                console.log('✅ Filter selected:', text);
                            });
                        });
                    }
                });
                
                // Close dropdowns when clicking outside
                document.addEventListener('click', function() {
                    filterGroups.forEach(group => {
                        const dropdown = group.querySelector('.cotizador-dropdown');
                        if (dropdown) {
                            dropdown.style.display = 'none';
                        }
                    });
                });
                
                // ============ CLEAR FILTERS BUTTON ============
                const clearButton = document.querySelector('.cotizador-btn-secondary');
                if (clearButton) {
                    clearButton.addEventListener('click', function() {
                        const selectTexts = document.querySelectorAll('.cotizador-select-text');
                        selectTexts.forEach((text, index) => {
                            const placeholders = ['Seleccionar una marca', 'Seleccionar modelo de máquina', 'Serie de la máquina'];
                            text.textContent = placeholders[index] || 'Seleccionar';
                        });
                        console.log('✅ Filters cleared');
                    });
                }
            });
        </script>
    </body>
</html>
