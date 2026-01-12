@extends('layouts.app')

@section('title', 'Início - Plataforma de Votação')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Bem-vindo à Plataforma de Votação</h1>
        <p class="text-xl text-gray-600">Vote nas suas empresas favoritas em cada categoria</p>
    </div>

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
