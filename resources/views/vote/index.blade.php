<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - Melhores do Ano 2025</title>
    <link rel="icon" type="image/webp" href="{{ asset('img/logo_icon.webp') }}">
    <link rel="icon" type="image/webp" href="{{ asset('img/logo.webp') }}">
    <link rel="shortcut icon" type="image/webp" href="{{ asset('img/logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.webp') }}">
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
                        <a href="{{ route('winners') }}" class="text-gray-700 hover:text-red-600 font-medium">Vencedores</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 font-medium">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-red-600 font-medium">Início</a>
                        <a href="{{ route('winners') }}" class="text-gray-700 hover:text-red-600 font-medium">Vencedores</a>
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium">Login</a>
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 font-medium">Cadastrar-se</a>
                    @endif
                </nav>
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
        <!-- Contagem Regressiva -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-red-600 to-red-700 rounded-2xl shadow-2xl p-6 md:p-8 text-white text-center">
                <h2 class="text-xl md:text-2xl font-bold mb-4">⏰ Votação Encerra em:</h2>
                <div id="countdown" class="flex justify-center gap-3 md:gap-6 flex-wrap">
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 min-w-[80px]">
                        <div class="text-3xl md:text-4xl font-bold" id="days">00</div>
                        <div class="text-xs md:text-sm mt-1 opacity-90">Dias</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 min-w-[80px]">
                        <div class="text-3xl md:text-4xl font-bold" id="hours">00</div>
                        <div class="text-xs md:text-sm mt-1 opacity-90">Horas</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 min-w-[80px]">
                        <div class="text-3xl md:text-4xl font-bold" id="minutes">00</div>
                        <div class="text-xs md:text-sm mt-1 opacity-90">Minutos</div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm rounded-xl p-3 min-w-[80px]">
                        <div class="text-3xl md:text-4xl font-bold" id="seconds">00</div>
                        <div class="text-xs md:text-sm mt-1 opacity-90">Segundos</div>
                    </div>
                </div>
                <p class="mt-4 text-sm md:text-base opacity-90">15 de Março de 2026 - 23:59:59</p>
            </div>
        </div>

        <!-- Prize Highlight Banner -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-yellow-400 via-orange-400 to-red-400 rounded-2xl shadow-2xl overflow-hidden">
                <div class="grid md:grid-cols-2 gap-6 p-6 md:p-8 items-center">
                    <div class="text-white">
                        <h2 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">
                            Seu voto decide.<br>
                            E ainda pode virar prêmio! 🎁
                        </h2>
                        <p class="text-lg md:text-xl mb-4 text-white/90">
                            Vote em pelo menos 5 empresas e concorra a prêmios incríveis da Vale do Xingu.
                        </p>
                        @if($audience && isset($votedCategoryIds))
                            <div class="bg-white/20 backdrop-blur-sm rounded-xl px-6 py-4 inline-block">
                                <p class="text-sm font-semibold mb-1">Seus votos:</p>
                                <p class="text-4xl font-bold">{{ count($votedCategoryIds) }} / 5</p>
                                @if(count($votedCategoryIds) >= 5)
                                    <p class="text-sm mt-2">✅ Você está participando!</p>
                                @else
                                    <p class="text-sm mt-2">Faltam {{ 5 - count($votedCategoryIds) }} votos</p>
                                @endif
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

        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">Vote nas Categorias</h1>
            <p class="text-xl text-gray-600">Escolha uma categoria e vote na sua empresa favorita</p>
        </div>

        <!-- Seção de Prêmios Escalonados -->
        @if(isset($awards) && $awards->count() > 0)
        @php
            $totalCategories = $categories->count();
            $userVotes = $audience ? count($votedCategoryIds) : 0;
            
            // Agrupar prêmios por tier
            $awardsByTier = $awards->where('is_active', true)
                ->filter(fn($a) => $a->hasRemainingQuantity())
                ->groupBy('tier')
                ->sortKeys();
        @endphp
        
        <div class="mb-12">
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-xl p-6 md:p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-3">🎁 Concorra aos Prêmios!</h2>
                    <p class="text-base md:text-lg text-gray-700">Quanto mais você vota, mais prêmios você pode ganhar!</p>
                </div>
                
                @if($audience)
                    <div class="mb-8 text-center">
                        <div class="inline-flex items-center bg-white rounded-xl px-8 py-4 shadow-lg">
                            <svg class="w-8 h-8 text-red-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-left">
                                <p class="text-sm text-gray-600">Seus votos:</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $userVotes }} <span class="text-xl text-gray-500">/ {{ $totalCategories }}</span></p>
                            </div>
                        </div>
                        
                        @if($userVotes >= $totalCategories)
                            <p class="mt-4 text-xl text-green-700 font-bold">🏆 Parabéns! Você concorre a TODOS os prêmios!</p>
                        @elseif($userVotes >= 15)
                            <p class="mt-4 text-lg text-green-700 font-semibold">✓ Você está participando do sorteio dos Prêmios Nível 1 e 2!</p>
                            <p class="text-sm text-orange-600 mt-1">Vote em {{ $totalCategories - $userVotes }} categorias para concorrer ao Prêmio Máximo!</p>
                        @elseif($userVotes >= 5)
                            <p class="mt-4 text-lg text-green-700 font-semibold">✓ Você está participando do sorteio dos Prêmios Nível 1!</p>
                            <p class="text-sm text-orange-600 mt-1">Vote em mais {{ 15 - $userVotes }} categorias para desbloquear Prêmios Nível 2!</p>
                        @else
                            <p class="mt-4 text-lg text-orange-700 font-semibold">Faltam {{ 5 - $userVotes }} votos para participar do sorteio</p>
                        @endif
                    </div>
                @endif
                
                <!-- Tier 1: 5 votos -->
                @if(isset($awardsByTier[1]))
                <div class="mb-8 @if($audience && $userVotes < 5) opacity-60 @endif">
                    <div class="flex items-center justify-between mb-4 bg-blue-100 rounded-xl px-6 py-3">
                        <div>
                            <h3 class="text-xl font-bold text-blue-900">🥉 Nível 1 - Prêmios Básicos</h3>
                            <p class="text-sm text-blue-700">Vote em <strong>5 empresas</strong> para concorrer</p>
                        </div>
                        @if($audience)
                            @if($userVotes >= 5)
                                <span class="bg-green-500 text-white px-4 py-2 rounded-full font-bold text-sm">✓ DESBLOQUEADO</span>
                            @else
                                <span class="bg-gray-400 text-white px-4 py-2 rounded-full font-bold text-sm">🔒 Faltam {{ 5 - $userVotes }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($awardsByTier[1] as $award)
                        <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-all @if($audience && $userVotes >= 5) ring-2 ring-green-400 @endif">
                            @if($award->image_path)
                                <div class="mb-3 h-24 flex items-center justify-center bg-gray-50 rounded-lg">
                                    <img src="{{ asset('storage/' . $award->image_path) }}" alt="{{ $award->name }}" class="max-h-full max-w-full object-contain">
                                </div>
                            @else
                                <div class="mb-3 h-24 flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg">
                                    <span class="text-4xl">🎁</span>
                                </div>
                            @endif
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $award->name }}</h4>
                            @if($award->description)
                                <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $award->description }}</p>
                            @endif
                            <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold">
                                {{ $award->remainingQuantity() }} disponíveis
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Tier 2: 15 votos -->
                @if(isset($awardsByTier[2]))
                <div class="mb-8 @if($audience && $userVotes < 15) opacity-60 @endif">
                    <div class="flex items-center justify-between mb-4 bg-orange-100 rounded-xl px-6 py-3">
                        <div>
                            <h3 class="text-xl font-bold text-orange-900">🥈 Nível 2 - Prêmios Intermediários</h3>
                            <p class="text-sm text-orange-700">Vote em <strong>15 empresas</strong> para concorrer</p>
                        </div>
                        @if($audience)
                            @if($userVotes >= 15)
                                <span class="bg-green-500 text-white px-4 py-2 rounded-full font-bold text-sm">✓ DESBLOQUEADO</span>
                            @else
                                <span class="bg-gray-400 text-white px-4 py-2 rounded-full font-bold text-sm">🔒 Faltam {{ max(0, 15 - $userVotes) }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($awardsByTier[2] as $award)
                        <div class="bg-white rounded-xl shadow-md p-4 hover:shadow-lg transition-all @if($audience && $userVotes >= 15) ring-2 ring-green-400 @endif">
                            @if($award->image_path)
                                <div class="mb-3 h-24 flex items-center justify-center bg-gray-50 rounded-lg">
                                    <img src="{{ asset('storage/' . $award->image_path) }}" alt="{{ $award->name }}" class="max-h-full max-w-full object-contain">
                                </div>
                            @else
                                <div class="mb-3 h-24 flex items-center justify-center bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg">
                                    <span class="text-4xl">🎁</span>
                                </div>
                            @endif
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $award->name }}</h4>
                            @if($award->description)
                                <p class="text-gray-600 text-xs mb-2 line-clamp-2">{{ $award->description }}</p>
                            @endif
                            <span class="inline-block bg-orange-100 text-orange-800 px-2 py-1 rounded-full text-xs font-semibold">
                                {{ $award->remainingQuantity() }} disponíveis
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Tier 3: Todos os votos -->
                @if(isset($awardsByTier[3]))
                <div class="@if($audience && $userVotes < $totalCategories) opacity-60 @endif">
                    <div class="flex items-center justify-between mb-4 bg-gradient-to-r from-yellow-100 via-yellow-200 to-yellow-100 rounded-xl px-6 py-3 shadow-lg">
                        <div>
                            <h3 class="text-xl font-bold text-yellow-900">🥇 Nível 3 - Prêmio Máximo</h3>
                            <p class="text-sm text-yellow-700">Vote em <strong>TODAS as categorias</strong> para concorrer</p>
                        </div>
                        @if($audience)
                            @if($userVotes >= $totalCategories)
                                <span class="bg-green-500 text-white px-4 py-2 rounded-full font-bold text-sm animate-pulse">✓ DESBLOQUEADO</span>
                            @else
                                <span class="bg-gray-400 text-white px-4 py-2 rounded-full font-bold text-sm">🔒 Faltam {{ $totalCategories - $userVotes }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($awardsByTier[3] as $award)
                        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl shadow-lg p-4 hover:shadow-xl transition-all border-2 border-yellow-300 @if($audience && $userVotes >= $totalCategories) ring-4 ring-green-400 @endif">
                            @if($award->image_path)
                                <div class="mb-3 h-32 flex items-center justify-center bg-white rounded-lg">
                                    <img src="{{ asset('storage/' . $award->image_path) }}" alt="{{ $award->name }}" class="max-h-full max-w-full object-contain">
                                </div>
                            @else
                                <div class="mb-3 h-32 flex items-center justify-center bg-gradient-to-br from-yellow-200 to-yellow-300 rounded-lg">
                                    <span class="text-6xl">🏆</span>
                                </div>
                            @endif
                            <h4 class="text-xl font-bold text-yellow-900 mb-1">{{ $award->name }}</h4>
                            @if($award->description)
                                <p class="text-gray-700 text-sm mb-2">{{ $award->description }}</p>
                            @endif
                            <span class="inline-block bg-yellow-200 text-yellow-900 px-3 py-1 rounded-full text-sm font-bold">
                                {{ $award->remainingQuantity() }} disponíveis
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
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

    <script>
        // Contagem Regressiva
        function updateCountdown() {
            const endDate = new Date('2026-03-15T23:59:59').getTime();
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance < 0) {
                document.getElementById('countdown').innerHTML = '<div class="text-2xl font-bold">Votação Encerrada!</div>';
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
</body>
</html>
