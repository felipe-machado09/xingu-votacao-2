<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} - Melhores do Ano 2025</title>
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('vote.show', $category) }}">
    <meta property="og:title" content="{{ $category->name }} - Vote na sua empresa favorita">
    <meta property="og:description" content="{{ $category->description ?? 'Vote na sua empresa favorita nesta categoria' }}">
    <meta property="og:image" content="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $category->name }} - Vote na sua empresa favorita">
    <meta name="twitter:description" content="{{ $category->description ?? 'Vote na sua empresa favorita nesta categoria' }}">
    
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
                    <a href="{{ route('vote.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Categorias</a>
                    @if($audience)
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 font-medium">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 font-medium">Cadastrar-se</a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Category Header -->
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">{{ $category->name }}</h1>
            @if($category->description)
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $category->description }}</p>
            @endif
        </div>

        @if(session('success'))
            <div class="max-w-3xl mx-auto mb-8 bg-green-50 border-l-4 border-green-400 p-6 rounded-lg animate-slide-up">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-800 font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($category->winner)
            <div class="max-w-3xl mx-auto mb-8 bg-gradient-to-r from-yellow-400 to-yellow-500 text-white p-6 rounded-xl shadow-lg animate-slide-up">
                <div class="flex items-center justify-center">
                    <svg class="w-8 h-8 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <div>
                        <h3 class="text-xl font-bold mb-1">🏆 Vencedor</h3>
                        <p class="text-lg">{{ $category->winner->company->legal_name }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($userVote)
            <div class="max-w-3xl mx-auto mb-8 bg-green-50 border-l-4 border-green-400 p-6 rounded-lg animate-slide-up">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-green-900 mb-1">✓ Você votou em</h3>
                        <p class="text-green-800">{{ $userVote->company->legal_name }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(!$audience)
            <div class="max-w-3xl mx-auto mb-8 bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg animate-slide-up">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-400 mr-3 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Cadastre-se para votar</h3>
                        <p class="text-blue-800 mb-4">Você precisa estar logado para votar nesta categoria.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('register') }}" class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition font-semibold">
                                Cadastrar
                            </a>
                            <a href="{{ route('login') }}" class="inline-block bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition font-semibold">
                                Entrar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if($category->companies->isEmpty())
            <div class="text-center py-20 animate-fade-in">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H9m10 0v-5.372a2.25 2.25 0 00-.488-1.398l-4.5-5.25a2.25 2.25 0 00-1.024-.786H10.5a2.25 2.25 0 00-2.25 2.25v5.372"></path>
                </svg>
                <p class="text-gray-600 text-xl">Nenhuma empresa nesta categoria ainda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($category->companies as $index => $company)
                    <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 overflow-hidden @if($highlightCompanyId == $company->id) ring-4 ring-red-500 @endif animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s">
                        <a href="{{ route('vote.company', $company) }}" class="block">
                            @if($company->logo_path)
                                <div class="h-48 bg-gray-50 flex items-center justify-center p-4">
                                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->legal_name }}" class="max-h-full max-w-full object-contain">
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-6xl font-bold">{{ substr($company->legal_name, 0, 1) }}</span>
                                </div>
                            @endif
                        </a>
                        
                        <div class="p-6">
                            <a href="{{ route('vote.company', $company) }}" class="block mb-2">
                                <h3 class="text-xl font-bold text-gray-900 hover:text-red-600 transition">{{ $company->legal_name }}</h3>
                            </a>
                            
                            @if($company->responsible_name)
                                <p class="text-sm text-gray-600 mb-1">{{ $company->responsible_name }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between mt-4">
                                <a href="{{ route('vote.company', $company) }}" class="text-sm text-red-600 hover:text-red-700 font-semibold flex items-center">
                                    Ver página
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                                
                                @if($audience && !$userVote && $category->isOpen())
                                    <form method="POST" action="{{ route('vote.store', [$category, $company]) }}" class="inline" onclick="event.stopPropagation();">
                                        @csrf
                                        <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-2 rounded-lg text-sm font-bold hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105">
                                            Votar
                                        </button>
                                    </form>
                                @elseif($userVote && $userVote->company_id == $company->id)
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-lg text-sm font-semibold flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Seu voto
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Share Section -->
            <div class="text-center mb-8">
                <button onclick="shareCategory()" class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-4 rounded-xl font-bold transition-all transform hover:scale-105 shadow-lg flex items-center mx-auto">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                    Compartilhar categoria
                </button>
            </div>
        @endif
    </main>

    <script>
        function shareCategory() {
            const url = '{{ route('vote.show', $category) }}';
            const title = '{{ $category->name }}';
            const text = 'Vote em {{ $category->name }} - Melhores do Ano 2025';
            
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: text,
                    url: url
                });
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    alert('Link copiado para a área de transferência!');
                }).catch(() => {
                    const textarea = document.createElement('textarea');
                    textarea.value = url;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    alert('Link copiado para a área de transferência!');
                });
            }
        }
    </script>
</body>
</html>
