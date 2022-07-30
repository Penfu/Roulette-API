<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>

<body class="min-h-screen bg-gray-50">
    <div class="flex h-full">
        <div class="flex flex-col flex-1 w-full h-full">
            <header class="z-10 bg-white shadow-md">
            </header>

            <!-- Content -->
            <div class="flex h-full text-gray-800">
                <main class="h-full w-full m-8 overflow-x-hidden">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>

</html>
