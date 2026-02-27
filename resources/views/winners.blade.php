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
    <title>Vencedores - Melhores do Ano {{ $year }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.webp') }}">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('winners') }}">
    <meta property="og:title" content="Vencedores - Melhores do Ano {{ $year }}">
    <meta property="og:description" content="Conheça os vencedores do Melhores do Ano {{ $year }}">
    <meta property="og:image" content="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Melhores do Ano - Vale do Xingu">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Vencedores - Melhores do Ano {{ $year }}">
    <meta name="twitter:description" content="Conheça os vencedores do Melhores do Ano {{ $year }}">
    <meta name="twitter:image" content="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<body class="bg-gradient-to-br from-yellow-50 via-white to-yellow-50 min-h-screen">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NMQC4WVT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Countdown -->
    <x-countdown />

    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Logomarca Melhores do Ano" class="h-10 sm:h-16">
                    </a>
                </div>
                <!-- Mobile menu button -->
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <nav class="hidden md:flex items-center space-x-6">
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
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium">Início</a>
                <a href="{{ route('vote.index') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium">Categorias</a>
                @if($audience)
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left py-2 text-gray-700 hover:text-red-600 font-medium">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium">Login</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-red-600 to-red-700 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center animate-fade-in">
                <img src="{{ asset('img/Logomarca Melhores do Ano Branca.webp') }}" alt="Logo" class="h-20 sm:h-32 mx-auto mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-4xl md:text-5xl font-extrabold mb-4">Melhores empresas de Altamira em {{ $year }}</h1>
                <p class="text-base sm:text-xl md:text-2xl max-w-4xl mx-auto leading-relaxed">
                    Após um processo de votação popular, transparente e gratuito, a população de Altamira escolheu as empresas que mais se destacaram em seus segmentos ao longo de {{ $year }}.
                </p>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="bg-white py-12 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 sm:gap-6 text-center">
                <div class="animate-slide-up">
                    <div class="text-2xl sm:text-4xl md:text-5xl font-bold text-red-600 mb-2">{{ number_format($totalVotes, 0, ',', '.') }}+</div>
                    <div class="text-gray-600 text-xs sm:text-sm md:text-base">votos auditados</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="text-2xl sm:text-4xl md:text-5xl font-bold text-red-600 mb-2">{{ $totalCompanies }}+</div>
                    <div class="text-gray-600 text-xs sm:text-sm md:text-base">empresas participantes</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="text-2xl sm:text-4xl md:text-5xl font-bold text-red-600 mb-2">{{ $totalCategories }}+</div>
                    <div class="text-gray-600 text-xs sm:text-sm md:text-base">segmentos votados</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="text-4xl md:text-5xl font-bold text-red-600 mb-2">
                        <svg class="w-12 h-12 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-gray-600 text-sm md:text-base">Plataforma própria</div>
                </div>
                <div class="animate-slide-up" style="animation-delay: 0.4s;">
                    <div class="text-4xl md:text-5xl font-bold text-red-600 mb-2">
                        <svg class="w-12 h-12 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="text-gray-600 text-sm md:text-base">Gratuito e aberto</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="bg-gray-50 py-8 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Empresas vencedoras por categoria</h2>

            <form method="GET" action="{{ route('winners') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Year Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Edição (ano)</label>
                        <select name="year" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            @foreach($availableYears as $availableYear)
                                <option value="{{ $availableYear }}" {{ $year == $availableYear ? 'selected' : '' }}>
                                    {{ $availableYear }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                        <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                            <option value="">Todas as categorias</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Search Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesquisar empresa</label>
                        <input type="text"
                               name="search"
                               value="{{ $search }}"
                               placeholder="Digite o nome da empresa..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <button type="submit" class="w-full sm:w-auto bg-red-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-red-700 transition-colors">
                        🔍 Buscar
                    </button>

                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm text-gray-600 mr-1 sm:mr-3">Acesso rápido:</span>
                        @foreach($availableYears->take(3) as $quickYear)
                            <a href="{{ route('winners', ['year' => $quickYear]) }}"
                               class="px-4 py-2 {{ $year == $quickYear ? 'bg-red-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} border border-gray-300 rounded-lg font-semibold transition-colors">
                                {{ $quickYear }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Winners Grid -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($winners->isEmpty())
            <div class="text-center py-20 animate-fade-in">
                <svg class="w-32 h-32 mx-auto text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600 text-2xl font-semibold mb-2">Nenhum vencedor encontrado</p>
                <p class="text-gray-500 text-lg">Tente ajustar os filtros de busca</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                @foreach($categoriesWithWinners as $index => $categoryWinner)
                    <div class="bg-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden animate-slide-up" style="animation-delay: {{ $index * 0.05 }}s;">
                        <!-- Category Header -->
                        <div class="bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-4">
                            <h3 class="text-lg font-bold text-center">{{ $categoryWinner->category->name }}</h3>
                        </div>

                        <!-- Winner Content -->
                        <div class="p-6">
                            <!-- Trophy Icon -->
                            <div class="flex justify-center mb-4">
                                <div class="bg-yellow-100 rounded-full p-4">
                                    <svg class="w-12 h-12 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Logo -->
                            @if($categoryWinner->company->logo_path)
                                <div class="h-32 flex items-center justify-center mb-4 bg-gray-50 rounded-lg p-4">
                                    <img src="{{ asset('storage/' . $categoryWinner->company->logo_path) }}"
                                         alt="{{ $categoryWinner->company->legal_name }}"
                                         class="max-h-full max-w-full object-contain">
                                </div>
                            @endif

                            <!-- Company Name -->
                            <h4 class="text-xl font-bold text-gray-900 text-center mb-2">
                                {{ $categoryWinner->company->legal_name }}
                            </h4>

                            @if($categoryWinner->company->responsible_name)
                                <p class="text-sm text-gray-600 text-center mb-3">
                                    {{ $categoryWinner->company->responsible_name }}
                                </p>
                            @endif

                            <!-- Votes Count -->
                            @if($categoryWinner->votes_count)
                                <div class="flex items-center justify-center text-red-600 mb-3">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-bold">{{ number_format($categoryWinner->votes_count, 0, ',', '.') }}</span>
                                    <span class="ml-1">votos</span>
                                </div>
                            @endif

                            <!-- Winner Badge -->
                            <div class="text-center">
                                <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-bold">
                                    🏆 VENCEDOR {{ $year }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <!-- Trophy Ceremony Section -->
    <section class="bg-gradient-to-r from-gray-900 to-gray-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Entrega dos troféus</h2>
                <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto">
                    A entrega dos troféus às empresas vencedoras do Melhores do Ano {{ $year }} foi realizada ao vivo no SBT Altamira, com cobertura jornalística.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 hover:bg-white/20 transition">
                    <div class="aspect-video bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-center mt-3 text-sm">Em breve</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 hover:bg-white/20 transition">
                    <div class="aspect-video bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-center mt-3 text-sm">Em breve</p>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 hover:bg-white/20 transition">
                    <div class="aspect-video bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-center mt-3 text-sm">Em breve</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Sponsors Section -->
    @if($sponsors && $sponsors->count() > 0)
    <section class="bg-white py-16 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Patrocinadores</h2>
                <p class="text-lg text-gray-600">Obrigado pelo apoio!</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 items-center">
                @foreach($sponsors as $sponsor)
                    <div class="flex items-center justify-center p-6 bg-gray-50 rounded-lg hover:shadow-lg transition">
                        @if($sponsor->logo_path)
                            @if($sponsor->website)
                                <a href="{{ $sponsor->website }}" target="_blank" rel="noopener" class="block">
                                    <img src="{{ asset('storage/' . $sponsor->logo_path) }}"
                                         alt="{{ $sponsor->name }}"
                                         class="max-h-20 max-w-full object-contain grayscale hover:grayscale-0 transition">
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $sponsor->logo_path) }}"
                                     alt="{{ $sponsor->name }}"
                                     class="max-h-20 max-w-full object-contain grayscale hover:grayscale-0 transition">
                            @endif
                        @else
                            <div class="text-xl font-bold text-gray-400">{{ $sponsor->name }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Melhores do Ano {{ $year }}</h3>
                    <p class="text-gray-400 text-sm">
                        Votação popular, transparente e gratuita das melhores empresas de Altamira.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Links Rápidos</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Início</a></li>
                        <li><a href="{{ route('vote.index') }}" class="hover:text-white transition">Votação</a></li>
                        <li><a href="{{ route('winners') }}" class="hover:text-white transition">Vencedores</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Contato</h3>
                    <p class="text-gray-400 text-sm">
                        Altamira - PA<br>
                        Vale do Xingu
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ $year }} Melhores do Ano - Vale do Xingu. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        function shareWinners() {
            const url = '{{ route('winners') }}';
            const title = 'Vencedores - Melhores do Ano {{ $year }}';
            const text = 'Confira os vencedores do Melhores do Ano {{ $year }}!';

            if (navigator.share) {
                navigator.share({ title: title, text: text, url: url });
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Link copiado!',
                        text: 'O link dos vencedores foi copiado para a área de transferência.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc2626',
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }).catch(() => {
                    const textarea = document.createElement('textarea');
                    textarea.value = url;
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textarea);
                    Swal.fire({
                        icon: 'success',
                        title: 'Link copiado!',
                        text: 'O link dos vencedores foi copiado para a área de transferência.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc2626',
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
