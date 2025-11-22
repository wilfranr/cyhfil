<div class="navbar-wrapper">
    <div class="navbar-content">
        <div class="navbar-logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="HEAVYMarket.NET" class="logo-image">
            </a>
        </div>
        <div class="navbar-frame">
            <div class="navbar-column">
                <div class="navbar-link-frame split-btn-wrapper">
                    <a href="{{ route('productos') }}" class="split-btn__link">
                        <span class="link-text">Nuestros productos</span>
                    </a>
                    <button class="split-btn__trigger" type="button" onclick="toggleProductsMenu(this)">
                        <span class="arrow">▼</span>
                    </button>
                    <!-- Menú desplegable -->
                    <div class="dropdown-menu">
                        <!-- Frame 6 - Contenido principal -->
                        <div class="dropdown-main-content">
                            <!-- Menu - Sidebar izquierdo -->
                            <div class="dropdown-menu-sidebar">
                                <div class="dropdown-menu-inner">
                                    <h3 class="dropdown-menu-title">Blog categories</h3>
                                    <ul class="dropdown-menu-list">
                                        <li class="dropdown-menu-item active" data-category="motores">
                                            <a href="#" class="dropdown-menu-link" data-target="motores">Motores</a>
                                        </li>
                                        <li class="dropdown-menu-item" data-category="trenes-rodaje">
                                            <a href="#" class="dropdown-menu-link" data-target="trenes-rodaje">Trenes de rodaje</a>
                                        </li>
                                        <li class="dropdown-menu-item" data-category="hidraulicos">
                                            <a href="#" class="dropdown-menu-link" data-target="hidraulicos">Hidráulicos</a>
                                        </li>
                                        <li class="dropdown-menu-item" data-category="transmisiones">
                                            <a href="#" class="dropdown-menu-link" data-target="transmisiones">Transmisiones</a>
                                        </li>
                                        <li class="dropdown-menu-item" data-category="electronicos">
                                            <a href="#" class="dropdown-menu-link" data-target="electronicos">Electrónicos</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Frame 10 - Contenido de artículos -->
                            <div class="dropdown-articles-wrapper">
                                <!-- Contenido para Motores -->
                                <div class="dropdown-articles-content category-content" data-category="motores">
                                    <div class="dropdown-articles-inner">
                                        <div class="dropdown-articles-header">
                                            <h4 class="dropdown-articles-title">Motores</h4>
                                        </div>
                                        <div class="dropdown-articles-grid">
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/piston.png') }}" alt="Pistones">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Pistones</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/camisa.png') }}" alt="Camisas de cilindro">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Camisas de cilindro</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/anillo.png') }}" alt="Anillo de pistón">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Anillo de pistón</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/casquete.png') }}" alt="Casquetas de bielas y bancada">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Casquetas de bielas y bancada</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contenido para Trenes de rodaje -->
                                <div class="dropdown-articles-content category-content" data-category="trenes-rodaje" style="display: none;">
                                    <div class="dropdown-articles-inner">
                                        <div class="dropdown-articles-header">
                                            <h4 class="dropdown-articles-title">Trenes de rodaje</h4>
                                        </div>
                                        <div class="dropdown-articles-grid">
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/cadena.png') }}" alt="Orugas">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Orugas</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/rueda-guia.png') }}" alt="Ruedas guía">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Ruedas guía</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/rodillo-inferior.png') }}" alt="Rodillos de apoyo">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Rodillos de apoyo</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/rodillo-superior.png') }}" alt="Ruedas tensoras">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Ruedas tensoras</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contenido para Hidráulicos -->
                                <div class="dropdown-articles-content category-content" data-category="hidraulicos" style="display: none;">
                                    <div class="dropdown-articles-inner">
                                        <div class="dropdown-articles-header">
                                            <h4 class="dropdown-articles-title">Hidráulicos</h4>
                                        </div>
                                        <div class="dropdown-articles-grid">
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/grupos-rotativos.png') }}" alt="Bombas hidráulicas">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Bombas hidráulicas</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/spools.png') }}" alt="Cilindros hidráulicos">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Cilindros hidráulicos</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/valvulas.png') }}" alt="Válvulas hidráulicas">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Válvulas hidráulicas</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <img src="{{ asset('images/empaquetaduras.png') }}" alt="Mangueras y conexiones">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Mangueras y conexiones</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contenido para Transmisiones -->
                                <div class="dropdown-articles-content category-content" data-category="transmisiones" style="display: none;">
                                    <div class="dropdown-articles-inner">
                                        <div class="dropdown-articles-header">
                                            <h4 class="dropdown-articles-title">Transmisiones</h4>
                                        </div>
                                        <div class="dropdown-articles-grid">
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <!-- <img src="{{ asset('images/convertidores-par.png') }}" alt="Convertidores de par"> -->
                                                        <img src="{{ asset('images/no-image.png') }}" alt="Convertidores de par">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Convertidores de par</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <!-- <img src="{{ asset('images/cajas-cambios.png') }}" alt="Cajas de cambios"> -->
                                                        <img src="{{ asset('images/no-image.png') }}" alt="Cajas de cambios">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Cajas de cambios</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <!-- <img src="{{ asset('images/diferenciales.png') }}" alt="Diferenciales"> -->
                                                        <img src="{{ asset('images/no-image.png') }}" alt="Diferenciales">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Diferenciales</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <!-- <img src="{{ asset('images/embragues.png') }}" alt="Embragues"> -->
                                                        <img src="{{ asset('images/no-image.png') }}" alt="Embragues">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Embragues</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contenido para Electrónicos -->
                                <div class="dropdown-articles-content category-content" data-category="electronicos" style="display: none;">
                                    <div class="dropdown-articles-inner">
                                        <div class="dropdown-articles-header">
                                            <h4 class="dropdown-articles-title">Electrónicos</h4>
                                        </div>
                                        <div class="dropdown-articles-grid">
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <!-- <img src="{{ asset('images/sensores.png') }}" alt="Sensores"> -->
                                                        <img src="{{ asset('images/no-image.png') }}" alt="Sensores">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Sensores</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <!-- <img src="{{ asset('images/modulos-control.png') }}" alt="Módulos de control"> -->
                                                        <img src="{{ asset('images/no-image.png') }}" alt="Módulos de control">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Módulos de control</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-articles-column">
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <!-- <img src="{{ asset('images/cableado-conectores.png') }}" alt="Cableado y conectores"> -->
                                                        <img src="{{ asset('images/no-image.png') }}" alt="Cableado y conectores">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Cableado y conectores</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="dropdown-blog-item">
                                                    <div class="dropdown-blog-image">
                                                        <!-- <img src="{{ asset('images/displays-monitores.png') }}" alt="Displays y monitores"> -->
                                                        <img src="{{ asset('images/no-image.png') }}" alt="Displays y monitores">
                                                    </div>
                                                    <div class="dropdown-blog-content">
                                                        <div class="dropdown-blog-text">
                                                            <h5 class="dropdown-blog-title">Displays y monitores</h5>
                                                            <p class="dropdown-blog-description">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
                                                        </div>
                                                        <button class="dropdown-blog-button">
                                                            <span class="dropdown-button-text">Saber más</span>
                                                            <svg class="dropdown-arrow-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                                <path d="M6 3L11 8L6 13" stroke="#0C0E0F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                <button class="navbar-button">
                    <span class="button-text">WhatsApp</span>
                </button>
            </div>
        </div>
    </div>
    <x-banner />
</div>

<script>
// Función para toggle del menú de productos
function toggleProductsMenu(button) {
    const wrapper = button.closest('.split-btn-wrapper');
    const menu = wrapper.querySelector('.dropdown-menu');
    const isActive = menu.classList.contains('active');
    
    // Cerrar todos los demás menús
    document.querySelectorAll('.dropdown-menu.active').forEach(m => {
        if (m !== menu) {
            m.classList.remove('active');
            m.closest('.split-btn-wrapper')?.classList.remove('menu-active');
        }
    });
    
    // Toggle del menú actual
    menu.classList.toggle('active');
    wrapper.classList.toggle('menu-active', menu.classList.contains('active'));
}

// Prevenir que el enlace despliegue el menú
document.addEventListener('DOMContentLoaded', function() {
    const productosLink = document.querySelector('.split-btn__link');
    if (productosLink) {
        productosLink.addEventListener('click', function(e) {
            // Cerrar el menú si está abierto
            const wrapper = this.closest('.split-btn-wrapper');
            const menu = wrapper.querySelector('.dropdown-menu');
            if (menu) {
                menu.classList.remove('active');
                wrapper.classList.remove('menu-active');
            }
            // Permitir que el enlace funcione normalmente (redirigir)
            // No prevenir el comportamiento por defecto
        });
    }
});

// Cerrar menú si haces clic fuera
document.addEventListener('click', function(event) {
    const isClickInsideMenu = event.target.closest('.dropdown-menu');
    const isClickOnTrigger = event.target.closest('.split-btn__trigger') || event.target.matches('.arrow');
    const isClickOnLink = event.target.closest('.split-btn__link');
    
    // Si el clic no está dentro del menú o en el trigger, cerrar todos los menús
    // (el enlace se maneja por separado arriba)
    if (!isClickInsideMenu && !isClickOnTrigger) {
        document.querySelectorAll('.dropdown-menu.active').forEach(menu => {
            menu.classList.remove('active');
            menu.closest('.split-btn-wrapper')?.classList.remove('menu-active');
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
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

