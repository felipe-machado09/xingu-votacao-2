<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sorteio - Melhores do Vale do Xingu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0a0e1a;
            color: #e2e8f0;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }

        ::-webkit-scrollbar { display: none; }

        /* ===== PARTICLES ===== */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            opacity: 0;
            animation: floatParticle linear infinite;
        }
        @keyframes floatParticle {
            0% { opacity: 0; transform: translateY(100vh) scale(0); }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateY(-10vh) scale(1); }
        }

        /* ===== SLOT MACHINE REEL ===== */
        .slot-container {
            overflow: hidden;
            height: 80px;
            position: relative;
        }
        .slot-reel {
            transition: transform 0.1s ease-out;
        }
        .slot-item {
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.5);
            white-space: nowrap;
        }
        .slot-item.active {
            color: #fff;
            text-shadow: 0 0 30px rgba(99, 102, 241, 0.8);
        }

        /* ===== GLOW BUTTON ===== */
        .btn-draw {
            position: relative;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7);
            border: none;
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
            padding: 20px 60px;
            border-radius: 16px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            overflow: hidden;
        }
        .btn-draw::before {
            content: '';
            position: absolute;
            inset: -3px;
            border-radius: 18px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7, #6366f1);
            background-size: 300% 300%;
            animation: gradientShift 3s ease infinite;
            z-index: -1;
            filter: blur(15px);
            opacity: 0.7;
        }
        .btn-draw:hover {
            transform: scale(1.05);
            box-shadow: 0 0 50px rgba(99, 102, 241, 0.5);
        }
        .btn-draw:active {
            transform: scale(0.98);
        }
        .btn-draw:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ===== PULSE RING ===== */
        @keyframes pulseRing {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2.5); opacity: 0; }
        }
        .pulse-ring {
            position: absolute;
            border: 3px solid rgba(99, 102, 241, 0.6);
            border-radius: 50%;
            width: 200px;
            height: 200px;
        }

        /* ===== SPINNING CIRCLE ===== */
        @keyframes spinCircle {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spin-circle {
            animation: spinCircle 1s linear infinite;
        }

        /* ===== CONFETTI ===== */
        @keyframes confettiFall {
            0% { transform: translateY(-10vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(110vh) rotate(720deg); opacity: 0; }
        }
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            top: -10px;
            z-index: 100;
            animation: confettiFall linear forwards;
        }

        /* ===== WINNER REVEAL ===== */
        @keyframes revealScale {
            0% { transform: scale(0) rotate(-10deg); opacity: 0; }
            50% { transform: scale(1.1) rotate(2deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .reveal-anim {
            animation: revealScale 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        /* ===== SHIMMER ===== */
        @keyframes shimmer {
            0% { background-position: -200% center; }
            100% { background-position: 200% center; }
        }
        .shimmer-text {
            background: linear-gradient(90deg, #e2e8f0, #fbbf24, #e2e8f0);
            background-size: 200% auto;
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
        }

        /* ===== AWARD CARD ===== */
        .award-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(99, 102, 241, 0.2);
            border-radius: 16px;
            padding: 24px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .award-card:hover {
            border-color: rgba(99, 102, 241, 0.5);
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.15);
        }
        .award-card.selected {
            border-color: #6366f1;
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.3);
        }

        /* ===== TIER BADGES ===== */
        .tier-1 { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .tier-2 { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .tier-3 { background: linear-gradient(135deg, #10b981, #059669); }

        /* ===== DRAWING ANIMATION OVERLAY ===== */
        .drawing-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 14, 26, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            backdrop-filter: blur(10px);
        }

        /* ===== SPINNING BORDER ===== */
        @keyframes rotateBorder {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .rotating-border {
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: conic-gradient(#6366f1, #8b5cf6, #a855f7, #ec4899, #6366f1);
            animation: rotateBorder 1.5s linear infinite;
        }

        /* ===== WINNER CARD ===== */
        .winner-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 2px solid rgba(99, 102, 241, 0.4);
            border-radius: 24px;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }
        .winner-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.15), transparent 70%);
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>

    <div class="relative z-10 h-screen flex flex-col" id="app">

        <!-- ===== HEADER MINIMO ===== -->
        <div class="flex items-center justify-end px-8 py-4">
            <div class="text-right">
                <div class="text-gray-400" style="font-size: 25px;" id="clock"></div>
            </div>
        </div>

        <!-- ===== CONTEÚDO PRINCIPAL ===== -->
        <div class="flex-1 flex flex-col items-center justify-center px-8" id="main-content">

            <!-- ESTADO 1: Seleção do Prêmio -->
            <div id="state-select" class="w-full max-w-6xl">
                <div class="text-center mb-6">
                    <img src="{{ asset('img/logo-melhores-do-ano-branca.png') }}" alt="Melhores do Ano" style="height: 200px; filter: drop-shadow(0 0 20px rgba(99,102,241,0.4));" class="mx-auto">
                </div>
                <h1 class="text-4xl font-extrabold text-center mb-2 shimmer-text">SORTEIO DE PRÊMIOS</h1>
                <p class="text-gray-400 text-center mb-10 text-lg">Selecione o prêmio e clique em sortear</p>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10" id="awards-grid">
                    @foreach($awards as $award)
                    <div class="award-card" data-award-id="{{ $award->id }}" onclick="selectAward(this, {{ $award->id }}, '{{ addslashes($award->name) }}', '{{ $award->image_path ? asset('storage/' . $award->image_path) : '' }}', {{ $award->tier }}, {{ $award->remainingQuantity() }})">
                        <div class="flex items-start gap-4">
                            @if($award->image_path)
                            <img src="{{ asset('storage/' . $award->image_path) }}" alt="{{ $award->name }}" class="w-20 h-20 rounded-xl object-cover flex-shrink-0">
                            @else
                            <div class="w-20 h-20 rounded-xl flex items-center justify-center flex-shrink-0 tier-{{ $award->tier }}">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-white mb-1">{{ $award->name }}</h3>
                                <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold text-white tier-{{ $award->tier }}">
                                    {{ $award->tier == 1 ? 'Básico' : ($award->tier == 2 ? 'Intermediário' : 'Máximo') }}
                                </span>
                                <p class="text-gray-400 text-sm mt-2">Mín. {{ $award->min_votes }} votos</p>
                                <p class="text-gray-500 text-sm">Restantes: <span class="text-indigo-400 font-bold">{{ $award->remainingQuantity() }}</span></p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    <button class="btn-draw" id="btn-sortear" disabled onclick="startDraw()">
                        <span id="btn-text">SELECIONE UM PRÊMIO</span>
                    </button>
                </div>
            </div>

            <!-- ESTADO 2: Animação do Sorteio (overlay) -->
            <div id="state-drawing" class="drawing-overlay" style="display:none;">
                <div class="text-center">
                    <!-- Spinning circle -->
                    <div class="relative mx-auto mb-8" style="width: 200px; height: 200px;">
                        <div class="rotating-border"></div>
                        <div class="absolute inset-1 rounded-full bg-gray-900 flex items-center justify-center">
                            <div id="drawing-award-img-container">
                                <svg class="w-20 h-20 text-indigo-400 spin-circle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                </svg>
                            </div>
                        </div>
                        <!-- Pulse rings -->
                        <div class="pulse-ring absolute" style="top: 50%; left: 50%; margin-top: -100px; margin-left: -100px; animation: pulseRing 1.5s ease-out infinite;"></div>
                        <div class="pulse-ring absolute" style="top: 50%; left: 50%; margin-top: -100px; margin-left: -100px; animation: pulseRing 1.5s ease-out 0.5s infinite;"></div>
                        <div class="pulse-ring absolute" style="top: 50%; left: 50%; margin-top: -100px; margin-left: -100px; animation: pulseRing 1.5s ease-out 1s infinite;"></div>
                    </div>

                    <h2 class="text-3xl font-extrabold text-white mb-4">SORTEANDO...</h2>
                    <p class="text-gray-400 text-lg mb-6" id="drawing-award-name"></p>

                    <!-- Slot machine de nomes -->
                    <div class="slot-container mx-auto rounded-xl bg-gray-900/80 border border-indigo-500/30" style="max-width: 500px;">
                        <div class="slot-reel" id="slot-reel"></div>
                    </div>

                    <!-- Progress bar -->
                    <div class="mt-8 mx-auto rounded-full overflow-hidden bg-gray-800" style="max-width: 400px; height: 6px;">
                        <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-full transition-all duration-100" id="progress-bar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- ESTADO 3: Resultado do Sorteio -->
            <div id="state-result" style="display:none;" class="w-full max-w-3xl">
                <!-- Logo acima do card -->
                <div class="text-center mb-8">
                    <img src="{{ asset('img/logo-melhores-do-ano-branca.png') }}" alt="Melhores do Ano" style="height: 100px; filter: drop-shadow(0 0 20px rgba(99,102,241,0.4));" class="mx-auto">
                </div>
                <div class="winner-card reveal-anim" id="winner-card">
                    <div class="text-center relative z-10">
                        <!-- Trophy / Award image -->
                        <div class="mb-6" id="result-award-img-container">
                            <div class="mx-auto w-24 h-24 rounded-full bg-gradient-to-br from-yellow-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                                <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>

                        <p class="text-indigo-400 text-sm font-semibold tracking-widest uppercase mb-2">Ganhador(a) do prêmio</p>
                        <h3 class="text-2xl font-bold text-amber-400 mb-6" id="result-award-name"></h3>

                        <div class="mb-8">
                            <h2 class="text-5xl font-black text-white mb-2 shimmer-text" id="result-name" style="animation-delay: 0.3s;"></h2>
                        </div>

                        <div class="grid grid-cols-2 gap-6 max-w-md mx-auto">
                            <div class="fade-in-up" style="animation-delay: 0.5s; opacity: 0;">
                                <div class="bg-gray-800/60 rounded-xl p-4 border border-gray-700/50">
                                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Telefone</p>
                                    <p class="text-xl font-bold text-white font-mono truncate" id="result-phone"></p>
                                </div>
                            </div>
                            <div class="fade-in-up" style="animation-delay: 0.7s; opacity: 0;">
                                <div class="bg-gray-800/60 rounded-xl p-4 border border-gray-700/50">
                                    <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Email</p>
                                    <p class="text-xl font-bold text-white font-mono truncate" id="result-email"></p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex gap-4 justify-center">
                            <button class="btn-draw" onclick="resetToSelect()" style="font-size: 1rem; padding: 14px 40px;">
                                <span>NOVO SORTEIO</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // ===== STATE =====
        let selectedAwardId = null;
        let selectedAwardName = '';
        let selectedAwardImage = '';
        let isDrawing = false;

        // ===== CLOCK =====
        function updateClock() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const date = now.toLocaleDateString('pt-BR', options);
            const time = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const el = document.getElementById('clock');
            if (el) el.innerHTML = `${date} <span class="text-indigo-400 font-bold ml-2">${time}</span>`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ===== PARTICLES =====
        function createParticles() {
            const container = document.getElementById('particles');
            const colors = ['#6366f1', '#8b5cf6', '#a855f7', '#ec4899', '#3b82f6'];
            for (let i = 0; i < 30; i++) {
                const p = document.createElement('div');
                p.className = 'particle';
                const size = Math.random() * 4 + 2;
                p.style.cssText = `
                    width: ${size}px; height: ${size}px;
                    left: ${Math.random() * 100}%;
                    background: ${colors[Math.floor(Math.random() * colors.length)]};
                    animation-duration: ${Math.random() * 15 + 10}s;
                    animation-delay: ${Math.random() * 10}s;
                `;
                container.appendChild(p);
            }
        }
        createParticles();

        // ===== CONFETTI =====
        function launchConfetti() {
            const colors = ['#6366f1', '#8b5cf6', '#fbbf24', '#ec4899', '#10b981', '#f43f5e', '#3b82f6', '#f97316'];
            const shapes = ['square', 'circle'];
            for (let i = 0; i < 120; i++) {
                const c = document.createElement('div');
                c.className = 'confetti';
                const color = colors[Math.floor(Math.random() * colors.length)];
                const shape = shapes[Math.floor(Math.random() * shapes.length)];
                const size = Math.random() * 10 + 6;
                c.style.cssText = `
                    left: ${Math.random() * 100}%;
                    width: ${size}px;
                    height: ${size}px;
                    background: ${color};
                    border-radius: ${shape === 'circle' ? '50%' : '2px'};
                    animation-duration: ${Math.random() * 2 + 2}s;
                    animation-delay: ${Math.random() * 1}s;
                `;
                document.body.appendChild(c);
                setTimeout(() => c.remove(), 4500);
            }
        }

        // ===== SELECT AWARD =====
        function selectAward(el, id, name, image, tier, remaining) {
            if (isDrawing) return;

            document.querySelectorAll('.award-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');

            selectedAwardId = id;
            selectedAwardName = name;
            selectedAwardImage = image;

            const btn = document.getElementById('btn-sortear');
            btn.disabled = false;
            document.getElementById('btn-text').textContent = 'SORTEAR';
        }

        // ===== START DRAW =====
        async function startDraw() {
            if (!selectedAwardId || isDrawing) return;
            isDrawing = true;

            const btn = document.getElementById('btn-sortear');
            btn.disabled = true;

            // Show drawing overlay
            document.getElementById('state-drawing').style.display = 'flex';
            document.getElementById('drawing-award-name').textContent = selectedAwardName;

            // Set award image in spinner if exists
            if (selectedAwardImage) {
                document.getElementById('drawing-award-img-container').innerHTML =
                    `<img src="${selectedAwardImage}" class="w-20 h-20 rounded-full object-cover spin-circle" alt="">`;
            }

            // Start progress bar
            const progressBar = document.getElementById('progress-bar');
            let progress = 0;

            const progressInterval = setInterval(() => {
                progress += 2;
                if (progress > 95) progress = 95;
                progressBar.style.width = progress + '%';
            }, 100);

            // Fetch winner from API
            try {
                const response = await fetch(`/sorteio/${selectedAwardId}/sortear`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (!data.success) {
                    clearInterval(progressInterval);
                    progressBar.style.width = '100%';
                    alert(data.message || 'Erro ao realizar o sorteio.');
                    document.getElementById('state-drawing').style.display = 'none';
                    isDrawing = false;
                    btn.disabled = false;
                    return;
                }

                // Run slot animation with decoy names + winner
                await runSlotAnimation(data.decoy_names, data.winner.name);

                clearInterval(progressInterval);
                progressBar.style.width = '100%';

                // Small pause before reveal
                await sleep(600);

                // Hide drawing, show result
                document.getElementById('state-drawing').style.display = 'none';
                document.getElementById('state-select').style.display = 'none';
                showWinner(data);

            } catch (err) {
                clearInterval(progressInterval);
                alert('Erro de conexão. Tente novamente.');
                document.getElementById('state-drawing').style.display = 'none';
                isDrawing = false;
                btn.disabled = false;
            }
        }

        // ===== SLOT MACHINE ANIMATION =====
        async function runSlotAnimation(decoyNames, winnerName) {
            const reel = document.getElementById('slot-reel');
            // Só nomes aleatórios — nunca o ganhador
            const names = decoyNames.length > 0 ? [...decoyNames] : ['Participante'];
            const duration = 5000; // 5 seconds
            const startTime = Date.now();

            return new Promise(resolve => {
                let currentIndex = 0;

                function tick() {
                    const elapsed = Date.now() - startTime;
                    const progress = Math.min(elapsed / duration, 1);

                    // Cicla pelos nomes aleatórios (sem o ganhador)
                    const nameIndex = currentIndex % names.length;

                    reel.innerHTML = '';

                    const prevIndex = (nameIndex - 1 + names.length) % names.length;
                    const nextIndex = (nameIndex + 1) % names.length;

                    [prevIndex, nameIndex, nextIndex].forEach((idx, i) => {
                        const div = document.createElement('div');
                        div.className = 'slot-item' + (i === 1 ? ' active' : '');
                        div.textContent = names[idx];
                        if (i === 0) div.style.opacity = '0.2';
                        if (i === 2) div.style.opacity = '0.2';
                        reel.appendChild(div);
                    });

                    reel.style.transform = 'translateY(-80px)';
                    currentIndex++;

                    if (progress >= 1) {
                        // Final: revela o ganhador
                        reel.innerHTML = '';
                        const div = document.createElement('div');
                        div.className = 'slot-item active';
                        div.textContent = winnerName;
                        div.style.fontSize = '2.2rem';
                        div.style.color = '#fbbf24';
                        div.style.textShadow = '0 0 40px rgba(251,191,36,0.6)';
                        reel.appendChild(div);
                        reel.style.transform = 'translateY(0)';
                        resolve();
                    } else {
                        // ~500ms por nome, desacelera perto do final
                        const interval = 150 + (progress * progress * 350);
                        setTimeout(tick, interval);
                    }
                }

                tick();
            });
        }

        // ===== SHOW WINNER =====
        function showWinner(data) {
            const resultEl = document.getElementById('state-result');

            // Set winner data
            document.getElementById('result-award-name').textContent = data.award.name;
            document.getElementById('result-name').textContent = data.winner.name;
            document.getElementById('result-phone').textContent = data.winner.phone;
            document.getElementById('result-email').textContent = data.winner.email;

            // Set award image
            if (data.award.image) {
                document.getElementById('result-award-img-container').innerHTML = `
                    <img src="${data.award.image}" class="mx-auto w-28 h-28 rounded-2xl object-cover shadow-lg shadow-indigo-500/30 border-2 border-indigo-500/40" alt="">
                `;
            }

            // Show result with animation
            resultEl.style.display = 'block';

            // Re-trigger animations
            const card = document.getElementById('winner-card');
            card.classList.remove('reveal-anim');
            void card.offsetWidth; // reflow
            card.classList.add('reveal-anim');

            // Launch confetti
            launchConfetti();
            setTimeout(launchConfetti, 1000);
            setTimeout(launchConfetti, 2000);

            isDrawing = false;

            // Update remaining count on the card
            const awardCard = document.querySelector(`[data-award-id="${selectedAwardId}"]`);
            if (awardCard && data.award.remaining <= 0) {
                awardCard.style.opacity = '0.3';
                awardCard.style.pointerEvents = 'none';
            }
        }

        // ===== RESET =====
        function resetToSelect() {
            document.getElementById('state-result').style.display = 'none';
            document.getElementById('state-select').style.display = 'block';

            // Deselect
            document.querySelectorAll('.award-card').forEach(c => c.classList.remove('selected'));
            selectedAwardId = null;
            selectedAwardName = '';
            selectedAwardImage = '';

            const btn = document.getElementById('btn-sortear');
            btn.disabled = true;
            document.getElementById('btn-text').textContent = 'SELECIONE UM PRÊMIO';

            // Reload page to refresh quantities
            window.location.reload();
        }

        // ===== HELPERS =====
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }
    </script>
</body>
</html>
