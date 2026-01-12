<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Empresa - Melhores do Ano 2025</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .code-input {
            width: 60px;
            height: 70px;
            font-size: 32px;
            text-align: center;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-weight: bold;
        }
        .code-input:focus {
            border-color: #dc2626;
            outline: none;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
        .code-input.filled {
            border-color: #10b981;
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
                    <a href="{{ route('company.register') }}" class="text-gray-700 hover:text-red-600 font-medium">Cadastrar Empresa</a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-xl p-8 md:p-12">
            <div class="text-center mb-8">
                <div class="mb-6">
                    <i class="fas fa-building text-red-600 text-5xl mb-4"></i>
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Login de Empresa</h1>
                <p class="text-gray-600">Digite seu e-mail para receber o código de acesso</p>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Erro ao fazer login</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('code_sent'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('code_sent') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Step 1: Email -->
            <div id="step-email" class="{{ session('code_sent') ? 'hidden' : '' }}">
                <form method="POST" action="{{ route('company.login.send-code') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            E-mail da Empresa <span class="text-red-600">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('email') border-red-500 @enderror"
                               placeholder="empresa@exemplo.com"
                               required
                               autofocus>
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-4 rounded-xl text-lg font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Enviar Código
                    </button>
                </form>
            </div>

            <!-- Step 2: Código -->
            <div id="step-code" class="{{ session('code_sent') ? '' : 'hidden' }}">
                <form method="POST" action="{{ route('company.login.verify-code') }}" id="codeForm">
                    @csrf
                    <input type="hidden" name="email" id="code-email" value="{{ old('email') }}">

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-4 text-center">
                            Digite o código de 6 dígitos enviado para seu e-mail
                        </label>
                        <div class="flex justify-center gap-3 mb-4">
                            <input type="text" 
                                   id="code-1" 
                                   class="code-input" 
                                   maxlength="1" 
                                   pattern="[0-9]"
                                   inputmode="numeric"
                                   autocomplete="off">
                            <input type="text" 
                                   id="code-2" 
                                   class="code-input" 
                                   maxlength="1" 
                                   pattern="[0-9]"
                                   inputmode="numeric"
                                   autocomplete="off">
                            <input type="text" 
                                   id="code-3" 
                                   class="code-input" 
                                   maxlength="1" 
                                   pattern="[0-9]"
                                   inputmode="numeric"
                                   autocomplete="off">
                            <input type="text" 
                                   id="code-4" 
                                   class="code-input" 
                                   maxlength="1" 
                                   pattern="[0-9]"
                                   inputmode="numeric"
                                   autocomplete="off">
                            <input type="text" 
                                   id="code-5" 
                                   class="code-input" 
                                   maxlength="1" 
                                   pattern="[0-9]"
                                   inputmode="numeric"
                                   autocomplete="off">
                            <input type="text" 
                                   id="code-6" 
                                   class="code-input" 
                                   maxlength="1" 
                                   pattern="[0-9]"
                                   inputmode="numeric"
                                   autocomplete="off">
                        </div>
                        <input type="hidden" name="code" id="full-code">
                        @error('code')
                            <p class="text-red-600 text-sm text-center mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 text-center mt-4">
                            Não recebeu o código? 
                            <button type="button" onclick="resendCode()" class="text-red-600 hover:text-red-700 font-semibold underline">
                                Reenviar
                            </button>
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" 
                                onclick="backToEmail()"
                                class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-300 transition-all duration-300">
                            <i class="fas fa-arrow-left mr-2"></i> Voltar
                        </button>
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-lg font-bold hover:from-red-700 hover:to-red-800 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-check mr-2"></i> Verificar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Auto-focus e navegação entre campos de código
        const codeInputs = ['code-1', 'code-2', 'code-3', 'code-4', 'code-5', 'code-6'];
        
        codeInputs.forEach((id, index) => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function(e) {
                    if (e.target.value && index < codeInputs.length - 1) {
                        document.getElementById(codeInputs[index + 1]).focus();
                    }
                    updateFullCode();
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        document.getElementById(codeInputs[index - 1]).focus();
                    }
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasted = e.clipboardData.getData('text').replace(/\D/g, '').substring(0, 6);
                    pasted.split('').forEach((char, i) => {
                        if (codeInputs[i]) {
                            document.getElementById(codeInputs[i]).value = char;
                        }
                    });
                    updateFullCode();
                    if (pasted.length === 6) {
                        document.getElementById('code-6').focus();
                    }
                });
            }
        });

        function updateFullCode() {
            const code = codeInputs.map(id => {
                const input = document.getElementById(id);
                return input ? input.value : '';
            }).join('');
            
            document.getElementById('full-code').value = code;
            
            // Marcar campos preenchidos
            codeInputs.forEach(id => {
                const input = document.getElementById(id);
                if (input && input.value) {
                    input.classList.add('filled');
                } else {
                    input.classList.remove('filled');
                }
            });
        }

        function backToEmail() {
            document.getElementById('step-email').classList.remove('hidden');
            document.getElementById('step-code').classList.add('hidden');
        }

        function resendCode() {
            const email = document.getElementById('code-email').value;
            if (email) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('company.login.send-code') }}';
                
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                
                const emailInput = document.createElement('input');
                emailInput.type = 'hidden';
                emailInput.name = 'email';
                emailInput.value = email;
                form.appendChild(emailInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Auto-submit quando todos os campos estiverem preenchidos
        codeInputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', function() {
                    updateFullCode();
                    const code = document.getElementById('full-code').value;
                    if (code.length === 6) {
                        setTimeout(() => {
                            document.getElementById('codeForm').submit();
                        }, 300);
                    }
                });
            }
        });

        // Focar no primeiro campo quando a etapa de código aparecer
        @if(session('code_sent'))
            setTimeout(() => {
                document.getElementById('code-1')?.focus();
            }, 100);
        @endif
    </script>
</body>
</html>
