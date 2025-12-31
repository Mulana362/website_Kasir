<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Toko Serba-Serbi Banten</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    {{-- Custom Styles --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* Styling dasar khusus tombol brand & brand text */
        body {
            font-family: "Inter", "Poppins", sans-serif;
        }

        .navbar-brand .brand-primary {
            color: #FFA726;
            font-weight: bold;
        }

        .btn-brand {
            background: #FFA726;
            border: none;
        }
        .btn-brand:hover {
            background: #fb8c00;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    {{-- NAVBAR --}}
    @include('partials.navbar')

    {{-- MAIN CONTENT --}}
    <main class="flex-fill">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
