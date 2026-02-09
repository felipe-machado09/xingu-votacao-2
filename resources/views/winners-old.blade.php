<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vencedores - Melhores do Ano 2025</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('winners') }}">
    <meta property="og:title" content="Vencedores - Melhores do Ano 2025">
    <meta property="og:description" content="Conheça os vencedores do Melhores do Ano 2025">
    <meta property="og:image" content="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}">

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
        @keyframes sparkle {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
        .animate-sparkle {
            animation: sparkle 2s ease-in-out infinite;
        }
        .winner-card {
            position: relative;
            background: linear-gradient(135deg, #fff 0%, #fef3c7 100%);
        }
        .winner-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            border-radius: 1rem;
            z-index: -1;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-yellow-50 via-white to-yellow-50 min-h-screen">
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
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-red-600 font-medium">Início</a>
                    <a href="{{ route('vote.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Categorias</a>
                    @if($audience)
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 font-medium">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium">Login</a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16 animate-fade-in">
            <div class="inline-block mb-6">
                <span class="text-8xl animate-sparkle inline-block">🏆</span>
            </div>
            <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-4">
                Vencedores 2025
            </h1>
            <p class="text-xl md:text-2xl text-gray-600 max-w-3xl mx-auto">
                Conheça os melhores do ano escolhidos por você!
            </p>
        </div>

        @if($winners->isEmpty())
            <div class="text-center py-20 animate-fade-in">
                <svg class="w-32 h-32 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 text-2xl font-semibold mb-2">Votação ainda em andamento...</p>
                <p class="text-gray-500 text-lg">Os vencedores serão anunciados em breve!</p>
                <div class="mt-8">
                    <a href="{{ route('vote.index') }}" class="inline-block bg-red-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-red-700 transition-all transform hover:scale-105 shadow-lg">
                        Ir para Votação
                    </a>
                </div>
            </div>
        @else
            <!-- Winners Grid -->
            <div class="space-y-12">
                @foreach($categoriesWithWinners as $index => $categoryWinner)
                    <div class="animate-slide-up" style="animation-delay: {{ $index * 0.1 }}s">
                        <!-- Category Header -->
                        <div class="text-center mb-8">
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">
                                {{ $categoryWinner->category->name }}
                            </h2>
                            @if($categoryWinner->category->description)
                                <p class="text-gray-600 text-lg">{{ $categoryWinner->category->description }}</p>
                            @endif
                        </div>

                        <!-- Winner Card -->
                        <div class="winner-card rounded-2xl overflow-hidden shadow-2xl max-w-4xl mx-auto">
                            <div class="grid md:grid-cols-2 gap-0">
                                <!-- Logo/Image Section -->
                                <div class="bg-gradient-to-br from-yellow-100 to-orange-100 p-12 flex items-center justify-center">
                                    @if($categoryWinner->company->logo_path)
                                        <img src="{{ asset('storage/' . $categoryWinner->company->logo_path) }}"
                                             alt="{{ $categoryWinner->company->legal_name }}"
                                             class="max-h-64 max-w-full object-contain">
                                    @else
                                        <div class="text-9xl font-bold text-yellow-600">
                                            {{ substr($categoryWinner->company->legal_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Info Section -->
                                <div class="p-8 md:p-12 flex flex-col justify-center bg-white/80 backdrop-blur-sm">
                                    <div class="mb-4">
                                        <span class="inline-flex items-center bg-yellow-500 text-white px-4 py-2 rounded-full text-sm font-bold">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                            🏆 VENCEDOR
                                        </span>
                                    </div>

                                    <h3 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3">
                                        {{ $categoryWinner->company->legal_name }}
                                    </h3>

                                    @if($categoryWinner->company->responsible_name)
                                        <p class="text-lg text-gray-700 mb-4">
                                            <span class="font-semibold">Responsável:</span> {{ $categoryWinner->company->responsible_name }}
                                        </p>
                                    @endif

                                    @if($categoryWinner->votes_count)
                                        <div class="flex items-center text-gray-600 mb-6">
                                            <svg class="w-6 h-6 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-2xl font-bold text-red-600">{{ number_format($categoryWinner->votes_count, 0, ',', '.') }}</span>
                                            <span class="ml-2 text-lg">votos</span>
                                        </div>
                                    @endif

                                    <div class="mt-4 pt-4 border-t border-yellow-200">
                                        <p class="text-sm text-gray-500 italic">
                                            ⭐ Escolhido pelo público em {{ now()->year }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Share Section -->
            <div class="text-center mt-16">
                <button onclick="shareWinners()" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-10 py-5 rounded-xl font-bold text-lg transition-all transform hover:scale-105 shadow-2xl flex items-center mx-auto">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                    Compartilhar Vencedores
                </button>
            </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-lg mb-2">Melhores do Ano 2025</p>
            <p class="text-gray-400">Obrigado por participar!</p>
        </div>
    </footer>

    <script>
        function shareWinners() {
            const url = '{{ route('winners') }}';
            const title = 'Vencedores - Melhores do Ano 2025';
            const text = 'Confira os vencedores do Melhores do Ano 2025!';

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
