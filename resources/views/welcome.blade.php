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
    <title>Melhores do Ano 2025 - Vale do Xingu | Vote nas melhores empresas</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Vote nas melhores empresas do Vale do Xingu! Participe da maior premiação da região e ajude a escolher os vencedores de 2025. Concorra a prêmios exclusivos!">
    <meta name="keywords" content="melhores do ano, vale do xingu, votação, prêmio, empresas, altamira, pará, amazônia">
    <meta name="author" content="Vale do Xingu">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://melhores.valedoxingu.com.br">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://melhores.valedoxingu.com.br">
    <meta property="og:title" content="Melhores do Ano 2025 - Vale do Xingu">
    <meta property="og:description" content="Vote nas melhores empresas do Vale do Xingu! Participe da maior premiação da região e concorra a prêmios exclusivos!">
    <meta property="og:image" content="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Melhores do Ano - Vale do Xingu">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://melhores.valedoxingu.com.br">
    <meta name="twitter:title" content="Melhores do Ano 2025 - Vale do Xingu">
    <meta name="twitter:description" content="Vote nas melhores empresas do Vale do Xingu! Participe da maior premiação da região e concorra a prêmios exclusivos!">
    <meta name="twitter:image" content="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.webp') }}">

    <!-- Schema.org JSON-LD -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "Event",
        "name": "Melhores do Ano 2025 - Vale do Xingu",
        "description": "Vote nas melhores empresas do Vale do Xingu! Participe da maior premiação da região.",
        "url": "https://melhores.valedoxingu.com.br",
        "eventStatus": "https://schema.org/EventScheduled",
        "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
        "location": {
            "@@type": "VirtualLocation",
            "url": "https://melhores.valedoxingu.com.br"
        },
        "organizer": {
            "@@type": "Organization",
            "name": "Vale do Xingu",
            "url": "https://valedoxingu.com.br"
        },
        "image": "{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}"
    }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @keyframes slide {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-slide {
            animation: slide 30s linear infinite;
        }
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
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
        .animate-slide-up-delay {
            animation: slideUp 0.8s ease-out 0.2s both;
        }
        .animate-slide-up-delay-2 {
            animation: slideUp 0.8s ease-out 0.4s both;
        }
        .animate-fade-in-delay {
            animation: fadeIn 1s ease-out 0.6s both;
        }

        /* Scroll animations */
        .scroll-animate {
            opacity: 0;
            transition: all 0.8s ease-out;
        }
        .scroll-animate.animated {
            opacity: 1;
        }
        .scroll-slide-up {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease-out;
        }
        .scroll-slide-up.animated {
            opacity: 1;
            transform: translateY(0);
        }
        .scroll-slide-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: all 0.8s ease-out;
        }
        .scroll-slide-left.animated {
            opacity: 1;
            transform: translateX(0);
        }
        .scroll-slide-right {
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.8s ease-out;
        }
        .scroll-slide-right.animated {
            opacity: 1;
            transform: translateX(0);
        }
        .scroll-scale {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.8s ease-out;
        }
        .scroll-scale.animated {
            opacity: 1;
            transform: scale(1);
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-white">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NMQC4WVT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    @if($sections['hero']->image ?? null)
                        <img src="{{ asset('storage/' . $sections['hero']->image) }}" alt="Logomarca Melhores do Ano" class="h-16">
                    @else
                        <img src="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Logomarca Melhores do Ano" class="h-16">
                    @endif
                </div>
                <nav class="flex items-center space-x-6">
                    @if(session('audience_id'))
                        <a href="{{ route('vote.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Votar</a>
                        <a href="{{ route('winners') }}" class="text-gray-700 hover:text-red-600 font-medium">Vencedores</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 font-medium">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('vote.index') }}" class="text-gray-700 hover:text-red-600 font-medium">Categorias</a>
                        <a href="{{ route('winners') }}" class="text-gray-700 hover:text-red-600 font-medium">Vencedores</a>
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 font-medium">Cadastrar-se</a>
                    @endif
                </nav>
            </div>
        </div>
    </header>

    <!-- Countdown -->
    @if($votingEndDate)
    <section class="bg-red-600 text-white py-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center space-x-8">
                <span class="font-bold text-lg">{{ $sections['countdown']->title ?? 'Encerra em' }}:</span>
                <div id="countdown" class="flex items-center space-x-4 text-2xl font-bold">
                    <div class="flex flex-col items-center">
                        <span id="days" class="text-3xl">00</span>
                        <span class="text-sm">DD</span>
                    </div>
                    <span>|</span>
                    <div class="flex flex-col items-center">
                        <span id="hours" class="text-3xl">00</span>
                        <span class="text-sm">HH</span>
                    </div>
                    <span>|</span>
                    <div class="flex flex-col items-center">
                        <span id="minutes" class="text-3xl">00</span>
                        <span class="text-sm">MM</span>
                    </div>
                    <span>|</span>
                    <div class="flex flex-col items-center">
                        <span id="seconds" class="text-3xl">00</span>
                        <span class="text-sm">SS</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-red-50 via-white to-red-50 py-24 md:py-32 overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-96 h-96 bg-red-500 rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-red-500 rounded-full blur-3xl transform translate-x-1/2 translate-y-1/2"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <!-- Logo -->
                <div class="mb-10 animate-fade-in">
                    <img src="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Logomarca Melhores do Ano" class="h-32 md:h-40 mx-auto drop-shadow-lg">
                </div>

                <!-- Headline -->
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6 leading-tight animate-slide-up">
                    <span class="block">{{ $sections['hero']->title ?? 'A votação dos Melhores do Ano 2025 de Altamira já começou!' }}</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl md:text-2xl lg:text-3xl text-gray-700 mb-10 max-w-3xl mx-auto font-medium leading-relaxed animate-slide-up-delay">
                    {{ $sections['hero']->content ?? 'Aqui, prêmio não se compra. Se decide no voto.' }}
                </p>

                <!-- CTA Buttons -->
                <div class="animate-slide-up-delay-2 flex flex-col sm:flex-row gap-4 items-center justify-center">
                    <a href="{{ route('register') }}" class="inline-block bg-gradient-to-r from-red-600 to-red-700 text-white px-10 py-5 rounded-xl text-xl font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-red-500/50 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <span>Clique pra votar</span>
                            <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                    </a>
                </div>

                <!-- Trust indicators -->
                <div class="mt-12 flex flex-wrap justify-center items-center gap-6 text-sm text-gray-600 animate-fade-in-delay">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Votação gratuita</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>Resultado auditado</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>100% legítimo</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Animated Banner -->
    <section class="bg-gray-100 py-4 overflow-hidden">
        <div class="whitespace-nowrap animate-slide">
            <span class="inline-block text-gray-700 font-medium text-lg mx-8">
                {{ $sections['animated_banner']->content ?? 'Votos auditados ° Resultado legítimo ° Sem cobranças' }}
            </span>
            <span class="inline-block text-gray-700 font-medium text-lg mx-8">
                {{ $sections['animated_banner']->content ?? 'Votos auditados ° Resultado legítimo ° Sem cobranças' }}
            </span>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="scroll-slide-left">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                        {{ $sections['about']->title ?? 'Entenda por que este prêmio é diferente.' }}
                    </h2>
                    <div class="prose prose-lg max-w-none text-gray-700 space-y-4">
                        @if($sections['about']->content ?? null)
                            {!! nl2br(e($sections['about']->content)) !!}
                        @else
                            <p class="text-lg leading-relaxed">
                                O Melhores do Ano é um prêmio único que reconhece as empresas mais queridas de Altamira através do voto popular. Diferente de outros prêmios, aqui não há jurados, influenciadores ou campanhas pagas.
                            </p>
                            <p class="text-lg leading-relaxed">
                                Na última edição, tivemos mais de 18 mil votos auditados, mais de 200 empresas participantes e mais de 50 segmentos votados. A entrega dos troféus acontece ao vivo no SBT Altamira, garantindo transparência total.
                            </p>
                            <p class="text-xl font-bold text-red-600 italic mt-6 transform transition-transform hover:scale-105">
                                "Aqui, quem escolhe é o povo"
                            </p>
                        @endif
                    </div>
                </div>
                <div class="relative scroll-slide-right float-animation">
                    @if($sections['about']->video_url ?? null)
                        @php
                            $videoUrl = $sections['about']->video_url;
                            // Se for URL do YouTube, converter para embed
                            if (strpos($videoUrl, 'youtube.com/watch') !== false || strpos($videoUrl, 'youtu.be/') !== false) {
                                preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $matches);
                                if (isset($matches[1])) {
                                    $videoUrl = 'https://www.youtube.com/embed/' . $matches[1];
                                }
                            }
                        @endphp
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="padding-bottom: 56.25%;">
                            <iframe
                                src="{{ $videoUrl }}"
                                class="absolute top-0 left-0 w-full h-full rounded-2xl"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen>
                            </iframe>
                        </div>
                    @else
                        <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="padding-bottom: 56.25%;">
                            <iframe
                                width="560"
                                height="315"
                                src="https://www.youtube.com/embed/sUz34ymF3tI?si=CWGTEXuaPRSXOTLm"
                                title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen
                                class="absolute top-0 left-0 w-full h-full rounded-2xl">
                            </iframe>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Prize Promotion Section -->
    <section class="py-20 bg-gradient-to-br from-yellow-50 via-orange-50 to-red-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <!-- Text Content -->
                <div class="scroll-slide-left order-2 md:order-1">
                    <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                        Seu voto decide.<br>
                        <span class="text-red-600">E ainda pode virar prêmio.</span>
                    </h2>
                    <p class="text-xl md:text-2xl text-gray-700 mb-8 leading-relaxed">
                        Vote em pelo menos 5 empresas no Melhores do Ano 2025 e participe dos sorteios promovidos pela Vale do Xingu.
                    </p>

                    <div class="space-y-4 mb-8">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-lg font-semibold text-gray-900">Vote gratuitamente</p>
                                <p class="text-gray-600">Não custa nada participar e concorrer</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-lg font-semibold text-gray-900">Participe automaticamente</p>
                                <p class="text-gray-600">Ao votar em 5+ empresas, você já está concorrendo</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-lg font-semibold text-gray-900">Prêmios incríveis</p>
                                <p class="text-gray-600">Eletrodomésticos e muito mais!</p>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('register') }}" class="inline-block bg-gradient-to-r from-red-600 to-red-700 text-white px-10 py-5 rounded-xl text-xl font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-red-500/50">
                        Começar a votar agora
                        <svg class="w-5 h-5 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>

                <!-- Prize Images -->
                <div class="scroll-slide-right order-1 md:order-2">
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 transform hover:scale-105 transition-transform duration-300">
                            <img src="{{ asset('img/Eletromésticos.webp') }}"
                                 alt="Prêmios - Eletrodomésticos"
                                 class="w-full h-auto rounded-xl">
                            <p class="text-center text-sm text-gray-500 mt-4 italic">*Imagens ilustrativas.</p>
                        </div>

                        <!-- Decorative elements -->
                        <div class="absolute -top-4 -right-4 w-24 h-24 bg-yellow-400 rounded-full opacity-20 blur-2xl"></div>
                        <div class="absolute -bottom-4 -left-4 w-32 h-32 bg-red-400 rounded-full opacity-20 blur-2xl"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    @if($categories->count() > 0)
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 scroll-slide-up">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Categorias em Votação</h2>
                <p class="text-xl text-gray-600">Escolha uma categoria e vote na sua empresa favorita</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach($categories as $index => $category)
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 border border-gray-100 overflow-hidden scroll-scale" data-delay="{{ $index * 100 }}">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <h3 class="text-2xl font-bold text-gray-900 flex-1">{{ $category->name }}</h3>
                        </div>

                        @if($category->description)
                            <p class="text-gray-600 mb-4 line-clamp-2 text-sm">{{ $category->description }}</p>
                        @endif

                        <div class="flex items-center text-sm text-gray-500 mb-6">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H9m10 0v-5.372a2.25 2.25 0 00-.488-1.398l-4.5-5.25a2.25 2.25 0 00-1.024-.786H10.5a2.25 2.25 0 00-2.25 2.25v5.372"></path>
                            </svg>
                            <span class="font-semibold">{{ $category->companies->count() }}</span>
                            <span class="ml-1">empresas participantes</span>
                        </div>

                        <a href="{{ route('vote.show', $category) }}" class="block w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl text-center font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Ver e Votar
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @if($categories->count() >= 6)
            <div class="text-center">
                <a href="{{ route('vote.index') }}" class="inline-block bg-gray-100 hover:bg-gray-200 text-gray-900 px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    Ver Todas as Categorias
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif

    <!-- Stats Section -->
    <section class="py-24 bg-gradient-to-b from-red-600 to-red-700 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16 scroll-slide-up">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    {{ $sections['stats']->title ?? 'O prêmio que tem credibilidade no mercado' }}
                </h2>
                <p class="text-xl md:text-2xl text-red-100">
                    {{ $sections['stats']->content ?? 'Resultado Melhores do Ano 2024' }}
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-8 text-center border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 shadow-xl scroll-scale" data-delay="0">
                    <div class="mb-4 transform transition-transform hover:scale-110">
                        <svg class="w-16 h-16 mx-auto text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="text-5xl font-extrabold mb-3 text-white">+18 mil</div>
                    <div class="text-lg font-semibold text-red-100">votos auditados</div>
                </div>

                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-8 text-center border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 shadow-xl scroll-scale" data-delay="100">
                    <div class="mb-4 transform transition-transform hover:scale-110">
                        <svg class="w-16 h-16 mx-auto text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0H9m10 0v-5.372a2.25 2.25 0 00-.488-1.398l-4.5-5.25a2.25 2.25 0 00-1.024-.786H10.5a2.25 2.25 0 00-2.25 2.25v5.372"></path>
                        </svg>
                    </div>
                    <div class="text-5xl font-extrabold mb-3 text-white">+200</div>
                    <div class="text-lg font-semibold text-red-100">empresas participantes</div>
                </div>

                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-8 text-center border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 shadow-xl scroll-scale" data-delay="200">
                    <div class="mb-4 transform transition-transform hover:scale-110">
                        <svg class="w-16 h-16 mx-auto text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="text-5xl font-extrabold mb-3 text-white">+50</div>
                    <div class="text-lg font-semibold text-red-100">segmentos votados</div>
                </div>

                <div class="bg-white/15 backdrop-blur-md rounded-2xl p-8 text-center border border-white/20 hover:bg-white/20 transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 shadow-xl scroll-scale" data-delay="300">
                    <div class="mb-4 transform transition-transform hover:scale-110">
                        <svg class="w-16 h-16 mx-auto text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="text-lg font-semibold text-red-100 mb-2">Entrega dos troféus</div>
                    <div class="text-base text-white/90">ao vivo no SBT Altamira</div>
                </div>
            </div>

            <div class="text-center mt-16 scroll-slide-up">
                <a href="{{ route('register') }}" class="inline-block bg-white text-red-600 px-10 py-5 rounded-xl text-xl font-bold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-2xl">
                    Vote agora
                </a>
            </div>
        </div>
    </section>

    <!-- Winners Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="scroll-slide-up">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-4">
                    {{ $sections['winners']->title ?? 'Melhores do Ano 2024' }}
                </h2>
                <p class="text-xl text-center text-gray-700 mb-12">
                    {{ $sections['winners']->content ?? 'Aqui, o resultado é público, auditável e legítimo.' }}
                </p>
            </div>

            @if($winners->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @foreach($winners as $index => $winner)
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-105 scroll-scale" data-delay="{{ $index * 100 }}">
                    @if($winner->image)
                        @php
                            $imagePath = $winner->image;
                            // Garante que o caminho está correto para o storage
                            if (!str_starts_with($imagePath, 'winners/')) {
                                $imagePath = 'winners/' . basename($imagePath);
                            }
                            $imageUrl = asset('storage/' . $imagePath);
                        @endphp
                        <img src="{{ $imageUrl }}" alt="{{ $winner->name }}" class="w-full h-48 object-cover" onerror="this.parentElement.innerHTML='<div class=\'w-full h-48 bg-gray-200 flex items-center justify-center\'><span class=\'text-gray-400\'>Imagem não encontrada</span></div>';">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">Sem imagem</span>
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">{{ $winner->name }}</h3>
                        @if($winner->category)
                            <p class="text-sm text-gray-600 mb-1">{{ $winner->category }}</p>
                        @endif
                        @if($winner->company_name)
                            <p class="text-sm font-medium text-red-600">{{ $winner->company_name }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @for($i = 0; $i < 8; $i++)
                <div class="bg-gray-50 rounded-lg overflow-hidden shadow-md">
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">Foto</span>
                    </div>
                </div>
                @endfor
            </div>
            @endif

            <p class="text-sm text-gray-600 text-center italic">
                {{ $sections['winners_note']->content ?? 'Obs: As marcas reconhecidas nesta premiação não foram indicadas por jurados, influenciadores ou campanhas pagas. Elas foram escolhidas pela própria população de Altamira.' }}
            </p>
        </div>
    </section>

    <!-- Sponsors Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="scroll-slide-up">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">
                    {{ $sections['sponsors']->title ?? 'Patrocinadores' }}
                </h2>
            </div>
            <div class="grid md:grid-cols-3 lg:grid-cols-6 gap-8 items-center justify-items-center">
                @php
                    $displaySponsors = collect();
                    if($sponsors->count() > 0) {
                        // Repetir patrocinadores até completar 6
                        while($displaySponsors->count() < 6) {
                            foreach($sponsors as $sponsor) {
                                $displaySponsors->push($sponsor);
                                if($displaySponsors->count() >= 6) break;
                            }
                        }
                    }
                @endphp
                @forelse($displaySponsors as $index => $sponsor)
                <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-110 scroll-scale" data-delay="{{ $index * 100 }}">
                    @if($sponsor->logo_path)
                    <a href="{{ $sponsor->website }}" target="_blank" rel="noopener noreferrer" class="block">
                        <img src="{{ asset('storage/' . $sponsor->logo_path) }}" alt="{{ $sponsor->name }}" class="w-24 h-24 object-contain rounded">
                    </a>
                    @else
                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                        <span class="text-gray-500 text-xs text-center">{{ $sponsor->name }}</span>
                    </div>
                    @endif
                </div>
                @empty
                @for($i = 0; $i < 6; $i++)
                <div class="bg-white p-4 rounded-lg shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 hover:scale-110 scroll-scale" data-delay="{{ $i * 100 }}">
                    <div class="w-24 h-24 bg-gray-200 rounded flex items-center justify-center">
                        <span class="text-gray-400 text-xs">Logo</span>
                    </div>
                </div>
                @endfor
                @endforelse
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="scroll-slide-up">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">
                    {{ $sections['faq']->title ?? 'Perguntas frequentes' }}
                </h2>
            </div>
            <div class="space-y-4">
                @php
                    $faqMetadata = $sections['faq']->metadata ?? [];
                    $faqs = [];
                    for($i = 1; $i <= 6; $i++) {
                        if(isset($faqMetadata["faq{$i}_question"]) && isset($faqMetadata["faq{$i}_answer"])) {
                            $faqs[] = [
                                'question' => $faqMetadata["faq{$i}_question"],
                                'answer' => $faqMetadata["faq{$i}_answer"]
                            ];
                        }
                    }
                @endphp
                @foreach($faqs as $index => $faq)
                <div class="border border-gray-200 rounded-lg overflow-hidden scroll-slide-up transition-all duration-300 hover:shadow-lg" data-delay="{{ $index * 50 }}">
                    <button onclick="toggleFaq({{ $index }})" class="w-full px-6 py-4 text-left font-semibold text-gray-900 hover:bg-gray-50 flex justify-between items-center transition-colors">
                        <span>{{ $faq['question'] }}</span>
                        <svg id="icon-{{ $index }}" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="answer-{{ $index }}" class="hidden px-6 py-4 text-gray-700 bg-gray-50">
                        {{ $faq['answer'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gradient-to-b from-gray-50 to-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <!-- Logo -->
                <div class="md:col-span-1">
                    <img src="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Logomarca Melhores do Ano" class="h-20 mb-6">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        O prêmio que reconhece os melhores de Altamira através do voto popular.
                    </p>
                </div>

                <!-- Informações -->
                <div>
                    <h3 class="font-bold text-lg mb-6 text-gray-900">Informações Legais</h3>
                    <div class="space-y-3">
                        <p class="text-sm text-gray-700 leading-relaxed">
                            <span class="font-semibold">{{ $sections['footer']->metadata['company_name'] ?? 'Rede de Rádio e Televisão Vale do Xingu LTDA' }}</span>
                        </p>
                        <p class="text-sm text-gray-600">
                            CNPJ: {{ $sections['footer']->metadata['cnpj'] ?? '22.918.262/0001-72' }}
                        </p>
                    </div>
                </div>

                <!-- Links -->
                <div>
                    <h3 class="font-bold text-lg mb-6 text-gray-900">Documentos</h3>
                    <div class="space-y-3">
                        <a href="{{ $sections['footer']->metadata['terms_url'] ?? '#' }}" target="_blank" class="block text-sm text-gray-600 hover:text-red-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Termos de uso / Política de privacidade
                        </a>
                        <a href="{{ $sections['footer']->metadata['regulation_url'] ?? '#' }}" target="_blank" class="block text-sm text-gray-600 hover:text-red-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Regulamento
                        </a>
                        <a href="{{ $sections['footer']->metadata['lgpd_url'] ?? '#' }}" target="_blank" class="block text-sm text-gray-600 hover:text-red-600 transition-colors flex items-center group">
                            <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            LGPD
                        </a>
                    </div>
                </div>

                <!-- Redes Sociais -->
                <div>
                    <h3 class="font-bold text-lg mb-6 text-gray-900">Siga-nos</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ $sections['footer']->metadata['facebook_url'] ?? '#' }}" target="_blank" class="bg-white border border-gray-200 hover:bg-red-600 hover:border-red-600 p-3 rounded-lg transition-all duration-300 transform hover:scale-110 group shadow-sm">
                            <img src="{{ asset('files/Melhores do ano 2025 - Facebook.webp') }}" alt="Facebook" class="w-6 h-6 group-hover:brightness-0 group-hover:invert">
                        </a>
                        <a href="{{ $sections['footer']->metadata['instagram_url'] ?? '#' }}" target="_blank" class="bg-white border border-gray-200 hover:bg-red-600 hover:border-red-600 p-3 rounded-lg transition-all duration-300 transform hover:scale-110 group shadow-sm">
                            <img src="{{ asset('files/Melhores do ano 2025 - Instagram.webp') }}" alt="Instagram" class="w-6 h-6 group-hover:brightness-0 group-hover:invert">
                        </a>
                        <a href="{{ $sections['footer']->metadata['youtube_url'] ?? '#' }}" target="_blank" class="bg-white border border-gray-200 hover:bg-red-600 hover:border-red-600 p-3 rounded-lg transition-all duration-300 transform hover:scale-110 group shadow-sm">
                            <img src="{{ asset('files/Melhores do ano 2025 - youtube.webp') }}" alt="Youtube" class="w-6 h-6 group-hover:brightness-0 group-hover:invert">
                        </a>
                        <a href="{{ $sections['footer']->metadata['tiktok_url'] ?? '#' }}" target="_blank" class="bg-white border border-gray-200 hover:bg-red-600 hover:border-red-600 p-3 rounded-lg transition-all duration-300 transform hover:scale-110 group shadow-sm">
                            <img src="{{ asset('files/Melhores do ano 2025 - TikTok.webp') }}" alt="TikTok" class="w-6 h-6 group-hover:brightness-0 group-hover:invert">
                        </a>
                        <a href="{{ $sections['footer']->metadata['whatsapp_url'] ?? '#' }}" target="_blank" class="bg-white border border-gray-200 hover:bg-red-600 hover:border-red-600 p-3 rounded-lg transition-all duration-300 transform hover:scale-110 group shadow-sm">
                            <img src="{{ asset('files/Melhores do ano 2025 - whatsapp.webp') }}" alt="WhatsApp" class="w-6 h-6 group-hover:brightness-0 group-hover:invert">
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-200 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-gray-600 text-center md:text-left">
                        &copy; {{ date('Y') }} Melhores do Ano. Todos os direitos reservados.
                    </p>
                    <p class="text-sm text-gray-500">
                        Site: <span class="text-red-600 font-semibold">melhores.valedoxingu.com.br</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleFaq(index) {
            const answer = document.getElementById('answer-' + index);
            const icon = document.getElementById('icon-' + index);
            const isHidden = answer.classList.contains('hidden');

            if (isHidden) {
                answer.classList.remove('hidden');
                icon.classList.add('rotate-180');
            } else {
                answer.classList.add('hidden');
                icon.classList.remove('rotate-180');
            }
        }

        @if($votingEndDate)
        function updateCountdown() {
            const endDate = new Date('{{ $votingEndDate }}').getTime();
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance < 0) {
                document.getElementById('countdown').innerHTML = '<span class="text-2xl">Votação encerrada</span>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('days').textContent = String(days).padStart(2, '0');
            document.getElementById('hours').textContent = String(hours).padStart(2, '0');
            document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
            document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
        @endif

        // Scroll animations
        function animateOnScroll() {
            const elements = document.querySelectorAll('.scroll-animate, .scroll-slide-up, .scroll-slide-left, .scroll-slide-right, .scroll-scale');

            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                const delay = element.dataset.delay ? parseInt(element.dataset.delay) : 0;

                if (elementTop < window.innerHeight - elementVisible) {
                    setTimeout(() => {
                        element.classList.add('animated');
                    }, delay);
                }
            });
        }

        // Run on scroll and on load
        window.addEventListener('scroll', animateOnScroll);
        window.addEventListener('load', animateOnScroll);
        animateOnScroll(); // Run once on page load

        // SweetAlert2 para mensagens de sucesso
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '📧 E-mail Enviado!',
                html: `
                    <div style="text-align: center;">
                        <p style="font-size: 16px; margin-bottom: 20px; color: #374151;">
                            {{ session('success') }}
                        </p>
                    </div>
                `,
                confirmButtonText: 'Entendi',
                confirmButtonColor: '#dc2626',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        @endif
    </script>

</body>
</html>
