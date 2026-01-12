<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Melhores do Ano 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-red-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Logomarca Melhores do Ano" class="h-16">
                    </a>
                </div>
                <nav class="flex items-center space-x-6">
                    @if($audience)
                        <a href="{{ route('vote.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Votar</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 font-medium">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-red-600 font-medium">Início</a>
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 font-medium">Cadastrar-se</a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Vote nas Categorias</h1>
            <p class="text-xl text-gray-600">Escolha uma categoria e vote na sua empresa favorita</p>
        </div>

        @if(!$audience)
            <div class="max-w-2xl mx-auto mb-8 bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg animate-slide-up">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-yellow-800 font-semibold">Você precisa estar logado para votar</p>
                        <p class="text-yellow-700 text-sm mt-1">
                            <a href="{{ route('register') }}" class="font-semibold underline hover:text-yellow-900">Cadastre-se</a> ou 
                            <a href="{{ route('login') }}" class="font-semibold underline hover:text-yellow-900">Entre</a> para continuar.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($categories->isEmpty())
            <div class="text-center py-20 animate-fade-in">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-600 text-xl">Nenhuma categoria disponível para votação no momento.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $index => $category)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-2xl font-bold text-gray-900 flex-1">{{ $category->name }}</h3>
                            @if(in_array($category->id, $votedCategoryIds))
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Votado
                                </span>
                            @endif
                        </div>
                        
                        @if($category->description)
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ $category->description }}</p>
                        @endif
                        
                        <div class="flex items-center text-sm text-gray-500 mb-6">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H9m10 0v-5.372a2.25 2.25 0 00-.488-1.398l-4.5-5.25a2.25 2.25 0 00-1.024-.786H10.5a2.25 2.25 0 00-2.25 2.25v5.372"></path>
                            </svg>
                            <span class="font-semibold">{{ $category->companies->count() }}</span>
                            <span class="ml-1">empresas participantes</span>
                        </div>
                        
                        @if(in_array($category->id, $votedCategoryIds))
                            <a href="{{ route('vote.show', $category) }}" class="block w-full bg-gray-100 text-gray-700 px-6 py-3 rounded-xl text-center font-semibold hover:bg-gray-200 transition">
                                Ver Resultado
                            </a>
                        @else
                            <a href="{{ route('vote.show', $category) }}" class="block w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl text-center font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                Votar Agora
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </main>
</body>
</html>
