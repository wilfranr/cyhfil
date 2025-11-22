<div x-data="{ open: false, activeTab: 'construccion' }"
     x-on:open-cotizador.window="open = true"
     class="cotizador-backdrop"
     :class="{ 'active': open }"
     x-show="open"
     x-transition.opacity
     style="display: none;" 
     x-init="$watch('open', value => { if(value) { $el.style.display = 'flex' } else { setTimeout(() => $el.style.display = 'none', 300) } })">

    <div class="cotizador-container">
        <!-- Sidebar -->
        <div class="cotizador-sidebar">
            <div class="cotizador-sidebar-header">
                <p class="cotizador-sidebar-text">Selecciona una categoría, luego elige marca, tipo y modelo de la máquina para continuar.</p>
            </div>

            <div class="cotizador-filters">
                <!-- Marca -->
                <div class="cotizador-filter-group">
                    <button class="cotizador-select">
                        <span class="cotizador-select-text">Seleccionar una marca</span>
                        <div class="cotizador-select-icon">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </button>
                </div>

                <!-- Modelo -->
                <div class="cotizador-filter-group">
                    <button class="cotizador-select">
                        <span class="cotizador-select-text">Seleccionar modelo de máquina</span>
                        <div class="cotizador-select-icon">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </button>
                </div>

                <!-- Serie -->
                <div class="cotizador-filter-group">
                    <button class="cotizador-select">
                        <span class="cotizador-select-text">Serie de la máquina</span>
                    </button>
                </div>
            </div>

            <div class="cotizador-actions">
                <button class="cotizador-btn-secondary" @click="open = false">
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
                <div class="cotizador-tab" :class="{ 'active': activeTab === 'construccion' }" @click="activeTab = 'construccion'">
                    <span class="cotizador-tab-text">Construccion</span>
                </div>
                <div class="cotizador-tab" :class="{ 'active': activeTab === 'equipo-ligero' }" @click="activeTab = 'equipo-ligero'">
                    <span class="cotizador-tab-text">Equipo Ligero</span>
                </div>
                <div class="cotizador-tab" :class="{ 'active': activeTab === 'mineria' }" @click="activeTab = 'mineria'">
                    <span class="cotizador-tab-text">Minería</span>
                </div>
                <div class="cotizador-tab" :class="{ 'active': activeTab === 'pavimentacion' }" @click="activeTab = 'pavimentacion'">
                    <span class="cotizador-tab-text">Pavimentación</span>
                </div>
                <div class="cotizador-tab" :class="{ 'active': activeTab === 'subterraneo' }" @click="activeTab = 'subterraneo'">
                    <span class="cotizador-tab-text">Subterráneo</span>
                </div>
                <div class="cotizador-tab" :class="{ 'active': activeTab === 'utilitarios' }" @click="activeTab = 'utilitarios'">
                    <span class="cotizador-tab-text">Utilitarios</span>
                </div>
            </div>

            <!-- Grid -->
            <div class="cotizador-grid-container">
                <div class="cotizador-grid" x-show="activeTab === 'construccion'">
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
                    <div class="cotizador-card">
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
                    <div class="cotizador-card">
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
                    <div class="cotizador-card">
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
                    <div class="cotizador-card">
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
                    <div class="cotizador-card">
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
                
                <!-- Placeholder for other tabs -->
                <div class="cotizador-grid" x-show="activeTab !== 'construccion'">
                    <div style="width: 100%; text-align: center; color: white; padding: 50px;">
                        <p>Contenido para la categoría seleccionada próximamente.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
