<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{config('app.name')}}</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        @vite('resources/css/app.css')
    </head>
    <body class="antialiased">
        <div id="app" class="relative items-top justify-center min-h-screen bg-gray-300  sm:items-center py-4 sm:pt-0">
        </div>
        @vite('resources/js/app.js')
    </body>
</html>
