<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Human Detector' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @livewireStyles

    {{-- Tambahkan ini agar @push('styles') muncul --}}
    @stack('styles')
</head>
<body class="bg-light">
    <main>
        {{ $slot }}
    </main>

    @livewireScripts
    @stack('scripts')@stack('scripts')

<!-- Tambahkan ini jika belum ada -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
