@extends('layouts.app')

@section('title', 'Cadastro de Empresa - Melhores do Ano '.\App\Models\LandingPageSection::campaignYear())

@section('content')
<div class="max-w-2xl mx-auto px-4">
    <div class="text-center mb-8">
        <img src="{{ \App\Models\LandingPageSection::logoUrl() }}" alt="Melhores do Ano" class="h-20 sm:h-24 mx-auto mb-4">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Cadastro de Empresa</h1>
        <p class="text-gray-600">Cadastre sua empresa e escolha as categorias em que deseja participar. Os campos com * são obrigatórios.</p>
    </div>
    <form method="POST" action="{{ route('company.register.store') }}" enctype="multipart/form-data" class="bg-white p-5 sm:p-8 rounded-2xl shadow space-y-6">
        @csrf
        <div>
            <label for="trade_name" class="block font-medium text-gray-700 mb-2">Nome fantasia *</label>
            <input id="trade_name" name="trade_name" value="{{ old('trade_name') }}" autocomplete="organization" required maxlength="255" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500">
            @error('trade_name')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="cnpj" class="block font-medium text-gray-700 mb-2">CNPJ *</label>
            <input id="cnpj" name="cnpj" value="{{ old('cnpj') }}" inputmode="numeric" required maxlength="18" placeholder="00.000.000/0000-00" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500">
            @error('cnpj')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="whatsapp_number" class="block font-medium text-gray-700 mb-2">WhatsApp *</label>
            <input id="whatsapp_number" name="whatsapp_number" type="tel" autocomplete="tel" value="{{ old('whatsapp_number') }}" required placeholder="(93) 99999-9999" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500">
            @error('whatsapp_number')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="logo_path" class="block font-medium text-gray-700 mb-2">Logomarca *</label>
            <input id="logo_path" name="logo_path" type="file" accept="image/jpeg,image/png,image/webp" required aria-describedby="logo-help" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-red-50 file:px-4 file:py-3 file:font-medium file:text-red-700">
            <p id="logo-help" class="text-sm text-gray-500 mt-2">JPG, PNG ou WebP, até 2 MB.</p>
            @error('logo_path')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
        </div>
        <fieldset>
            <legend class="font-medium text-gray-700 mb-2">Categorias *</legend>
            <p class="text-sm text-gray-500 mb-3">Marque uma ou mais categorias. Você pode selecionar opções de grupos diferentes.</p>
            <div class="max-h-72 overflow-y-auto border border-gray-300 rounded-lg p-4 space-y-4">
                @foreach($categories as $group => $items)
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-2">{{ $group }}</p>
                        <div class="space-y-1">
                            @foreach($items as $category)
                                <label class="flex items-start gap-3 rounded-lg px-2 py-3 hover:bg-red-50 cursor-pointer">
                                    <input type="checkbox" name="categoria_id[]" value="{{ $category->id }}" @checked(in_array($category->id, old('categoria_id', []))) class="mt-1 h-4 w-4 shrink-0 accent-red-600">
                                    <span class="text-gray-700">{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @error('categoria_id')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
            @error('categoria_id.*')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
        </fieldset>
        <div>
            <label for="instagram_url" class="block font-medium text-gray-700 mb-2">Link do Instagram <span class="text-sm font-normal text-gray-500">(opcional)</span></label>
            <input id="instagram_url" name="instagram_url" type="url" value="{{ old('instagram_url') }}" maxlength="255" placeholder="https://www.instagram.com/suaempresa" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-red-500 focus:border-red-500">
            @error('instagram_url')<p class="text-sm text-red-600 mt-2">{{ $message }}</p>@enderror
        </div>
        <label class="flex gap-3 items-start text-sm text-gray-600">
            <input type="checkbox" name="lgpd" value="1" required @checked(old('lgpd')) class="mt-1 h-4 w-4 shrink-0 accent-red-600">
            <span>Autorizo o uso destes dados para o cadastro e a participação da empresa na votação. *</span>
        </label>
        @error('lgpd')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
        <div class="flex flex-col sm:flex-row sm:items-center gap-4 pt-2">
            <button type="submit" class="bg-red-600 hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 text-white font-bold rounded-lg px-6 py-3">Cadastrar empresa</button>
            <a href="{{ route('home') }}" class="text-center text-gray-600 hover:text-red-600 py-3">Voltar ao início</a>
        </div>
    </form>
</div>
@endsection
