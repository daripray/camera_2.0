<!-- resources/views/components/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Aplikasi Kamera FO/BO' }}</title>
    <!-- @ vite('resources/css/app.css') -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @livewireStyles
</head>
<body class="bg-light">
    <div class="container py-4">
        {{ $slot }}
    </div>

    @livewireScripts
    <!-- @ vite('resources/js/app.js') -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
