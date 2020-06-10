<html lang="{{ str_replace('_', '-', config('app.locale', 'en-US')) }}">
    <head>
        <title>{{ $title ?? config('app.name', 'NewTumblr') }}</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;800&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="bg-blue-800 text-white">
            <div class="container mx-auto min-h-screen flex flex-col">
                <x-header />
                @yield('body')
            </div>
        </div>

        <script src="{{ mix('js/app.js') }}"></script>
    </body>
</html>
