<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

        <!-- Styles -->
        <style>
          @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&family=Righteous&display=swap');
        </style>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

        <link href="{{ asset('/css/bulma.css') }}" rel="stylesheet" />
        {{-- <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
        <script src="{{ mix('/js/app.js') }}" defer></script> --}}
        @vite(['resources/css/app.css', 'resources/inertia/app.js'])
        @routes
        @inertiaHead
    </head>
    <body>
       @inertia
    </body>
</html>
