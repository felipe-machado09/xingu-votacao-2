@extends('layouts.app')

@section('title', 'Início - Plataforma de Votação')

@push('head')
    <link rel="icon" type="image/webp" href="{{ asset('img/logo.webp') }}">
    <link rel="shortcut icon" type="image/webp" href="{{ asset('img/logo.webp') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.webp') }}">
@endpush

@section('countdown')
    <x-countdown />
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Bem-vindo à Plataforma de Votação</h1>
        <p class="text-xl text-gray-600">Vote nas suas empresas favoritas em cada categoria</p>
    </div>

    <!-- Seção de Prêmios -->
    @if($awards && $awards->count() > 0)
    <div class="mb-12">
        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3">🎁 Prêmios para Participantes</h2>
                <p class="text-lg text-gray-700">Vote em no mínimo 5 empresas e concorra aos prêmios!</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($awards as $award)
                    @if($award->is_active && $award->hasRemainingQuantity())
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        @if($award->image_path)
                            <div class="mb-4 h-40 flex items-center justify-center bg-gray-50 rounded-lg">
                                <img src="{{ asset('storage/' . $award->image_path) }}" alt="{{ $award->name }}" class="max-h-full max-w-full object-contain">
                            </div>
                        @else
                            <div class="mb-4 h-40 flex items-center justify-center bg-gradient-to-br from-yellow-100 to-orange-100 rounded-lg">
                                <span class="text-6xl">🎁</span>
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $award->name }}</h3>
                        @if($award->description)
                            <p class="text-gray-600 text-sm mb-3">{{ $award->description }}</p>
                        @endif
                        <div class="flex items-center justify-between text-sm">
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-semibold">
                                {{ $award->remainingQuantity() }} disponíveis
                            </span>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            
            <div class="mt-8 bg-yellow-100 border-l-4 border-yellow-500 p-6 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-600 mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-yellow-900 font-bold mb-1">Como participar do sorteio:</p>
                        <ul class="text-yellow-800 space-y-1 text-sm">
                            <li>✓ Vote em no mínimo 5 empresas diferentes</li>
                            <li>✓ Você será automaticamente elegível ao sorteio</li>
                            <li>✓ Quanto mais votos, maior sua chance!</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($categories->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-600 text-lg">Nenhuma categoria disponível para votação no momento.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($categories as $category)
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $category->name }}</h3>
                    @if($category->description)
                        <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                    @endif
                    <p class="text-sm text-gray-500 mb-4">{{ $category->companies->count() }} empresas participando</p>
                    <a href="{{ route('vote.show', $category) }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                        Ver e Votar
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    @if(!session('audience_id'))
        <div class="mt-12 text-center bg-blue-50 rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Pronto para votar?</h2>
            <p class="text-gray-600 mb-6">Cadastre-se ou faça login para votar</p>
            <div class="space-x-4">
                <a href="{{ route('register') }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    Cadastrar Agora
                </a>
                <a href="{{ route('login') }}" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
                    Entrar
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
