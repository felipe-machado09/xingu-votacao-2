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
    <title>{{ $company->legal_name }} - Melhores do Ano 2025</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('logo_icon.ico') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('vote.company', $company) }}">
    <meta property="og:title" content="{{ $company->legal_name }} - Vote em nós!">
    <meta property="og:description" content="Estamos participando do Melhores do Ano 2025! Vote em {{ $company->legal_name }} e nos ajude a ganhar!">
    @if($company->logo_path)
        <meta property="og:image" content="{{ asset('storage/' . $company->logo_path) }}">
    @else
        <meta property="og:image" content="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}">
    @endif
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:site_name" content="Melhores do Ano - Vale do Xingu">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ route('vote.company', $company) }}">
    <meta name="twitter:title" content="{{ $company->legal_name }} - Vote em nós!">
    <meta name="twitter:description" content="Estamos participando do Melhores do Ano 2025! Vote em {{ $company->legal_name }} e nos ajude a ganhar!">
    @if($company->logo_path)
        <meta name="twitter:image" content="{{ asset('storage/' . $company->logo_path) }}">
    @else
        <meta name="twitter:image" content="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
        .animate-scale-in {
            animation: scaleIn 0.6s ease-out;
        }
        .gradient-overlay {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.95) 0%, rgba(185, 28, 28, 0.95) 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NMQC4WVT"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- Header -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Logomarca Melhores do Ano" class="h-10 sm:h-14">
                    </a>
                </div>
                <!-- Mobile menu button -->
                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="md:hidden p-2 rounded-lg text-gray-700 hover:bg-gray-100 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('vote.index') }}" class="text-gray-700 hover:text-red-600 font-medium transition-colors">Categorias</a>
                    @if($audience)
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 font-medium transition-colors">Sair</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-red-600 text-white px-6 py-2.5 rounded-lg hover:bg-red-700 font-semibold transition-all shadow-md hover:shadow-lg">Cadastrar-se</a>
                    @endif
                </nav>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white">
            <div class="px-4 py-3 space-y-2">
                <a href="{{ route('vote.index') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium">Categorias</a>
                @if($audience)
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left py-2 text-gray-700 hover:text-red-600 font-medium">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block py-2 text-gray-700 hover:text-red-600 font-medium">Login</a>
                    <a href="{{ route('register') }}" class="block py-2 bg-red-600 text-white text-center rounded-lg hover:bg-red-700 font-medium">Cadastrar-se</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-red-600 via-red-700 to-red-800 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-20">
            <div class="text-center animate-fade-in">
                @if($company->logo_path)
                    <div class="mb-6 sm:mb-8 animate-scale-in">
                        <div class="inline-block bg-white rounded-2xl p-4 sm:p-6 shadow-2xl transform hover:scale-105 transition-transform duration-300">
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->legal_name }}" class="h-20 sm:h-32 md:h-40 object-contain">
                        </div>
                    </div>
                @endif
                <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 animate-slide-up">
                    {{ $company->legal_name }}
                </h1>
                <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-4 sm:px-6 py-2 sm:py-3 rounded-full mb-6 animate-slide-up">
                    <i class="fas fa-trophy text-yellow-300"></i>
                    <p class="text-sm sm:text-lg md:text-xl font-semibold">Participante do Melhores do Ano 2025</p>
                </div>
                <p class="text-base sm:text-xl md:text-2xl text-red-100 max-w-3xl mx-auto animate-slide-up">
                    Ajude-nos a ganhar! Vote em {{ $company->legal_name }} nas categorias em que participamos.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Sidebar -->
            <aside class="lg:col-span-1 space-y-6">
                <!-- Company Info Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 animate-fade-in">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-building text-red-600 mr-3"></i>
                        Informações
                    </h2>
                    <div class="space-y-5">
                        @if($company->responsible_name)
                            <div class="border-l-4 border-red-500 pl-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Responsável</p>
                                <p class="text-base font-semibold text-gray-900">{{ $company->responsible_name }}</p>
                            </div>
                        @endif
                        @if($company->responsible_phone)
                            <div class="border-l-4 border-red-500 pl-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Telefone</p>
                                @php
                                    $phone = preg_replace('/\D/', '', $company->responsible_phone);
                                    $formattedPhone = '';
                                    if (strlen($phone) == 11) {
                                        $formattedPhone = '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
                                    } elseif (strlen($phone) == 10) {
                                        $formattedPhone = '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
                                    } else {
                                        $formattedPhone = $company->responsible_phone;
                                    }
                                @endphp
                                <a href="tel:{{ $phone }}" class="text-base font-semibold text-red-600 hover:text-red-700 transition-colors">
                                    <i class="fas fa-phone mr-2"></i>{{ $formattedPhone }}
                                </a>
                            </div>
                        @endif
                        @if($company->cnpj)
                            <div class="border-l-4 border-red-500 pl-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">CNPJ</p>
                                <p class="text-base font-semibold text-gray-900 font-mono">{{ preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $company->cnpj) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Share Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 animate-fade-in">
                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-share-alt text-red-600 mr-3"></i>
                        Compartilhar
                    </h3>
                    <div class="space-y-3">
                        <button onclick="shareOnFacebook()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center shadow-md">
                            <i class="fab fa-facebook-f mr-3 text-lg"></i>
                            Facebook
                        </button>
                        <button onclick="shareOnWhatsApp()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center shadow-md">
                            <i class="fab fa-whatsapp mr-3 text-lg"></i>
                            WhatsApp
                        </button>
                        <button onclick="shareOnInstagram()" class="w-full bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 hover:from-purple-700 hover:via-pink-700 hover:to-orange-600 text-white px-4 py-3 rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center shadow-md">
                            <i class="fab fa-instagram mr-3 text-lg"></i>
                            Instagram
                        </button>
                        <button onclick="copyLink()" class="w-full bg-gray-700 hover:bg-gray-800 text-white px-4 py-3 rounded-lg font-semibold transition-all transform hover:scale-105 flex items-center justify-center shadow-md">
                            <i class="fas fa-link mr-3"></i>
                            Copiar Link
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Gallery Section -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 animate-fade-in">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-images text-red-600 mr-3"></i>
                        Galeria
                    </h2>
                    <div class="grid md:grid-cols-3 gap-4">
                        <!-- Imagem Principal -->
                        <div class="relative group overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 bg-gray-100">
                            @if($company->main_image_path)
                                <img src="{{ asset('storage/' . $company->main_image_path) }}" alt="Imagem Principal - {{ $company->legal_name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <p class="text-white font-semibold text-sm">Imagem Principal</p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-48 flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                                    <p class="text-gray-500 text-sm font-medium">Imagem Principal</p>
                                    <p class="text-gray-400 text-xs mt-1">Não adicionada</p>
                                </div>
                            @endif
                        </div>

                        <!-- Imagem da Fachada -->
                        <div class="relative group overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 bg-gray-100">
                            @if($company->facade_image_path)
                                <img src="{{ asset('storage/' . $company->facade_image_path) }}" alt="Fachada - {{ $company->legal_name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <p class="text-white font-semibold text-sm">Fachada</p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-48 flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <i class="fas fa-building text-gray-400 text-4xl mb-2"></i>
                                    <p class="text-gray-500 text-sm font-medium">Fachada</p>
                                    <p class="text-gray-400 text-xs mt-1">Não adicionada</p>
                                </div>
                            @endif
                        </div>

                        <!-- Imagem da Equipe -->
                        <div class="relative group overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 bg-gray-100">
                            @if($company->team_image_path)
                                <img src="{{ asset('storage/' . $company->team_image_path) }}" alt="Equipe - {{ $company->legal_name }}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <p class="text-white font-semibold text-sm">Equipe</p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-48 flex flex-col items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                    <i class="fas fa-users text-gray-400 text-4xl mb-2"></i>
                                    <p class="text-gray-500 text-sm font-medium">Equipe</p>
                                    <p class="text-gray-400 text-xs mt-1">Não adicionada</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Categories Card -->
                <div class="bg-white rounded-xl shadow-lg p-8 border border-gray-100 animate-fade-in">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-tags text-red-600 mr-3"></i>
                            Categorias de Participação
                        </h2>
                        <span class="bg-red-100 text-red-700 px-4 py-1.5 rounded-full text-sm font-semibold">
                            {{ $company->categories->count() }} {{ $company->categories->count() == 1 ? 'categoria' : 'categorias' }}
                        </span>
                    </div>

                    @if($company->categories->count() > 0)
                        <div class="grid md:grid-cols-2 gap-4">
                            @foreach($company->categories as $category)
                                <a href="{{ route('vote.show', $category) }}" class="group relative bg-gradient-to-br from-gray-50 to-white border-2 border-gray-200 hover:border-red-400 rounded-xl p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900 group-hover:text-red-600 mb-2 transition-colors">
                                                {{ $category->name }}
                                            </h3>
                                            @if(isset($votesByCategory[$category->id]))
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <i class="fas fa-vote-yea text-red-500 mr-2"></i>
                                                    <span class="font-semibold">{{ $votesByCategory[$category->id]['count'] }}</span>
                                                    <span class="ml-1">{{ $votesByCategory[$category->id]['count'] == 1 ? 'voto' : 'votos' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="w-10 h-10 bg-red-100 group-hover:bg-red-600 rounded-lg flex items-center justify-center transition-colors">
                                                <i class="fas fa-arrow-right text-red-600 group-hover:text-white transition-colors"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-inbox text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 font-medium">Nenhuma categoria cadastrada.</p>
                        </div>
                    @endif
                </div>

                <!-- CTA Card -->
                <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-xl shadow-2xl p-8 text-white relative overflow-hidden animate-fade-in">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -ml-32 -mb-32"></div>
                    <div class="relative z-10 text-center">
                        <div class="mb-6">
                            <i class="fas fa-hand-pointer text-5xl text-yellow-300 mb-4"></i>
                            <h3 class="text-3xl font-extrabold mb-3">Ajude-nos a ganhar!</h3>
                            <p class="text-xl text-red-100 max-w-2xl mx-auto">
                                Vote em {{ $company->legal_name }} nas categorias em que participamos e nos ajude a conquistar o prêmio Melhores do Ano 2025!
                            </p>
                        </div>
                        <a href="{{ route('vote.index') }}" class="inline-flex items-center space-x-3 bg-white text-red-600 px-8 py-4 rounded-xl text-lg font-bold hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-xl">
                            <i class="fas fa-vote-yea"></i>
                            <span>Ver Categorias e Votar</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Logomarca Melhores do Ano" class="h-12 mx-auto mb-4">
                </a>
                <p class="text-gray-600 text-sm">
                    © {{ date('Y') }} Melhores do Ano. Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>

    <script>
        const url = '{{ route('vote.company', $company) }}';
        const title = '{{ $company->legal_name }} - Vote em nós!';
        const text = 'Estamos participando do Melhores do Ano 2025! Vote em {{ $company->legal_name }} e nos ajude a ganhar!';

        function shareOnFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank');
        }

        function shareOnWhatsApp() {
            window.open(`https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`, '_blank');
        }

        function shareOnInstagram() {
            // Instagram não permite compartilhamento direto via web
            // Vamos copiar o link e abrir o Instagram
            const instagramText = `📢 ${text}\n\n${url}\n\n#MelhoresDoAno2025 #VoteEmNós`;

            // Tentar abrir o app do Instagram (mobile) ou copiar para área de transferência
            if (/iPhone|iPad|iPod|Android/i.test(navigator.userAgent)) {
                // Mobile: tentar abrir o app do Instagram
                const instagramUrl = `instagram://camera`;
                window.location.href = instagramUrl;

                // Fallback: copiar para área de transferência
                setTimeout(() => {
                    copyToClipboard(instagramText);
                    Swal.fire({
                        icon: 'info',
                        title: 'Link copiado!',
                        text: 'Cole no Instagram Stories ou Feed.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#dc2626',
                        timer: 3000,
                        timerProgressBar: true,
                    });
                }, 500);
            } else {
                // Desktop: copiar e instruir
                copyToClipboard(instagramText);
                Swal.fire({
                    icon: 'info',
                    title: 'Link copiado!',
                    text: 'Abra o Instagram e cole no seu Stories ou Feed.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626',
                    timer: 3000,
                    timerProgressBar: true,
                });
            }
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).catch(() => {
                // Fallback para navegadores antigos
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            });
        }

        function copyLink() {
            copyToClipboard(url);
            Swal.fire({
                icon: 'success',
                title: 'Link copiado!',
                text: 'O link foi copiado para a área de transferência.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc2626',
                timer: 3000,
                timerProgressBar: true,
            });
        }
    </script>
</body>
</html>
