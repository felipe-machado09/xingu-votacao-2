<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Plataforma de Votação')</title>
    @yield('meta')
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
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

    @yield('scripts')
</body>
</html>
