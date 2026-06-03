<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Handman - Sistem Management Tugas Kantor')</title>
    <link rel="shortcut icon" href="{{ asset('assets/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased" x-data="{ sidebarOpen: false }">

    @auth
        <div class="flex min-h-screen bg-gray-50">
            @include('components.sidebar')

            <div class="flex-1 flex flex-col min-w-0">
                @include('components.topbar')

                <main class="flex-1 px-4 sm:px-6 mt-4 overflow-x-hidden md:ml-64">
                    @yield('content')
                </main>
            </div>
        </div>
    @endauth

    @guest
        @yield('content')
    @endguest

</body>
</html>
