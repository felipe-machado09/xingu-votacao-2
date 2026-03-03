<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BI - Votação em Tempo Real</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
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

        /* Scrollbar oculta */
        ::-webkit-scrollbar { display: none; }

        /* Animação de pulse para atividade recente */
        @keyframes pulse-green {
            0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            50% { box-shadow: 0 0 20px 10px rgba(16, 185, 129, 0.1); }
        }
        .pulse-active { animation: pulse-green 2s infinite; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in { animation: slideIn 0.5s ease-out; }

        @keyframes countUp {
            from { opacity: 0; transform: scale(0.8); }
            to { opacity: 1; transform: scale(1); }
        }
        .count-up { animation: countUp 0.3s ease-out; }

        /* Glow effect nos cards */
        .card-glow {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .card-glow:hover {
            border-color: rgba(99, 102, 241, 0.3);
        }

        /* Gradient borders */
        .gradient-border {
            position: relative;
        }
        .gradient-border::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 12px 12px 0 0;
        }

        .gradient-border-blue::before { background: linear-gradient(90deg, #3b82f6, #6366f1); }
        .gradient-border-green::before { background: linear-gradient(90deg, #10b981, #34d399); }
        .gradient-border-purple::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
        .gradient-border-amber::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .gradient-border-rose::before { background: linear-gradient(90deg, #f43f5e, #fb7185); }
        .gradient-border-cyan::before { background: linear-gradient(90deg, #06b6d4, #22d3ee); }

        /* Progress bars */
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: rgba(255,255,255,0.06);
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease-in-out;
        }

        /* Rank badges */
        .rank-badge {
            width: 22px; height: 22px;
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 800;
            flex-shrink: 0;
        }
        .rank-1 { background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #78350f; }
        .rank-2 { background: linear-gradient(135deg, #94a3b8, #64748b); color: #1e293b; }
        .rank-3 { background: linear-gradient(135deg, #d97706, #b45309); color: #fffbeb; }
        .rank-default { background: rgba(255,255,255,0.06); color: #64748b; }

        /* Company/Category row */
        .list-row {
            padding: 4px 6px;
            border-radius: 8px;
            transition: background 0.2s;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .list-row:hover {
            background: rgba(99, 102, 241, 0.06);
        }

        /* Indicador de status */
        .status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            display: inline-block;
        }
        .status-live {
            background: #10b981;
            box-shadow: 0 0 8px rgba(16, 185, 129, 0.6);
            animation: pulse-green 1.5s infinite;
        }

        /* Comparison badges */
        .compare-badge {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            line-height: 1;
        }
        .compare-up {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }
        .compare-down {
            background: rgba(244, 63, 94, 0.15);
            color: #fb7185;
        }
        .compare-neutral {
            background: rgba(100, 116, 139, 0.15);
            color: #94a3b8;
        }

        /* Table rows */
        .table-row {
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.2s;
        }
        .table-row:hover {
            background: rgba(99, 102, 241, 0.05);
        }

        /* Floating +N vote animation */
        @keyframes floatUp {
            0% {
                opacity: 0;
                transform: translate(-50%, 0) scale(0.5);
            }
            15% {
                opacity: 1;
                transform: translate(-50%, -20px) scale(1.1);
            }
            60% {
                opacity: 1;
                transform: translate(-50%, -150px) scale(1);
            }
            100% {
                opacity: 0;
                transform: translate(-50%, -300px) scale(0.7);
            }
        }
        .vote-float {
            position: fixed;
            left: 50%;
            top: 50%;
            pointer-events: none;
            font-family: 'Inter', sans-serif;
            font-weight: 900;
            font-size: 64px;
            color: #34d399;
            text-shadow: 0 0 30px rgba(52, 211, 153, 0.7), 0 0 60px rgba(52, 211, 153, 0.4), 0 2px 4px rgba(0,0,0,0.5);
            animation: floatUp 2.2s ease-out forwards;
            z-index: 100;
            white-space: nowrap;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

    <!-- ============================================ -->
    <!--  TELA DE CÓDIGO (antes da liberação)         -->
    <!-- ============================================ -->
    <div id="authScreen" class="h-screen w-screen flex items-center justify-center">
        <div class="text-center">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('img/logo-melhores-do-ano-branca.png') }}" alt="Melhores do Vale do Xingu" class="mx-auto mb-6" style="max-height: 120px; filter: drop-shadow(0 0 30px rgba(99,102,241,0.3));">
                <h1 class="text-2xl font-bold text-white mb-1">Painel de Votação</h1>
                <p class="text-slate-400 text-sm">Melhores do Vale do Xingu</p>
            </div>

            <!-- Código -->
            <div class="mb-6">
                <p class="text-slate-400 text-sm mb-3">Digite este código no painel administrativo para liberar a TV</p>
                <div class="inline-flex items-center gap-3 px-8 py-5 rounded-2xl" style="background: linear-gradient(135deg, #1e293b, #0f172a); border: 2px solid rgba(99,102,241,0.3); box-shadow: 0 0 40px rgba(99,102,241,0.1);">
                    <div id="codeDisplay" class="text-6xl font-black tracking-[0.4em] text-white font-mono" style="text-shadow: 0 0 20px rgba(99,102,241,0.5);">
                        ------
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center justify-center gap-2 mb-4">
                <div id="authStatusDot" class="w-2.5 h-2.5 rounded-full bg-amber-400" style="animation: pulse-green 2s infinite;"></div>
                <span id="authStatusText" class="text-sm text-amber-400 font-medium">Aguardando liberação do administrador...</span>
            </div>

            <!-- Instrução -->
            <div class="mt-8 px-6 py-3 rounded-xl inline-flex items-center gap-3" style="background: rgba(30,41,59,0.5); border: 1px solid rgba(255,255,255,0.05);">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs text-slate-400">Acesse <span class="text-indigo-400 font-semibold">/admin</span> → Gerenciar TVs → insira o código acima</span>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!--  DASHBOARD (aparece após liberação)          -->
    <!-- ============================================ -->
    <div id="dashboardScreen" class="h-screen w-screen flex flex-col p-3 gap-3" style="display: none;">

        <!-- HEADER -->
        <header class="flex items-center justify-between px-4 py-2 rounded-xl" style="background: linear-gradient(135deg, #1e293b, #0f172a); border: 1px solid rgba(99,102,241,0.2);">
            <div class="flex items-center gap-3">
                <img src="{{ asset('img/logo-melhores-do-ano-branca.png') }}" alt="Logo" style="max-height: 36px; filter: drop-shadow(0 0 10px rgba(99,102,241,0.3));">
                <div>
                    <h1 class="text-lg font-bold text-white tracking-tight">Painel de Votação</h1>
                    <p class="text-[10px] text-slate-400 -mt-0.5">Melhores do Vale do Xingu</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="status-dot status-live" id="statusDot"></span>
                    <span class="text-xs font-medium text-emerald-400" id="statusText">AO VIVO</span>
                </div>
                <div class="text-right">
                    <div class="text-xl font-bold text-white tabular-nums" id="clock"></div>
                    <div class="text-[10px] text-slate-400" id="dateDisplay"></div>
                </div>
            </div>
        </header>

        <!-- KPI CARDS ROW 1: Principais + Comparativos Diários -->
        <div class="grid grid-cols-8 gap-2">
            <!-- Total Votos -->
            <div class="card-glow gradient-border gradient-border-blue p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Total Votos</span>
                </div>
                <div class="text-xl font-black text-white tabular-nums" id="totalVotes">—</div>
            </div>

            <!-- Eleitores -->
            <div class="card-glow gradient-border gradient-border-green p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Eleitores</span>
                </div>
                <div class="text-xl font-black text-white tabular-nums" id="totalVoters">—</div>
            </div>

            <!-- Hoje vs Ontem (VOTOS) -->
            <div class="card-glow gradient-border gradient-border-purple p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Hoje</span>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <div class="text-xl font-black text-white tabular-nums" id="votesToday">—</div>
                    <span class="compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums" id="badgeTodayVsYesterday"></span>
                </div>
                <div class="text-[9px] text-slate-500 mt-0.5">Ontem: <span id="votesYesterday" class="text-slate-400 font-medium tabular-nums">—</span></div>
            </div>

            <!-- Hoje vs Ontem (ELEITORES) -->
            <div class="card-glow gradient-border gradient-border-cyan p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Eleitores Hoje</span>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <div class="text-xl font-black text-white tabular-nums" id="votersToday">—</div>
                    <span class="compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums" id="badgeVotersTodayVsYesterday"></span>
                </div>
                <div class="text-[9px] text-slate-500 mt-0.5">Ontem: <span id="votersYesterday" class="text-slate-400 font-medium tabular-nums">—</span></div>
            </div>

            <!-- Semana Atual vs Passada (VOTOS) -->
            <div class="card-glow gradient-border gradient-border-amber p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Semana Atual</span>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <div class="text-xl font-black text-white tabular-nums" id="votesThisWeek">—</div>
                    <span class="compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums" id="badgeWeekVotes"></span>
                </div>
                <div class="text-[9px] text-slate-500 mt-0.5">Sem. passada: <span id="votesLastWeek" class="text-slate-400 font-medium tabular-nums">—</span></div>
            </div>

            <!-- Semana Atual vs Passada (ELEITORES) -->
            <div class="card-glow gradient-border gradient-border-rose p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Eleitores Semana</span>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <div class="text-xl font-black text-white tabular-nums" id="votersThisWeek">—</div>
                    <span class="compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums" id="badgeWeekVoters"></span>
                </div>
                <div class="text-[9px] text-slate-500 mt-0.5">Sem. passada: <span id="votersLastWeek" class="text-slate-400 font-medium tabular-nums">—</span></div>
            </div>

            <!-- Última Hora -->
            <div class="card-glow gradient-border gradient-border-blue p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Última Hora</span>
                </div>
                <div class="text-xl font-black text-white tabular-nums" id="votesLastHour">—</div>
                <div class="text-[9px] text-slate-500 mt-0.5">Média/h: <span id="avgPerHourToday" class="text-slate-400 font-medium tabular-nums">—</span></div>
            </div>

            <!-- Atividade -->
            <div class="card-glow gradient-border gradient-border-green p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Atividade</span>
                </div>
                <div class="flex items-baseline gap-1">
                    <div class="text-xl font-black tabular-nums" id="recentVotes" style="color: #10b981;">—</div>
                    <span class="text-[8px] text-slate-500">/10s</span>
                </div>
                <div class="text-[9px] text-slate-500 mt-0.5">Categorias: <span id="totalCategories" class="text-slate-400 font-medium tabular-nums">—</span></div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="flex-1 grid grid-cols-12 gap-3 min-h-0">

            <!-- COLUNA ESQUERDA: Gráficos -->
            <div class="col-span-5 flex flex-col gap-3">
                <!-- GRÁFICO DE VOTOS POR HORA -->
                <div class="flex-1 card-glow gradient-border gradient-border-blue p-3 flex flex-col">
                    <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Votos por Hora (24h)
                    </h3>
                    <div class="flex-1 min-h-0">
                        <canvas id="chartHourly"></canvas>
                    </div>
                </div>
            </div>

            <!-- TOP CATEGORIAS -->
            <div class="col-span-3 card-glow gradient-border gradient-border-purple p-3 flex flex-col">
                <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Top Categorias
                </h3>
                <div class="flex-1 overflow-y-auto" id="topCategoriesList">
                    <div class="text-center text-slate-500 text-xs py-4">Carregando...</div>
                </div>
            </div>

            <!-- TOP EMPRESAS -->
            <div class="col-span-4 card-glow gradient-border gradient-border-green p-3 flex flex-col">
                <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Top Empresas
                    <span class="ml-auto text-[9px] text-slate-500 font-normal" id="totalCompaniesLabel"></span>
                </h3>
                <div class="flex-1 overflow-y-auto" id="topCompaniesList">
                    <div class="text-center text-slate-500 text-xs py-4">Carregando...</div>
                </div>
            </div>
        </div>

        <!-- BOTTOM ROW -->
        <div class="grid grid-cols-12 gap-3" style="height: 280px;">

            <!-- VOTOS POR FAIXA ETÁRIA -->
            <div class="col-span-4 card-glow gradient-border gradient-border-amber p-3 flex flex-col">
                <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Votos por Faixa Etária
                </h3>
                <div class="flex-1 min-h-0">
                    <canvas id="chartGroups"></canvas>
                </div>
            </div>

            <!-- RANKING CATEGORIAS: LIDER -->
            <div class="col-span-8 card-glow gradient-border gradient-border-cyan p-3 flex flex-col">
                <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    Líder por Categoria
                </h3>
                <div class="flex-1 overflow-hidden" id="categoryLeadersList">
                    <div class="text-center text-slate-500 text-xs py-4">Carregando...</div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="flex items-center justify-between px-4 py-1.5 rounded-lg" style="background: rgba(30,41,59,0.5); border: 1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-2">
                <span class="text-[10px] text-slate-500">Atualização automática a cada 5 segundos</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] text-slate-500">Última atualização: <span id="lastUpdate" class="text-slate-400 font-medium">—</span></span>
                <span class="text-[10px] text-slate-500">|</span>
                <span class="text-[10px] text-slate-500">Ciclos: <span id="cycleCount" class="text-slate-400 font-medium">0</span></span>
            </div>
        </footer>
    </div> <!-- /dashboardScreen -->

    <script>
        // ============================================
        //  CONFIG
        // ============================================
        const REFRESH_INTERVAL = 5000; // 5 segundos
        const AUTH_CHECK_INTERVAL = 3000; // verificar autorização a cada 3s
        const API_URL = '/bi/data';
        let cycleCount = 0;
        let hourlyChart = null;
        let groupsChart = null;
        let tvSessionId = null;
        let authCheckTimer = null;
        let dataRefreshTimer = null;
        let isAuthorized = false;
        let lastTotalVotes = null;

        // ============================================
        //  ANIMAÇÃO +N VOTOS
        // ============================================
        function spawnVoteFloat(count) {
            const el = document.createElement('div');
            el.className = 'vote-float';
            el.textContent = `+${count}`;

            // Variação na duração
            const duration = 2.0 + Math.random() * 0.5;
            el.style.animationDuration = `${duration}s`;

            document.body.appendChild(el);

            // Remover após animação
            setTimeout(() => el.remove(), duration * 1000 + 100);
        }

        // ============================================
        //  CSRF TOKEN
        // ============================================
        const csrfToken = '{{ csrf_token() }}';

        // ============================================
        //  FLUXO DE AUTENTICAÇÃO DA TV
        // ============================================
        async function requestCode() {
            try {
                const response = await fetch('/bi/request-code', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) throw new Error('Erro ao gerar código');
                const data = await response.json();

                tvSessionId = data.session_id;
                const code = data.code;

                // Exibir código com espaço no meio: "123 456"
                const formatted = code.substring(0, 3) + ' ' + code.substring(3);
                document.getElementById('codeDisplay').textContent = formatted;

                // Salvar no localStorage para caso a página recarregue
                localStorage.setItem('tv_session_id', tvSessionId);
                localStorage.setItem('tv_code', code);

                // Começar a verificar autorização
                startAuthCheck();
            } catch (err) {
                console.error('Erro ao solicitar código:', err);
                document.getElementById('codeDisplay').textContent = 'ERRO';
                document.getElementById('authStatusText').textContent = 'Erro ao conectar. Recarregue a página.';
                document.getElementById('authStatusText').className = 'text-sm text-rose-400 font-medium';
                document.getElementById('authStatusDot').style.background = '#f43f5e';
            }
        }

        async function checkAuthorization() {
            if (!tvSessionId) return;

            try {
                const response = await fetch(`/bi/check-auth?session_id=${tvSessionId}`);
                const data = await response.json();

                if (data.authorized) {
                    // TV foi liberada!
                    isAuthorized = true;
                    clearInterval(authCheckTimer);
                    showDashboard();
                }
            } catch (err) {
                console.error('Erro ao verificar autorização:', err);
            }
        }

        function startAuthCheck() {
            // Verificar imediatamente
            checkAuthorization();
            // E depois a cada 3 segundos
            authCheckTimer = setInterval(checkAuthorization, AUTH_CHECK_INTERVAL);
        }

        function showDashboard() {
            document.getElementById('authScreen').style.display = 'none';
            document.getElementById('dashboardScreen').style.display = 'flex';

            // Inicializar gráficos e dados
            initHourlyChart();
            initGroupsChart();
            initDailyTrendChart();
            fetchData();
            dataRefreshTimer = setInterval(fetchData, REFRESH_INTERVAL);

            // Continuar verificando se a sessão não foi revogada
            setInterval(checkStillAuthorized, 10000);
        }

        async function checkStillAuthorized() {
            if (!tvSessionId) return;

            try {
                const response = await fetch(`/bi/check-auth?session_id=${tvSessionId}`);
                const data = await response.json();

                if (!data.authorized) {
                    // Admin revogou o acesso
                    isAuthorized = false;
                    clearInterval(dataRefreshTimer);

                    // Destruir gráficos
                    if (hourlyChart) { hourlyChart.destroy(); hourlyChart = null; }
                    if (groupsChart) { groupsChart.destroy(); groupsChart = null; }

                    // Voltar para tela de código
                    document.getElementById('dashboardScreen').style.display = 'none';
                    document.getElementById('authScreen').style.display = 'flex';

                    // Limpar localStorage
                    localStorage.removeItem('tv_session_id');
                    localStorage.removeItem('tv_code');

                    // Gerar novo código
                    document.getElementById('codeDisplay').textContent = '------';
                    document.getElementById('authStatusText').textContent = 'Sessão encerrada pelo administrador. Gerando novo código...';
                    document.getElementById('authStatusText').className = 'text-sm text-rose-400 font-medium';
                    document.getElementById('authStatusDot').style.background = '#f43f5e';

                    setTimeout(() => {
                        document.getElementById('authStatusText').textContent = 'Aguardando liberação do administrador...';
                        document.getElementById('authStatusText').className = 'text-sm text-amber-400 font-medium';
                        document.getElementById('authStatusDot').style.background = '#fbbf24';
                        cycleCount = 0;
                        requestCode();
                    }, 2000);
                }
            } catch (err) {
                console.error('Erro ao verificar sessão:', err);
            }
        }

        // Cores para gráficos
        const COLORS = {
            blue: { bg: 'rgba(59, 130, 246, 0.15)', border: 'rgba(59, 130, 246, 0.8)', fill: 'rgba(59, 130, 246, 0.3)' },
            purple: { bg: 'rgba(139, 92, 246, 0.15)', border: 'rgba(139, 92, 246, 0.8)' },
            green: { bg: 'rgba(16, 185, 129, 0.15)', border: 'rgba(16, 185, 129, 0.8)' },
            amber: { bg: 'rgba(245, 158, 11, 0.15)', border: 'rgba(245, 158, 11, 0.8)' },
            rose: { bg: 'rgba(244, 63, 94, 0.15)', border: 'rgba(244, 63, 94, 0.8)' },
            cyan: { bg: 'rgba(6, 182, 212, 0.15)', border: 'rgba(6, 182, 212, 0.8)' },
        };

        const CHART_PALETTE = [
            '#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd',
            '#3b82f6', '#60a5fa', '#93c5fd',
            '#10b981', '#34d399', '#6ee7b7',
            '#f59e0b', '#fbbf24', '#fde68a',
            '#f43f5e', '#fb7185', '#fda4af',
            '#06b6d4', '#22d3ee', '#67e8f9',
        ];

        // ============================================
        //  RELÓGIO
        // ============================================
        function updateClock() {
            const now = new Date();
            const clockEl = document.getElementById('clock');
            const dateEl = document.getElementById('dateDisplay');
            if (clockEl) clockEl.textContent = now.toLocaleTimeString('pt-BR');
            if (dateEl) dateEl.textContent = now.toLocaleDateString('pt-BR', {
                weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
            });
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ============================================
        //  FORMATAR NÚMEROS
        // ============================================
        function formatNumber(n) {
            if (n === null || n === undefined) return '—';
            return new Intl.NumberFormat('pt-BR').format(n);
        }

        // ============================================
        //  ANIMAR NÚMERO
        // ============================================
        function animateValue(el, newValue) {
            if (!el) return;
            const current = parseInt(el.textContent.replace(/\D/g, '')) || 0;
            if (current === newValue) return;

            el.classList.remove('count-up');
            void el.offsetWidth;
            el.classList.add('count-up');
            el.textContent = formatNumber(newValue);
        }

        // ============================================
        //  GRÁFICO VOTOS POR HORA
        // ============================================
        function initHourlyChart() {
            const ctx = document.getElementById('chartHourly');
            if (!ctx) return;

            // Gradiente vibrante para cada barra (24 horas)
            const barColors = [
                '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7',
                '#c026d3', '#e11d48', '#f43f5e', '#fb7185', '#f97316', '#f59e0b',
                '#eab308', '#84cc16', '#22c55e', '#10b981', '#14b8a6', '#06b6d4',
                '#0ea5e9', '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#c026d3',
            ];
            const barBorders = barColors.map(c => c);
            const barBgColors = barColors.map(c => c + 'cc'); // 80% opacity

            hourlyChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                plugins: [ChartDataLabels],
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Votos',
                        data: [],
                        backgroundColor: barBgColors,
                        borderColor: barBorders,
                        borderWidth: 1.5,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 800, easing: 'easeInOutQuart' },
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            anchor: 'end',
                            align: 'end',
                            color: '#e2e8f0',
                            font: { size: 10, weight: 'bold', family: 'Inter' },
                            formatter: function(value) {
                                return value > 0 ? value : '';
                            },
                            offset: 2,
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#e2e8f0',
                            bodyColor: '#94a3b8',
                            borderColor: 'rgba(99, 102, 241, 0.3)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 10,
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,0.03)', drawBorder: false },
                            ticks: { color: '#94a3b8', font: { size: 9, weight: '500' }, maxRotation: 0 }
                        },
                        y: {
                            grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                            ticks: { color: '#64748b', font: { size: 9 } },
                            beginAtZero: true,
                        }
                    }
                }
            });
        }

        function updateHourlyChart(data) {
            if (!hourlyChart) return;
            hourlyChart.data.labels = Object.keys(data);
            hourlyChart.data.datasets[0].data = Object.values(data);
            hourlyChart.update('none');
        }

        // ============================================
        //  GRÁFICO VOTOS POR FAIXA ETÁRIA
        // ============================================
        function initGroupsChart() {
            const ctx = document.getElementById('chartGroups');
            if (!ctx) return;
            groupsChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: CHART_PALETTE,
                        borderColor: '#0a0e1a',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    animation: { duration: 800 },
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#94a3b8',
                                font: { size: 9 },
                                padding: 6,
                                boxWidth: 10,
                                boxHeight: 10,
                                usePointStyle: true,
                                pointStyle: 'circle',
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#e2e8f0',
                            bodyColor: '#94a3b8',
                            borderColor: 'rgba(99, 102, 241, 0.3)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const value = context.parsed;
                                    const pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return ` ${context.label}: ${value.toLocaleString('pt-BR')} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateGroupsChart(data) {
            if (!groupsChart) return;
            groupsChart.data.labels = Object.keys(data);
            groupsChart.data.datasets[0].data = Object.values(data);
            groupsChart.update('none');
        }

        // ============================================
        //  GRÁFICO TENDÊNCIA DIÁRIA (7 DIAS)
        // ============================================
        let dailyTrendChart = null;

        function initDailyTrendChart() {
            const ctx = document.getElementById('chartDailyTrend');
            if (!ctx) return;
            dailyTrendChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Votos',
                        data: [],
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244, 63, 94, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: '#0a0e1a',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 800 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#e2e8f0',
                            bodyColor: '#94a3b8',
                            borderColor: 'rgba(244, 63, 94, 0.3)',
                            borderWidth: 1,
                            cornerRadius: 8,
                            padding: 10,
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,0.03)', drawBorder: false },
                            ticks: { color: '#64748b', font: { size: 9 } }
                        },
                        y: {
                            grid: { color: 'rgba(255,255,255,0.03)', drawBorder: false },
                            ticks: { color: '#64748b', font: { size: 9 } },
                            beginAtZero: true,
                        }
                    }
                }
            });
        }

        function updateDailyTrendChart(data) {
            if (!dailyTrendChart) return;
            dailyTrendChart.data.labels = Object.keys(data);
            dailyTrendChart.data.datasets[0].data = Object.values(data);
            dailyTrendChart.update('none');
        }

        // ============================================
        //  TOP CATEGORIAS
        // ============================================
        function updateTopCategories(categories) {
            const container = document.getElementById('topCategoriesList');
            if (!container || !categories || categories.length === 0) {
                if (container) container.innerHTML = '<div class="text-center text-slate-500 text-xs py-4">Nenhum dado</div>';
                return;
            }

            const maxVotes = Math.max(...categories.map(c => c.votes));
            let html = '';

            categories.forEach((cat, i) => {
                const pct = maxVotes > 0 ? (cat.votes / maxVotes * 100) : 0;
                const color = CHART_PALETTE[i % CHART_PALETTE.length];
                const rankClass = i === 0 ? 'rank-1' : (i === 1 ? 'rank-2' : (i === 2 ? 'rank-3' : 'rank-default'));
                const groupTag = cat.group ? `<span class="text-[8px] px-1.5 py-0.5 rounded" style="background:${color}22; color:${color}">${cat.group}</span>` : '';

                html += `
                    <div class="list-row flex items-center gap-2">
                        <div class="rank-badge ${rankClass}">${i + 1}</div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="text-[11px] font-semibold text-slate-200 truncate">${cat.name}</span>
                                    ${groupTag}
                                </div>
                                <span class="text-[11px] font-bold tabular-nums flex-shrink-0" style="color:${color}">${formatNumber(cat.votes)}</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:${pct}%; background: linear-gradient(90deg, ${color}, ${color}88);"></div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // ============================================
        //  TOP EMPRESAS
        // ============================================
        function updateTopCompanies(companies) {
            const container = document.getElementById('topCompaniesList');
            if (!container || !companies || companies.length === 0) {
                if (container) container.innerHTML = '<div class="text-center text-slate-500 text-xs py-4">Nenhum dado</div>';
                return;
            }

            const maxVotes = Math.max(...companies.map(c => c.votes));
            const totalVotes = companies.reduce((sum, c) => sum + c.votes, 0);
            let html = '';

            companies.forEach((comp, i) => {
                const pct = maxVotes > 0 ? (comp.votes / maxVotes * 100) : 0;
                const sharePct = totalVotes > 0 ? (comp.votes / totalVotes * 100).toFixed(1) : 0;
                const color = CHART_PALETTE[i % CHART_PALETTE.length];
                const rankClass = i === 0 ? 'rank-1' : (i === 1 ? 'rank-2' : (i === 2 ? 'rank-3' : 'rank-default'));

                const avatar = comp.logo
                    ? `<img src="${comp.logo}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0" alt="" onerror="this.outerHTML='<div class=\'w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white flex-shrink-0\' style=\'background:${color}\'>${comp.name.charAt(0)}</div>'">`
                    : `<div class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-bold text-white flex-shrink-0" style="background:${color}">${comp.name.charAt(0)}</div>`;

                html += `
                    <div class="list-row flex items-center gap-2">
                        <div class="rank-badge ${rankClass}">${i + 1}</div>
                        ${avatar}
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[11px] font-semibold text-slate-200 truncate">${comp.name}</span>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <span class="text-[9px] text-slate-500 tabular-nums">${sharePct}%</span>
                                    <span class="text-[11px] font-bold tabular-nums" style="color:${color}">${formatNumber(comp.votes)}</span>
                                </div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:${pct}%; background: linear-gradient(90deg, ${color}, ${color}88);"></div>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }

        // ============================================
        //  RANKING LÍDERES POR CATEGORIA
        // ============================================
        function updateCategoryLeaders(leaders) {
            const container = document.getElementById('categoryLeadersList');
            if (!container || !leaders || leaders.length === 0) {
                if (container) container.innerHTML = '<div class="text-center text-slate-500 text-xs py-4">Nenhum dado</div>';
                return;
            }

            let html = '<div class="grid grid-cols-3 gap-x-4 gap-y-0.5">';

            leaders.forEach((item, i) => {
                const color = CHART_PALETTE[i % CHART_PALETTE.length];
                html += `
                    <div class="flex items-center gap-2 py-0.5">
                        <div class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:${color}"></div>
                        <div class="flex-1 min-w-0 flex justify-between items-center gap-1">
                            <div class="min-w-0">
                                <div class="text-[10px] font-medium text-slate-400 truncate">${item.category}</div>
                                <div class="text-[11px] font-semibold text-white truncate">${item.leader}</div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <div class="text-[11px] font-bold tabular-nums" style="color:${color}">${formatNumber(item.leader_votes)}</div>
                                <div class="text-[9px] text-slate-500">${formatNumber(item.total_votes)} total</div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.innerHTML = html;
        }

        // ============================================
        //  COMPARATIVO: calcula % e renderiza badge
        // ============================================
        function updateCompareBadge(badgeId, current, previous) {
            const badge = document.getElementById(badgeId);
            if (!badge) return;

            if (previous === 0 && current === 0) {
                badge.className = 'compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums compare-neutral';
                badge.innerHTML = '—';
                return;
            }

            if (previous === 0) {
                badge.className = 'compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums compare-up';
                badge.innerHTML = '▲ novo';
                return;
            }

            const pct = ((current - previous) / previous * 100).toFixed(1);
            const absPct = Math.abs(pct);

            if (pct > 0) {
                badge.className = 'compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums compare-up';
                badge.innerHTML = `▲ ${absPct}%`;
            } else if (pct < 0) {
                badge.className = 'compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums compare-down';
                badge.innerHTML = `▼ ${absPct}%`;
            } else {
                badge.className = 'compare-badge text-[9px] font-bold px-1.5 py-0.5 rounded-full tabular-nums compare-neutral';
                badge.innerHTML = '= 0%';
            }
        }

        // ============================================
        //  FETCH DATA
        // ============================================
        async function fetchData() {
            try {
                const response = await fetch(API_URL);
                if (!response.ok) throw new Error('Erro na API');
                const data = await response.json();

                // KPIs principais
                animateValue(document.getElementById('totalVotes'), data.total_votes);

                // Detectar novos votos e mostrar animação +N
                if (lastTotalVotes !== null && data.total_votes > lastTotalVotes) {
                    const diff = data.total_votes - lastTotalVotes;
                    spawnVoteFloat(diff);
                }
                lastTotalVotes = data.total_votes;

                animateValue(document.getElementById('totalVoters'), data.total_voters);
                animateValue(document.getElementById('votesToday'), data.votes_today);
                animateValue(document.getElementById('votesLastHour'), data.votes_last_hour);
                animateValue(document.getElementById('totalCategories'), data.total_categories);

                // Comparativos diários
                const yEl = document.getElementById('votesYesterday');
                if (yEl) yEl.textContent = formatNumber(data.votes_yesterday);
                updateCompareBadge('badgeTodayVsYesterday', data.votes_today, data.votes_yesterday);

                animateValue(document.getElementById('votersToday'), data.voters_today);
                const vyEl = document.getElementById('votersYesterday');
                if (vyEl) vyEl.textContent = formatNumber(data.voters_yesterday);
                updateCompareBadge('badgeVotersTodayVsYesterday', data.voters_today, data.voters_yesterday);

                // Comparativos semanais
                animateValue(document.getElementById('votesThisWeek'), data.votes_this_week);
                const lwEl = document.getElementById('votesLastWeek');
                if (lwEl) lwEl.textContent = formatNumber(data.votes_last_week);
                updateCompareBadge('badgeWeekVotes', data.votes_this_week, data.votes_last_week_same_day);

                animateValue(document.getElementById('votersThisWeek'), data.voters_this_week);
                const vlwEl = document.getElementById('votersLastWeek');
                if (vlwEl) vlwEl.textContent = formatNumber(data.voters_last_week);
                updateCompareBadge('badgeWeekVoters', data.voters_this_week, data.voters_last_week);

                // Média por hora
                const avgEl = document.getElementById('avgPerHourToday');
                if (avgEl) avgEl.textContent = data.avg_per_hour_today;

                const recentEl = document.getElementById('recentVotes');
                animateValue(recentEl, data.recent_votes);
                if (recentEl) recentEl.style.color = data.recent_votes > 0 ? '#10b981' : '#64748b';

                // Indicador de atividade
                const statusDot = document.getElementById('statusDot');
                const statusText = document.getElementById('statusText');
                if (statusDot && statusText) {
                    if (data.recent_votes > 0) {
                        statusDot.className = 'status-dot status-live';
                        statusText.textContent = 'AO VIVO';
                        statusText.className = 'text-xs font-medium text-emerald-400';
                    } else {
                        statusDot.className = 'status-dot';
                        statusDot.style.background = '#64748b';
                        statusText.textContent = 'AGUARDANDO';
                        statusText.className = 'text-xs font-medium text-slate-400';
                    }
                }

                // Gráficos
                updateHourlyChart(data.votes_per_hour);
                updateGroupsChart(data.votes_by_age);
                if (data.votes_per_day) updateDailyTrendChart(data.votes_per_day);

                // Total empresas label
                const compLabel = document.getElementById('totalCompaniesLabel');
                if (compLabel && data.total_companies) compLabel.textContent = `${data.total_companies} empresas`;

                // Listas
                updateTopCategories(data.top_categories);
                updateTopCompanies(data.top_companies);
                updateCategoryLeaders(data.category_leaders);

                // Footer
                const luEl = document.getElementById('lastUpdate');
                if (luEl) luEl.textContent = data.updated_at;
                cycleCount++;
                const ccEl = document.getElementById('cycleCount');
                if (ccEl) ccEl.textContent = cycleCount;

            } catch (err) {
                console.error('Erro ao buscar dados:', err);
            }
        }

        // ============================================
        //  INIT — Fluxo de autenticação
        // ============================================
        (function init() {
            // Verificar se já tem uma sessão salva no localStorage
            const savedSessionId = localStorage.getItem('tv_session_id');

            if (savedSessionId) {
                tvSessionId = parseInt(savedSessionId);
                // Verificar se ainda está ativa
                fetch(`/bi/check-auth?session_id=${tvSessionId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.authorized) {
                            showDashboard();
                        } else {
                            localStorage.removeItem('tv_session_id');
                            localStorage.removeItem('tv_code');
                            requestCode();
                        }
                    })
                    .catch(() => requestCode());
            } else {
                requestCode();
            }
        })();
    </script>
</body>
</html>
