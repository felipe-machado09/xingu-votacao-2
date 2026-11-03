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
    <title>Categorias - Melhores do Ano {{ \App\Models\LandingPageSection::campaignYear() }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">
    <link rel="apple-touch-icon" href="{{ \App\Models\LandingPageSection::logoUrl() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('vote.index') }}">
    <meta property="og:title" content="Vote nas Melhores Empresas - Melhores do Ano {{ \App\Models\LandingPageSection::campaignYear() }}">
    <meta property="og:description" content="Escolha suas empresas favoritas do Vale do Xingu! Vote agora e concorra a prêmios exclusivos.">
    <meta property="og:image" content="{{ \App\Models\LandingPageSection::logoUrl() }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Melhores do Ano - Vale do Xingu">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Vote nas Melhores Empresas - Melhores do Ano {{ \App\Models\LandingPageSection::campaignYear() }}">
    <meta name="twitter:description" content="Escolha suas empresas favoritas do Vale do Xingu! Vote agora e concorra a prêmios exclusivos.">
    <meta name="twitter:image" content="{{ \App\Models\LandingPageSection::logoUrl() }}">

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
                        <img src="{{ \App\Models\LandingPageSection::logoUrl() }}" alt="Logomarca Melhores do Ano" class="h-10 sm:h-16">
                    </a>
                </div>
                <!-- Mobile menu button -->
                <button type="button" aria-label="Abrir menu de navegação" aria-controls="mobile-menu" aria-expanded="false" onclick="this.setAttribute('aria-expanded', !document.getElementById('mobile-menu').classList.toggle('hidden'))" class="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <nav class="hidden md:flex items-center space-x-6">
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
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white">
            <div class="px-4 py-3 space-y-2">
                @if($audience)
                    <a href="{{ route('vote.index') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium">Votar</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left py-2 text-gray-700 hover:text-red-600 font-medium">Sair</button>
                    </form>
                @else
                    <a href="{{ route('home') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium">Início</a>
                    <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 bg-red-600 text-white text-center rounded-lg hover:bg-red-700 font-medium">Cadastrar-se</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Banner de Patrocinadores Rotativo -->
    @if(isset($sponsors) && $sponsors->count() > 0)
    <div class="bg-gray-100 border-y border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center mb-3">
                <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Patrocínio</p>
            </div>
            <div class="relative overflow-hidden" style="height: 120px;">
                <div id="sponsor-carousel" class="flex transition-transform duration-500 ease-in-out">
                    @foreach($sponsors as $sponsor)
                        <div class="flex-shrink-0 w-full flex items-center justify-center px-4">
                            @if($sponsor->logo_path)
                                @if($sponsor->website)
                                    <a href="{{ $sponsor->website }}" target="_blank" rel="noopener" class="block">
                                        <img src="{{ asset('storage/' . $sponsor->logo_path) }}"
                                             alt="{{ $sponsor->name }}"
                                             class="max-h-20 max-w-md object-contain hover:scale-105 transition-transform">
                                    </a>
                                @else
                                    <img src="{{ asset('storage/' . $sponsor->logo_path) }}"
                                         alt="{{ $sponsor->name }}"
                                         class="max-h-20 max-w-md object-contain">
                                @endif
                            @else
                                <div class="text-2xl font-bold text-gray-400">{{ $sponsor->name }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @if($sponsors->count() > 1)
            <div class="flex justify-center gap-2 mt-4">
                @foreach($sponsors as $index => $sponsor)
                    <button onclick="goToSlide({{ $index }})"
                            class="sponsor-dot w-2 h-2 rounded-full bg-gray-400 hover:bg-gray-600 transition"
                            data-index="{{ $index }}"></button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Prize Highlight Banner -->
        @if($minimumPrizeVotes)
        <div class="mb-8">
            <div class="bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 rounded-2xl shadow-2xl overflow-hidden">
                <div class="grid md:grid-cols-2 gap-6 p-6 md:p-8 items-center">
                    <div class="text-white">
                        <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold mb-4 leading-tight">
                            Seu voto decide.<br>
                            E ainda pode virar prêmio! 🎁
                        </h2>
                        <p class="text-sm sm:text-lg md:text-xl mb-4 text-white/90">
                            Os prêmios disponíveis exigem a partir de {{ $minimumPrizeVotes }} votos. Confira os requisitos de cada prêmio abaixo.
                        </p>
                        @if($audience && isset($votedCategoryIds))
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4 inline-block">
                                <p class="text-sm font-semibold mb-1">Seus votos:</p>
                                <p class="text-4xl font-bold">{{ count($votedCategoryIds) }}</p>
                                <p class="text-sm mt-2">Confira seu progresso em cada prêmio abaixo.</p>
                            </div>
                        @endif
                    </div>
                    <div class="hidden md:block">
                        <img src="{{ asset('img/Eletromésticos.webp') }}"
                             alt="Prêmios"
                             class="w-full h-auto rounded-xl shadow-2xl transform hover:scale-105 transition-transform">
                        <p class="text-center text-xs text-white/70 mt-2 italic">*Imagens ilustrativas</p>
                    </div>
                </div>
            </div>
        </div>

        @endif
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Vote nas Categorias</h1>
            <p class="text-xl text-gray-600">Escolha uma categoria e vote na sua empresa favorita</p>

            {{-- Botão para votar em sequência --}}
            @if($audience)
                @php
                    $firstUnvoted = $categories->whereNotIn('id', $votedCategoryIds)->first();
                    $remaining = $categories->count() - count($votedCategoryIds);
                @endphp
                @if($firstUnvoted)
                    <a href="{{ route('vote.show', $firstUnvoted) }}"
                       class="inline-flex items-center mt-6 bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-4 rounded-xl font-bold hover:from-red-700 hover:to-red-800 transition-all transform hover:scale-105 shadow-lg text-lg">
                        🗳️ Votar em sequência
                        <span class="ml-3 bg-white/20 px-3 py-1 rounded-full text-sm">{{ $remaining }} restantes</span>
                    </a>
                @endif
            @endif
        </div>

        @if($awards->isNotEmpty())
            @include('components.award-progress')
        @endif

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

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 mb-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Melhores do Ano {{ \App\Models\LandingPageSection::campaignYear() }}</h3>
                    <p class="text-gray-400 text-sm">
                        Votação popular, transparente e gratuita das melhores empresas de Altamira.
                    </p>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-4">Links Rápidos</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Início</a></li>
                        <li><a href="{{ route('vote.index') }}" class="hover:text-white transition">Votação</a></li>
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
                <p>&copy; {{ date('Y') }} Melhores do Ano - Vale do Xingu. Todos os direitos reservados.</p>
            </div>
        </div>
        <x-regulation-link />
    </footer>

    <script>
        // Carousel de Patrocinadores
        @if(isset($sponsors) && $sponsors->count() > 1)
        let currentSlide = 0;
        const totalSlides = {{ $sponsors->count() }};
        const carousel = document.getElementById('sponsor-carousel');
        const dots = document.querySelectorAll('.sponsor-dot');

        function updateDots() {
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.remove('bg-gray-400');
                    dot.classList.add('bg-red-600');
                } else {
                    dot.classList.remove('bg-red-600');
                    dot.classList.add('bg-gray-400');
                }
            });
        }

        function goToSlide(index) {
            currentSlide = index;
            carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
            updateDots();
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            goToSlide(currentSlide);
        }

        updateDots();
        setInterval(nextSlide, 5000);
        @endif
    </script>
    @stack('scripts')
</body>
</html>
