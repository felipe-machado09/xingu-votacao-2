<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Empresa - Melhores do Ano 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            min-height: 42px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #ef4444;
            border: none;
            color: white;
            padding: 4px 8px;
            border-radius: 0.375rem;
        }
        .error-field {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }
        .error-message {
            color: #ef4444;
        }
        #lgpd-toggle {
            cursor: pointer;
        }
        #lgpd-toggle:active {
            transform: scale(0.95);
        }
        #lgpd-thumb {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .step-indicator {
            transition: all 0.3s ease;
            background: #e5e7eb;
            color: #6b7280;
        }
        .step-indicator.active {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            transform: scale(1.1);
        }
        .step-indicator.completed {
            background: #10b981;
            color: white;
        }
        .step-content {
            display: none;
        }
        .step-content.active {
            display: block;
            animation: fadeIn 0.4s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-red-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('files/Logomarca Melhores do Ano 2025.webp') }}" alt="Logomarca Melhores do Ano" class="h-16">
                    </a>
                </div>
                <nav class="flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-red-600 font-medium">Início</a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-600 font-medium">Login</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Cadastro de Empresa</h1>
                <p class="text-gray-600">Preencha os dados abaixo para participar do Melhores do Ano 2025</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Por favor, corrija os seguintes erros:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step Indicators -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <div class="step-indicator active flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg shadow-md" data-step="1">
                            <span class="step-number">1</span>
                        </div>
                        <div class="flex-1 h-2 mx-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="step-progress h-full bg-red-600 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="flex items-center flex-1">
                        <div class="step-indicator flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg shadow-md" data-step="2">
                            <span class="step-number">2</span>
                        </div>
                        <div class="flex-1 h-2 mx-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="step-progress h-full bg-red-600 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="flex items-center flex-1">
                        <div class="step-indicator flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg shadow-md" data-step="3">
                            <span class="step-number">3</span>
                        </div>
                        <div class="flex-1 h-2 mx-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="step-progress h-full bg-red-600 rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                    <div class="step-indicator flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg shadow-md" data-step="4">
                        <span class="step-number">4</span>
                    </div>
                </div>
                <div class="flex justify-between mt-2 text-xs font-semibold text-gray-600">
                    <span class="step-label" data-step="1">Dados Básicos</span>
                    <span class="step-label" data-step="2">Endereço</span>
                    <span class="step-label" data-step="3">Logo & Categorias</span>
                    <span class="step-label" data-step="4">Finalizar</span>
                </div>
            </div>

            <form method="POST" action="{{ route('company.register.store') }}" enctype="multipart/form-data" id="companyRegisterForm">
                @csrf
                <input type="hidden" name="role_name" value="Empresa">

                <!-- Step 1: Dados Básicos -->
                <div class="step-content active" data-step="1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-building text-red-600 mr-3"></i>
                        Dados Básicos da Empresa
                    </h2>

                    <div class="space-y-6">
                        <!-- Nome -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Nome da Empresa <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('name') error-field @enderror"
                                   required>
                            @error('name')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CNPJ -->
                        <div>
                            <label for="cnpj" class="block text-sm font-semibold text-gray-700 mb-2">
                                CNPJ <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="cnpj" 
                                   name="cnpj" 
                                   value="{{ old('cnpj') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('cnpj') error-field @enderror"
                                   placeholder="00.000.000/0000-00"
                                   maxlength="18"
                                   required>
                            <p class="mt-1 text-xs text-gray-500">Exemplo válido para testes: 11.222.333/0001-81</p>
                            @error('cnpj')
                                <p class="error-message">
                                    {{ $message }}
                                    <a href="{{ route('company.login') }}" class="text-red-600 hover:text-red-700 underline font-semibold ml-1">Faça login aqui</a> se você já possui uma conta.
                                </p>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div>
                            <label for="telefone" class="block text-sm font-semibold text-gray-700 mb-2">
                                Telefone <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="telefone" 
                                   name="telefone" 
                                   value="{{ old('telefone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('telefone') error-field @enderror"
                                   placeholder="(00) 00000-0000"
                                   maxlength="15"
                                   required>
                            @error('telefone')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- WhatsApp -->
                        <div>
                            <label for="whatsapp_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                WhatsApp (Opcional)
                            </label>
                            <input type="text" 
                                   id="whatsapp_number" 
                                   name="whatsapp_number" 
                                   value="{{ old('whatsapp_number') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                   placeholder="(00) 00000-0000"
                                   maxlength="15">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                E-mail <span class="text-red-600">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('email') error-field @enderror"
                                   required>
                            <p class="mt-2 text-sm text-gray-500">Um código de acesso será enviado para este e-mail quando você fizer login.</p>
                            @error('email')
                                <p class="error-message">
                                    {{ $message }}
                                    <a href="{{ route('company.login') }}" class="text-red-600 hover:text-red-700 underline font-semibold ml-1">Faça login aqui</a> se você já possui uma conta.
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="button" onclick="nextStep()" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Próximo <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Endereço -->
                <div class="step-content" data-step="2">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-600 mr-3"></i>
                        Endereço da Empresa
                    </h2>

                    <div class="space-y-6">
                        <!-- CEP -->
                        <div>
                            <label for="address_zipcode" class="block text-sm font-semibold text-gray-700 mb-2">
                                CEP <span class="text-red-600">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="address_zipcode" 
                                       name="address_zipcode" 
                                       value="{{ old('address_zipcode') }}"
                                       class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('address_zipcode') error-field @enderror"
                                       placeholder="00000-000"
                                       maxlength="9"
                                       required>
                                <div id="cep-loading" class="absolute right-4 top-1/2 transform -translate-y-1/2 hidden">
                                    <i class="fas fa-spinner fa-spin text-red-600"></i>
                                </div>
                                <div id="cep-success" class="absolute right-4 top-1/2 transform -translate-y-1/2 hidden">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                                <div id="cep-error" class="absolute right-4 top-1/2 transform -translate-y-1/2 hidden">
                                    <i class="fas fa-exclamation-circle text-red-600"></i>
                                </div>
                            </div>
                            <p id="cep-message" class="mt-2 text-sm hidden"></p>
                            @error('address_zipcode')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rua -->
                        <div>
                            <label for="address_street" class="block text-sm font-semibold text-gray-700 mb-2">
                                Rua/Avenida <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="address_street" 
                                   name="address_street" 
                                   value="{{ old('address_street') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('address_street') error-field @enderror"
                                   required>
                            @error('address_street')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-3 gap-4">
                            <!-- Número -->
                            <div>
                                <label for="address_number" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Número <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       id="address_number" 
                                       name="address_number" 
                                       value="{{ old('address_number') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('address_number') error-field @enderror"
                                       required>
                                @error('address_number')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Complemento -->
                            <div class="md:col-span-2">
                                <label for="address_complement" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Complemento
                                </label>
                                <input type="text" 
                                       id="address_complement" 
                                       name="address_complement" 
                                       value="{{ old('address_complement') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                       placeholder="Apto, Bloco, etc.">
                            </div>
                        </div>

                        <!-- Bairro -->
                        <div>
                            <label for="address_neighborhood" class="block text-sm font-semibold text-gray-700 mb-2">
                                Bairro <span class="text-red-600">*</span>
                            </label>
                            <input type="text" 
                                   id="address_neighborhood" 
                                   name="address_neighborhood" 
                                   value="{{ old('address_neighborhood') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('address_neighborhood') error-field @enderror"
                                   required>
                            @error('address_neighborhood')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Cidade -->
                            <div>
                                <label for="address_city" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Cidade <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       id="address_city" 
                                       name="address_city" 
                                       value="{{ old('address_city', 'Altamira') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('address_city') error-field @enderror"
                                       required>
                                @error('address_city')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="address_state" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Estado <span class="text-red-600">*</span>
                                </label>
                                <input type="text" 
                                       id="address_state" 
                                       name="address_state" 
                                       value="{{ old('address_state', 'PA') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('address_state') error-field @enderror"
                                       placeholder="PA"
                                       maxlength="2"
                                       required>
                                @error('address_state')
                                    <p class="error-message">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" onclick="prevStep()" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-bold hover:bg-gray-300 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Voltar
                        </button>
                        <button type="button" onclick="nextStep()" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Próximo <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Logo e Categorias -->
                <div class="step-content" data-step="3">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-images text-red-600 mr-3"></i>
                        Logo e Categorias
                    </h2>

                    <div class="space-y-6">
                        <!-- Logo -->
                        <div>
                            <label for="logo_path" class="block text-sm font-semibold text-gray-700 mb-2">
                                Logo da Empresa
                            </label>
                            <input type="file" 
                                   id="logo_path" 
                                   name="logo_path" 
                                   accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('logo_path') error-field @enderror">
                            <p class="mt-2 text-sm text-gray-500">Formatos aceitos: JPEG, PNG, JPG, GIF. Tamanho máximo: 2MB</p>
                            @error('logo_path')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Categorias -->
                        <div>
                            <label for="categoria_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                Categoria de Votação <span class="text-red-600">*</span>
                            </label>
                            <select id="categoria_id" 
                                    name="categoria_id[]" 
                                    multiple
                                    class="w-full @error('categoria_id') error-field @enderror"
                                    required>
                                @foreach($categories as $group => $groupCategories)
                                    <optgroup label="{{ $group ?? 'Outras' }}">
                                        @foreach($groupCategories as $category)
                                            <option value="{{ $category->id }}" {{ in_array($category->id, old('categoria_id', [])) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <p class="error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" onclick="prevStep()" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-bold hover:bg-gray-300 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Voltar
                        </button>
                        <button type="button" onclick="nextStep()" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Próximo <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: LGPD e Finalização -->
                <div class="step-content" data-step="4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-shield-alt text-red-600 mr-3"></i>
                        Termos e Finalização
                    </h2>

                    <div class="space-y-6">
                        <!-- LGPD -->
                        <div class="bg-gray-50 border-2 border-gray-200 rounded-xl p-6">
                            <div class="flex items-center justify-between">
                                <label for="lgpd" class="flex-1 text-sm text-gray-700 cursor-pointer" onclick="toggleLGPD()">
                                    Aceito os <a href="https://melhores.valedoxingu.com.br/documents/LGPD_Melhores.pdf" target="_blank" class="text-red-600 hover:text-red-700 underline font-semibold" onclick="event.stopPropagation()">termos de privacidade e proteção de dados (LGPD)</a> <span class="text-red-600">*</span>
                                </label>
                                <!-- Toggle Button -->
                                <div class="ml-4">
                                    <input type="checkbox" 
                                           id="lgpd" 
                                           name="lgpd" 
                                           value="1"
                                           class="hidden"
                                           required>
                                    <button type="button" 
                                            id="lgpd-toggle"
                                            onclick="toggleLGPD()"
                                            class="relative inline-flex h-7 w-14 items-center rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 bg-gray-300 @error('lgpd') border-2 border-red-500 @enderror">
                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform duration-300 translate-x-1" id="lgpd-thumb"></span>
                                    </button>
                                </div>
                            </div>
                            @error('lgpd')
                                <p class="error-message mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Resumo -->
                        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
                            <h3 class="font-bold text-gray-900 mb-4">Resumo do Cadastro</h3>
                            <div class="space-y-2 text-sm text-gray-700">
                                <p><strong>Empresa:</strong> <span id="summary-name">-</span></p>
                                <p><strong>CNPJ:</strong> <span id="summary-cnpj">-</span></p>
                                <p><strong>E-mail:</strong> <span id="summary-email">-</span></p>
                                <p><strong>Endereço:</strong> <span id="summary-address">-</span></p>
                                <p><strong>Categorias:</strong> <span id="summary-categories">-</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <button type="button" onclick="prevStep()" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg font-bold hover:bg-gray-300 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Voltar
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-check-circle mr-2"></i> Finalizar Cadastro
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        let currentStep = 1;
        const totalSteps = 4;

        // Validação e máscara de CNPJ
        const cnpjInput = document.getElementById('cnpj');
        let cnpjValidationTimeout;
        
        cnpjInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, '$1.$2.$3/$4-$5');
                e.target.value = value;
            }
            
            // Limpar timeout anterior
            clearTimeout(cnpjValidationTimeout);
            
            // Remover mensagens de erro ao digitar
            clearFieldError(e.target);
            
            // Validar apenas quando o usuário parar de digitar (debounce)
            cnpjValidationTimeout = setTimeout(() => {
                if (e.target.value.replace(/\D/g, '').length === 14) {
                    validateCNPJ(e.target);
                }
            }, 500);
        });
        
        cnpjInput.addEventListener('blur', function(e) {
            clearTimeout(cnpjValidationTimeout);
            validateCNPJ(e.target);
        });
        
        function validateCNPJ(input) {
            const cnpj = input.value.replace(/\D/g, '');
            const parent = input.parentElement;
            
            // Não validar se já houver erro do Laravel
            const laravelError = parent.querySelector('.error-message:not(.dynamic-error)');
            if (laravelError) {
                return;
            }
            
            // Limpar erros anteriores
            clearFieldError(input);
            input.classList.remove('error-field');
            
            if (cnpj.length === 0) {
                return;
            }
            
            if (cnpj.length !== 14) {
                showFieldError(input, 'O CNPJ deve ter 14 dígitos.', 'cnpj-error');
                return;
            }
            
            // Verificar se todos os dígitos são iguais
            if (/^(\d)\1{13}$/.test(cnpj)) {
                showFieldError(input, 'O CNPJ informado é inválido.', 'cnpj-error');
                return;
            }
            
            // Validar dígitos verificadores
            if (!isValidCNPJ(cnpj)) {
                showFieldError(input, 'O CNPJ informado é inválido. Verifique os dígitos verificadores.', 'cnpj-error');
            }
        }
        
        function clearFieldError(input) {
            // Remover apenas mensagens de erro dinâmicas (JavaScript), não as do Laravel
            const parent = input.parentElement;
            const errorMessages = parent.querySelectorAll('.error-message.dynamic-error');
            errorMessages.forEach(msg => msg.remove());
            
            // Remover classe de erro apenas se não houver erro do Laravel
            const laravelError = parent.querySelector('.error-message:not(.dynamic-error)');
            if (!laravelError) {
                input.classList.remove('error-field');
            }
        }
        
        function isValidCNPJ(cnpj) {
            // Calcular primeiro dígito verificador
            let length = 12;
            let sum = 0;
            let pos = 5;
            
            for (let i = 0; i < length; i++) {
                sum += parseInt(cnpj[i]) * pos;
                pos = (pos == 2) ? 9 : pos - 1;
            }
            
            let result = sum % 11;
            let digit1 = (result < 2) ? 0 : 11 - result;
            
            if (parseInt(cnpj[12]) != digit1) {
                return false;
            }
            
            // Calcular segundo dígito verificador
            length = 13;
            sum = 0;
            pos = 6;
            
            for (let i = 0; i < length; i++) {
                sum += parseInt(cnpj[i]) * pos;
                pos = (pos == 2) ? 9 : pos - 1;
            }
            
            result = sum % 11;
            let digit2 = (result < 2) ? 0 : 11 - result;
            
            return parseInt(cnpj[13]) == digit2;
        }
        
        function showFieldError(input, message, errorClass = '') {
            // Garantir que não há mensagens anteriores
            clearFieldError(input);
            
            input.classList.add('error-field');
            const errorDiv = document.createElement('p');
            errorDiv.className = 'error-message dynamic-error mt-1 ' + errorClass;
            errorDiv.textContent = message;
            input.parentElement.appendChild(errorDiv);
        }

        function maskPhone(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length <= 10) {
                    value = value.replace(/^(\d{2})(\d{4})(\d{4})$/, '($1) $2-$3');
                } else {
                    value = value.replace(/^(\d{2})(\d{5})(\d{4})$/, '($1) $2-$3');
                }
                input.value = value;
            }
        }
        
        function validatePhone(input) {
            const phone = input.value.replace(/\D/g, '');
            const parent = input.parentElement;
            
            // Não validar se já houver erro do Laravel
            const laravelError = parent.querySelector('.error-message:not(.dynamic-error)');
            if (laravelError) {
                return;
            }
            
            // Limpar erros anteriores
            clearFieldError(input);
            input.classList.remove('error-field');
            
            if (phone.length === 0) {
                return;
            }
            
            if (phone.length !== 11) {
                showFieldError(input, 'O telefone deve ter 11 dígitos (DDD + número com 9 dígitos).', 'phone-error');
                return;
            }
            
            // Verificar se todos os dígitos são iguais
            if (/^(\d)\1{10}$/.test(phone)) {
                showFieldError(input, 'O telefone informado é inválido.', 'phone-error');
                return;
            }
            
            // Verificar DDD
            const ddd = parseInt(phone.substring(0, 2));
            if (ddd < 11 || ddd > 99) {
                showFieldError(input, 'O DDD informado é inválido.', 'phone-error');
                return;
            }
            
            // Verificar primeiro dígito do número (deve ser entre 2 e 9)
            const firstDigit = parseInt(phone[2]);
            if (firstDigit < 2 || firstDigit > 9) {
                showFieldError(input, 'O número de telefone informado é inválido.', 'phone-error');
            }
        }

        const telefoneInput = document.getElementById('telefone');
        let telefoneValidationTimeout;
        
        telefoneInput.addEventListener('input', function(e) {
            maskPhone(e.target);
            
            // Limpar timeout anterior
            clearTimeout(telefoneValidationTimeout);
            
            // Remover mensagens de erro ao digitar
            clearFieldError(e.target);
            
            // Validar apenas quando o usuário parar de digitar (debounce)
            telefoneValidationTimeout = setTimeout(() => {
                if (e.target.value.replace(/\D/g, '').length === 11) {
                    validatePhone(e.target);
                }
            }, 500);
        });
        
        telefoneInput.addEventListener('blur', function(e) {
            clearTimeout(telefoneValidationTimeout);
            validatePhone(e.target);
        });

        const whatsappInput = document.getElementById('whatsapp_number');
        let whatsappValidationTimeout;
        
        if (whatsappInput) {
            whatsappInput.addEventListener('input', function(e) {
                maskPhone(e.target);
                
                // Limpar timeout anterior
                clearTimeout(whatsappValidationTimeout);
                
                // Remover mensagens de erro ao digitar
                clearFieldError(e.target);
                
                // Validar apenas quando o usuário parar de digitar (debounce)
                if (e.target.value.length > 0) {
                    whatsappValidationTimeout = setTimeout(() => {
                        if (e.target.value.replace(/\D/g, '').length === 11) {
                            validatePhone(e.target);
                        }
                    }, 500);
                }
            });
            
            whatsappInput.addEventListener('blur', function(e) {
                clearTimeout(whatsappValidationTimeout);
                if (e.target.value.length > 0) {
                    validatePhone(e.target);
                }
            });
        }

        // Máscara de CEP
        const cepInput = document.getElementById('address_zipcode');
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                value = value.replace(/^(\d{5})(\d{3})$/, '$1-$2');
                e.target.value = value;
            }
            
            // Limpar estados quando o usuário editar
            if (value.length < 8) {
                document.getElementById('cep-loading').classList.add('hidden');
                document.getElementById('cep-success').classList.add('hidden');
                document.getElementById('cep-error').classList.add('hidden');
                document.getElementById('cep-message').classList.add('hidden');
            }
        });

        // Integração com ViaCEP
        cepInput.addEventListener('blur', function(e) {
            const cep = e.target.value.replace(/\D/g, '');
            
            // Só busca se tiver 8 dígitos
            if (cep.length === 8) {
                buscarCEP(cep);
            } else if (cep.length > 0) {
                mostrarErroCEP('CEP deve ter 8 dígitos');
            }
        });

        function buscarCEP(cep) {
            const loading = document.getElementById('cep-loading');
            const success = document.getElementById('cep-success');
            const error = document.getElementById('cep-error');
            const message = document.getElementById('cep-message');
            
            // Limpar estados anteriores
            loading.classList.remove('hidden');
            success.classList.add('hidden');
            error.classList.add('hidden');
            message.classList.add('hidden');
            
            // Fazer requisição ao ViaCEP
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    loading.classList.add('hidden');
                    
                    if (data.erro) {
                        mostrarErroCEP('CEP não encontrado. Por favor, preencha os dados manualmente.');
                        return;
                    }
                    
                    // Preencher campos automaticamente
                    document.getElementById('address_street').value = data.logradouro || '';
                    document.getElementById('address_neighborhood').value = data.bairro || '';
                    document.getElementById('address_city').value = data.localidade || '';
                    document.getElementById('address_state').value = data.uf || '';
                    
                    // Mostrar sucesso
                    success.classList.remove('hidden');
                    message.classList.remove('hidden');
                    message.classList.remove('text-red-600');
                    message.classList.add('text-green-600');
                    message.textContent = '✓ Endereço preenchido automaticamente! Você pode editar se necessário.';
                    
                    // Remover mensagem após 5 segundos
                    setTimeout(() => {
                        message.classList.add('hidden');
                    }, 5000);
                    
                    // Focar no campo número
                    document.getElementById('address_number').focus();
                })
                .catch(err => {
                    loading.classList.add('hidden');
                    mostrarErroCEP('Erro ao buscar CEP. Por favor, preencha os dados manualmente.');
                    console.error('Erro ao buscar CEP:', err);
                });
        }

        function mostrarErroCEP(mensagem) {
            const success = document.getElementById('cep-success');
            const error = document.getElementById('cep-error');
            const message = document.getElementById('cep-message');
            
            success.classList.add('hidden');
            error.classList.remove('hidden');
            message.classList.remove('hidden');
            message.classList.remove('text-green-600');
            message.classList.add('text-red-600');
            message.textContent = '⚠ ' + mensagem;
            
            // Remover mensagem após 5 segundos
            setTimeout(() => {
                message.classList.add('hidden');
            }, 5000);
        }


        // Select2
        $(document).ready(function() {
            $('#categoria_id').select2({
                placeholder: 'Selecione uma ou mais categorias',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Nenhuma categoria encontrada";
                    }
                }
            });
        });

        // Step Navigation
        function updateStepIndicators() {
            document.querySelectorAll('.step-indicator').forEach((indicator, index) => {
                const stepNum = index + 1;
                // Remove todas as classes de estado
                indicator.classList.remove('active', 'completed', 'bg-gray-200', 'bg-red-600', 'bg-green-500');
                indicator.style.background = '';
                
                if (stepNum < currentStep) {
                    indicator.classList.add('completed');
                    indicator.style.background = '#10b981';
                    indicator.innerHTML = '<i class="fas fa-check text-white"></i>';
                } else if (stepNum === currentStep) {
                    indicator.classList.add('active');
                    indicator.style.background = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
                    indicator.innerHTML = `<span class="step-number text-white">${stepNum}</span>`;
                } else {
                    indicator.style.background = '#e5e7eb';
                    indicator.innerHTML = `<span class="step-number text-gray-600">${stepNum}</span>`;
                }
            });

            // Update progress bar
            document.querySelectorAll('.step-progress').forEach((bar, index) => {
                if (index < currentStep - 1) {
                    bar.style.width = '100%';
                } else {
                    bar.style.width = '0%';
                }
            });
        }

        function showStep(step) {
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });
            
            const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
            if (stepContent) {
                stepContent.classList.add('active');
            }

            updateStepIndicators();
            updateSummary();
        }

        function nextStep() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function validateStep(step) {
            let isValid = true;
            const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
            
            if (!stepContent) return true;

            const requiredFields = stepContent.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('error-field');
                    isValid = false;
                } else {
                    field.classList.remove('error-field');
                }
            });


            if (step === 3) {
                const categories = $('#categoria_id').val();
                if (!categories || categories.length === 0) {
                    $('#categoria_id').addClass('error-field');
                    isValid = false;
                }
            }

            if (step === 4) {
                const lgpd = document.getElementById('lgpd').checked;
                const lgpdToggle = document.getElementById('lgpd-toggle');
                if (!lgpd) {
                    lgpdToggle.classList.add('border-2', 'border-red-500');
                    isValid = false;
                } else {
                    lgpdToggle.classList.remove('border-2', 'border-red-500');
                }
            }

            return isValid;
        }

        function updateSummary() {
            if (currentStep === 4) {
                document.getElementById('summary-name').textContent = document.getElementById('name').value || '-';
                document.getElementById('summary-cnpj').textContent = document.getElementById('cnpj').value || '-';
                document.getElementById('summary-email').textContent = document.getElementById('email').value || '-';
                
                const street = document.getElementById('address_street').value || '';
                const number = document.getElementById('address_number').value || '';
                const neighborhood = document.getElementById('address_neighborhood').value || '';
                const city = document.getElementById('address_city').value || '';
                const state = document.getElementById('address_state').value || '';
                document.getElementById('summary-address').textContent = 
                    `${street}, ${number} - ${neighborhood}, ${city}/${state}` || '-';
                
                const selectedCategories = $('#categoria_id').select2('data');
                const categoryNames = selectedCategories.map(cat => cat.text).join(', ');
                document.getElementById('summary-categories').textContent = categoryNames || '-';
            }
        }

        // Toggle LGPD
        function toggleLGPD() {
            const checkbox = document.getElementById('lgpd');
            const toggle = document.getElementById('lgpd-toggle');
            const thumb = document.getElementById('lgpd-thumb');
            
            checkbox.checked = !checkbox.checked;
            
            if (checkbox.checked) {
                toggle.classList.remove('bg-gray-300');
                toggle.classList.add('bg-red-600');
                thumb.classList.remove('translate-x-1');
                thumb.classList.add('translate-x-7');
            } else {
                toggle.classList.remove('bg-red-600');
                toggle.classList.add('bg-gray-300');
                thumb.classList.remove('translate-x-7');
                thumb.classList.add('translate-x-1');
            }
            
            // Remover erro se houver
            toggle.classList.remove('border-2', 'border-red-500');
        }

        // Inicializar estado do toggle
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('lgpd');
            if (checkbox && checkbox.checked) {
                toggleLGPD();
            }
        });


        // Initialize
        updateStepIndicators();
    </script>
</body>
</html>
