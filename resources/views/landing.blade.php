<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Landing Page</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cotizador.css') }}">
    </head>
    <body>
        <div class="page-wrapper">
            <x-navbar />
            <x-carousel />
            <x-brands-section />
            <x-quote-section />
            <x-steps-section />
            <x-systems-section />
            <x-footer-section />
        </div>
    </body>
</html>

