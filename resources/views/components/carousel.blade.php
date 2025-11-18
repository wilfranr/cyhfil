<div class="carousel-wrapper" id="carousel">
    <div class="carousel-container">
        <div class="carousel-slides">
            <div class="carousel-slide active" data-slide="0">
                <img src="{{ asset('images/carrusel1.svg') }}" alt="Carrusel 1" class="carousel-image">
            </div>
            <div class="carousel-slide" data-slide="1">
                <img src="{{ asset('images/carrusel2.svg') }}" alt="Carrusel 2" class="carousel-image">
            </div>
            <div class="carousel-slide" data-slide="2">
                <img src="{{ asset('images/carrusel3.svg') }}" alt="Carrusel 3" class="carousel-image">
            </div>
        </div>
        
        <div class="carousel-controls">
            <button class="carousel-arrow carousel-arrow-left" id="prevBtn">
                <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M32.5 13L19.5 26L32.5 39" stroke="rgba(247, 253, 242, 0.4)" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="carousel-arrow carousel-arrow-right" id="nextBtn">
                <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19.5 13L32.5 26L19.5 39" stroke="rgba(247, 253, 242, 0.4)" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        
        <div class="carousel-content">
            <div class="carousel-text-container">
                <h2 class="carousel-title carousel-title-0 active">Trabajamos con las marcas más prestigiosas del mercado</h2>
                <h2 class="carousel-title carousel-title-1">Te brindamos una plataforma de cotización moderna y fácil de usar</h2>
                <h2 class="carousel-title carousel-title-2">Asistencia continua, las 24 horas, para ayudarte con cualquier consulta</h2>
            </div>
            <div class="carousel-dots">
                <span class="carousel-dot active" data-dot="0"></span>
                <span class="carousel-dot" data-dot="1"></span>
                <span class="carousel-dot" data-dot="2"></span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.carousel-dot');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('active', i === index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        // Mostrar/ocultar textos según el slide activo
        document.querySelectorAll('.carousel-title').forEach((title, i) => {
            title.classList.toggle('active', i === index);
        });
        currentSlide = index;
    }

    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }

    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }

    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => showSlide(index));
    });

    // Auto-play cada 5 segundos
    setInterval(nextSlide, 5000);
});
</script>

