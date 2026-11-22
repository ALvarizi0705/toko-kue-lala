<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Nota') - Toko Kue Lala</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        body { font-family: 'Poppins', sans-serif; font-size: 12px; color: #333; background: #fff; }
        @media screen { .nota-container { max-width: 700px; margin: 2rem auto; padding: 1rem; } }
    </style>
</head>
<body>
    <div class="nota-container">
        @yield('content')
    </div>
    <script>
        window.addEventListener('load', () => {
            // Auto-print jika ada parameter ?print=1
            if (new URLSearchParams(window.location.search).get('print') === '1') {
                window.print();
            }
        });
    </script>
</body>
</html>
