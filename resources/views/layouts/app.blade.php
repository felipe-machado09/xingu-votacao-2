<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-NMQC4WVT');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Plataforma de Votação')</title>
    <link rel="icon" type="image/webp" href="{{ asset('img/logo_icon.webp') }}">
    <link rel="icon" type="image/webp" href="{{ asset('img/logo.webp') }}">
    <link rel="shortcut icon" type="image/webp" href="{{ asset('img/logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.webp') }}">
    @stack('head')
    @yield('meta')
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NMQC4WVT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @yield('countdown')

    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="text-xl font-bold text-gray-900">Plataforma de Votação</a>
                </div>
                <div class="flex items-center space-x-4">
                    @if(session('audience_id'))
                        <span class="text-gray-700">{{ session('audience_name') }}</span>
                        <a href="{{ route('vote.index') }}" class="text-blue-600 hover:text-blue-800">Votar</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-800">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Cadastrar</a>
                        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Entrar</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="py-8">
        @yield('content')
    </main>

    <footer class="bg-white mt-12 py-6 border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} Plataforma de Votação. Todos os direitos reservados.</p>
        </div>
    </footer>

    @stack('scripts')
    @yield('scripts')
</body>
</html>
