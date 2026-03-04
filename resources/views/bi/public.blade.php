<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Público - Votação em Tempo Real</title>
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

        ::-webkit-scrollbar { display: none; }

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

        .card-glow {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(99, 102, 241, 0.15);
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .card-glow:hover {
            border-color: rgba(99, 102, 241, 0.3);
        }

        .gradient-border { position: relative; }
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

        .list-row {
            padding: 4px 6px;
            border-radius: 8px;
            transition: background 0.2s;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .list-row:hover { background: rgba(99, 102, 241, 0.06); }

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

        .compare-badge {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            line-height: 1;
        }
        .compare-up { background: rgba(16, 185, 129, 0.15); color: #34d399; }
        .compare-down { background: rgba(244, 63, 94, 0.15); color: #fb7185; }
        .compare-neutral { background: rgba(100, 116, 139, 0.15); color: #94a3b8; }

        @keyframes floatUp {
            0% { opacity: 0; transform: translate(-50%, 0) scale(0.5); }
            15% { opacity: 1; transform: translate(-50%, -20px) scale(1.1); }
            60% { opacity: 1; transform: translate(-50%, -150px) scale(1); }
            100% { opacity: 0; transform: translate(-50%, -300px) scale(0.7); }
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

        /* Badge público */
        .badge-public {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(6, 182, 212, 0.2));
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 20px;
            padding: 2px 10px;
            font-size: 10px;
            font-weight: 600;
            color: #34d399;
            letter-spacing: 0.5px;
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
                <h1 class="text-2xl font-bold text-white mb-1">Painel Público de Votação</h1>
                <p class="text-slate-400 text-sm">Melhores do Vale do Xingu — Dados Globais</p>
            </div>

            <!-- Código -->
            <div class="mb-6">
                <p class="text-slate-400 text-sm mb-3">Digite este código no painel administrativo para liberar a TV</p>
                <div class="inline-flex items-center gap-3 px-8 py-5 rounded-2xl" style="background: linear-gradient(135deg, #1e293b, #0f172a); border: 2px solid rgba(16,185,129,0.3); box-shadow: 0 0 40px rgba(16,185,129,0.1);">
                    <div id="codeDisplay" class="text-6xl font-black tracking-[0.4em] text-white font-mono" style="text-shadow: 0 0 20px rgba(16,185,129,0.5);">
                        ------
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center justify-center gap-2 mb-4">
                <div id="authStatusDot" class="w-2.5 h-2.5 rounded-full bg-amber-400" style="animation: pulse-green 2s infinite;"></div>
                <span id="authStatusText" class="text-sm text-amber-400 font-medium">Aguardando liberação do administrador...</span>
            </div>

            <!-- Badge -->
            <div class="mb-4">
                <span class="badge-public">PAINEL PÚBLICO — SEM DADOS DE EMPRESAS</span>
            </div>

            <!-- Instrução -->
            <div class="mt-8 px-6 py-3 rounded-xl inline-flex items-center gap-3" style="background: rgba(30,41,59,0.5); border: 1px solid rgba(255,255,255,0.05);">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-xs text-slate-400">Acesse <span class="text-emerald-400 font-semibold">/admin</span> → Gerenciar TVs → insira o código acima</span>
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
                    <p class="text-[10px] text-slate-400 -mt-0.5">Melhores do Vale do Xingu — Dados Globais</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="badge-public">PÚBLICO</span>
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

        <!-- KPI CARDS -->
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

            <!-- Eleitores Hoje -->
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

            <!-- Semana Atual -->
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

            <!-- Categorias -->
            <div class="card-glow gradient-border gradient-border-rose p-2.5">
                <div class="flex items-center gap-1.5 mb-1">
                    <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span class="text-[9px] font-medium text-slate-400 uppercase tracking-wider">Categorias</span>
                </div>
                <div class="text-xl font-black text-white tabular-nums" id="totalCategories">—</div>
                <div class="text-[9px] text-slate-500 mt-0.5">Média: <span id="avgVotesPerCategory" class="text-slate-400 font-medium tabular-nums">—</span> votos</div>
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
                <div class="text-[9px] text-slate-500 mt-0.5">Candidatos: <span id="totalCompanies" class="text-slate-400 font-medium tabular-nums">—</span></div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="flex-1 grid grid-cols-12 gap-3 min-h-0">

            <!-- COLUNA ESQUERDA: Gráficos empilhados -->
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

                <!-- TENDÊNCIA 7 DIAS -->
                <div class="card-glow gradient-border gradient-border-rose p-3 flex flex-col" style="height: 180px;">
                    <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                        <svg class="w-3.5 h-3.5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        Tendência Diária (7 dias)
                    </h3>
                    <div class="flex-1 min-h-0">
                        <canvas id="chartDailyTrend"></canvas>
                    </div>
                </div>
            </div>

            <!-- VOTOS POR FAIXA ETÁRIA, GRUPO E RESUMO (coluna central + direita) -->
            <div class="col-span-7 grid grid-rows-2 gap-3">
                <!-- LINHA SUPERIOR: Grupo + Resumo -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- VOTOS POR GRUPO DE CATEGORIA -->
                    <div class="card-glow gradient-border gradient-border-cyan p-3 flex flex-col">
                        <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Votos por Grupo
                        </h3>
                        <div class="flex-1 min-h-0">
                            <canvas id="chartGroups"></canvas>
                        </div>
                    </div>

                    <!-- ESTATÍSTICAS GLOBAIS em CARDS -->
                    <div class="grid grid-cols-2 gap-2 content-center">
                        <!-- Votos/Eleitor -->
                        <div class="card-glow p-3 flex flex-col items-center justify-center text-center" style="border-color: rgba(129, 140, 248, 0.4);">
                            <svg class="w-4 h-4 text-indigo-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <div class="text-2xl font-black text-indigo-300 tabular-nums leading-none" id="statAvgVotesPerVoter">—</div>
                            <div class="text-[9px] text-slate-300 mt-1 uppercase tracking-wider font-semibold">Méd. Votos/Eleitor</div>
                        </div>

                        <!-- Taxa Participação -->
                        <div class="card-glow p-3 flex flex-col items-center justify-center text-center" style="border-color: rgba(52, 211, 153, 0.4);">
                            <svg class="w-4 h-4 text-emerald-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div class="text-2xl font-black text-emerald-300 tabular-nums leading-none" id="statParticipation">—<span class="text-sm">%</span></div>
                            <div class="text-[9px] text-slate-300 mt-1 uppercase tracking-wider font-semibold">Taxa Participação</div>
                        </div>

                        <!-- Cat. Votadas/Eleitor -->
                        <div class="card-glow p-3 flex flex-col items-center justify-center text-center" style="border-color: rgba(34, 211, 238, 0.4);">
                            <svg class="w-4 h-4 text-cyan-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <div class="text-2xl font-black text-cyan-300 tabular-nums leading-none" id="statCatsPerVoter">—</div>
                            <div class="text-[9px] text-slate-300 mt-1 uppercase tracking-wider font-semibold">Méd. Cat./Eleitor</div>
                        </div>

                        <!-- Cadastrados -->
                        <div class="card-glow p-3 flex flex-col items-center justify-center text-center" style="border-color: rgba(192, 132, 252, 0.4);">
                            <svg class="w-4 h-4 text-purple-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <div class="text-2xl font-black text-purple-300 tabular-nums leading-none" id="statTotalAudience">—</div>
                            <div class="text-[9px] text-slate-300 mt-1 uppercase tracking-wider font-semibold">Total Cadastrados</div>
                        </div>

                        <!-- Votos/Dia -->
                        <div class="card-glow p-3 flex flex-col items-center justify-center text-center" style="border-color: rgba(96, 165, 250, 0.4);">
                            <svg class="w-4 h-4 text-blue-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div class="text-2xl font-black text-blue-300 tabular-nums leading-none" id="statAvgVotesPerDay">—</div>
                            <div class="text-[9px] text-slate-300 mt-1 uppercase tracking-wider font-semibold">Méd. Votos/Dia</div>
                        </div>

                        <!-- Eleitores/Dia -->
                        <div class="card-glow p-3 flex flex-col items-center justify-center text-center" style="border-color: rgba(252, 211, 77, 0.4);">
                            <svg class="w-4 h-4 text-amber-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <div class="text-2xl font-black text-amber-300 tabular-nums leading-none" id="statAvgVotersPerDay">—</div>
                            <div class="text-[9px] text-slate-300 mt-1 uppercase tracking-wider font-semibold">Méd. Eleitores/Dia</div>
                        </div>

                    </div>
                </div>

                <!-- LINHA INFERIOR: Faixa Etária + Cadastros por Hora -->
                <div class="grid grid-cols-2 gap-3">
                    <!-- VOTOS POR FAIXA ETÁRIA -->
                    <div class="card-glow gradient-border gradient-border-amber p-3 flex flex-col">
                        <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Votos por Faixa Etária
                        </h3>
                        <div class="flex-1 min-h-0">
                            <canvas id="chartAge"></canvas>
                        </div>
                    </div>

                    <!-- CADASTROS POR HORA -->
                    <div class="card-glow gradient-border gradient-border-green p-3 flex flex-col">
                        <h3 class="text-xs font-semibold text-slate-300 mb-2 flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            Cadastros por Hora (24h)
                        </h3>
                        <div class="flex-1 min-h-0">
                            <canvas id="chartRegistrations"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer class="flex items-center justify-between px-4 py-1.5 rounded-lg" style="background: rgba(30,41,59,0.5); border: 1px solid rgba(255,255,255,0.05);">
            <div class="flex items-center gap-2">
                <span class="text-[10px] text-slate-500">Atualização automática a cada 5 segundos</span>
                <span class="text-[10px] text-slate-500">•</span>
                <span class="text-[10px] text-emerald-500 font-medium">Dados globais — sem identificação de empresas</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[10px] text-slate-500">Última atualização: <span id="lastUpdate" class="text-slate-400 font-medium">—</span></span>
                <span class="text-[10px] text-slate-500">|</span>
                <span class="text-[10px] text-slate-500">Ciclos: <span id="cycleCount" class="text-slate-400 font-medium">0</span></span>
            </div>
        </footer>
    </div>

    <script>
        // ============================================
        //  CONFIG
        // ============================================
        const REFRESH_INTERVAL = 5000;
        const AUTH_CHECK_INTERVAL = 3000;
        const API_URL = '/bi-publico/data';
        let cycleCount = 0;
        let hourlyChart = null;
        let ageChart = null;
        let groupsChart = null;
        let dailyTrendChart = null;
        let registrationsChart = null;
        let lastTotalVotes = null;
        let tvSessionId = null;
        let authCheckTimer = null;
        let dataRefreshTimer = null;
        let isAuthorized = false;

        // ============================================
        //  CSRF TOKEN
        // ============================================
        const csrfToken = '{{ csrf_token() }}';

        // ============================================
        //  FLUXO DE AUTENTICAÇÃO DA TV
        // ============================================
        async function requestCode() {
            try {
                const response = await fetch('/bi-publico/request-code', {
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

                const formatted = code.substring(0, 3) + ' ' + code.substring(3);
                document.getElementById('codeDisplay').textContent = formatted;

                localStorage.setItem('tv_public_session_id', tvSessionId);
                localStorage.setItem('tv_public_code', code);

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
                const response = await fetch(`/bi-publico/check-auth?session_id=${tvSessionId}`);
                const data = await response.json();

                if (data.authorized) {
                    isAuthorized = true;
                    clearInterval(authCheckTimer);
                    showDashboard();
                }
            } catch (err) {
                console.error('Erro ao verificar autorização:', err);
            }
        }

        function startAuthCheck() {
            checkAuthorization();
            authCheckTimer = setInterval(checkAuthorization, AUTH_CHECK_INTERVAL);
        }

        function showDashboard() {
            document.getElementById('authScreen').style.display = 'none';
            document.getElementById('dashboardScreen').style.display = 'flex';

            initHourlyChart();
            initAgeChart();
            initGroupsChart();
            initDailyTrendChart();
            initRegistrationsChart();
            fetchData();
            dataRefreshTimer = setInterval(fetchData, REFRESH_INTERVAL);

            setInterval(checkStillAuthorized, 10000);
        }

        async function checkStillAuthorized() {
            if (!tvSessionId) return;

            try {
                const response = await fetch(`/bi-publico/check-auth?session_id=${tvSessionId}`);
                const data = await response.json();

                if (!data.authorized) {
                    isAuthorized = false;
                    clearInterval(dataRefreshTimer);

                    if (hourlyChart) { hourlyChart.destroy(); hourlyChart = null; }
                    if (ageChart) { ageChart.destroy(); ageChart = null; }
                    if (registrationsChart) { registrationsChart.destroy(); registrationsChart = null; }
                    if (groupsChart) { groupsChart.destroy(); groupsChart = null; }
                    if (dailyTrendChart) { dailyTrendChart.destroy(); dailyTrendChart = null; }

                    document.getElementById('dashboardScreen').style.display = 'none';
                    document.getElementById('authScreen').style.display = 'flex';

                    localStorage.removeItem('tv_public_session_id');
                    localStorage.removeItem('tv_public_code');

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

        // ============================================
        //  PALETA DE CORES
        // ============================================
        const CHART_PALETTE = [
            '#6366f1', '#8b5cf6', '#a78bfa', '#c4b5fd',
            '#3b82f6', '#60a5fa', '#93c5fd',
            '#10b981', '#34d399', '#6ee7b7',
            '#f59e0b', '#fbbf24', '#fde68a',
            '#f43f5e', '#fb7185', '#fda4af',
            '#06b6d4', '#22d3ee', '#67e8f9',
        ];

        // ============================================
        //  ANIMAÇÃO +N VOTOS
        // ============================================
        function spawnVoteFloat(count) {
            const el = document.createElement('div');
            el.className = 'vote-float';
            el.textContent = `+${count}`;
            const duration = 2.0 + Math.random() * 0.5;
            el.style.animationDuration = `${duration}s`;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), duration * 1000 + 100);
        }

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

            const barColors = [
                '#06b6d4', '#0ea5e9', '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7',
                '#c026d3', '#e11d48', '#f43f5e', '#fb7185', '#f97316', '#f59e0b',
                '#eab308', '#84cc16', '#22c55e', '#10b981', '#14b8a6', '#06b6d4',
                '#0ea5e9', '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#c026d3',
            ];

            hourlyChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                plugins: [ChartDataLabels],
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Votos',
                        data: [],
                        backgroundColor: barColors.map(c => c + 'cc'),
                        borderColor: barColors,
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
                            anchor: 'end', align: 'end',
                            color: '#e2e8f0',
                            font: { size: 10, weight: 'bold', family: 'Inter' },
                            formatter: v => v > 0 ? v : '',
                            offset: 2,
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#e2e8f0', bodyColor: '#94a3b8',
                            borderColor: 'rgba(99, 102, 241, 0.3)', borderWidth: 1,
                            cornerRadius: 8, padding: 10,
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

        // ============================================
        //  GRÁFICO FAIXA ETÁRIA
        // ============================================
        function initAgeChart() {
            const ctx = document.getElementById('chartAge');
            if (!ctx) return;
            ageChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: CHART_PALETTE.map(c => c + 'CC'),
                        borderColor: CHART_PALETTE,
                        borderWidth: 1,
                        borderRadius: 4,
                        barPercentage: 0.7,
                        categoryPercentage: 0.8,
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 800 },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: 'rgba(148, 163, 184, 0.08)', drawBorder: false },
                            ticks: {
                                color: '#64748b', font: { size: 9 },
                                callback: v => v >= 1000 ? (v/1000).toFixed(0) + 'k' : v
                            }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: '#94a3b8', font: { size: 10, weight: '500' } }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        datalabels: {
                            anchor: 'end', align: 'right', offset: 4,
                            color: '#cbd5e1', font: { size: 9, weight: 'bold' },
                            formatter: v => v ? v.toLocaleString('pt-BR') : ''
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#e2e8f0', bodyColor: '#94a3b8',
                            borderColor: 'rgba(99, 102, 241, 0.3)', borderWidth: 1,
                            cornerRadius: 8, padding: 10,
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? ((ctx.parsed.x / total) * 100).toFixed(1) : 0;
                                    return ` ${ctx.parsed.x.toLocaleString('pt-BR')} votos (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // ============================================
        //  GRÁFICO CADASTROS POR HORA
        // ============================================
        function initRegistrationsChart() {
            const ctx = document.getElementById('chartRegistrations');
            if (!ctx) return;

            const barColors = [
                '#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#10b981', '#059669',
                '#047857', '#065f46', '#10b981', '#34d399', '#6ee7b7', '#a7f3d0',
                '#10b981', '#059669', '#047857', '#065f46', '#10b981', '#34d399',
                '#6ee7b7', '#a7f3d0', '#10b981', '#059669', '#047857', '#065f46',
            ];

            registrationsChart = new Chart(ctx.getContext('2d'), {
                type: 'bar',
                plugins: [ChartDataLabels],
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Cadastros',
                        data: [],
                        backgroundColor: barColors.map(c => c + 'cc'),
                        borderColor: barColors,
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
                            display: true,
                            anchor: 'end', align: 'end',
                            color: '#a7f3d0',
                            font: { size: 10, weight: 'bold', family: 'Inter' },
                            formatter: v => v > 0 ? v : '',
                            offset: 2,
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#e2e8f0', bodyColor: '#94a3b8',
                            borderColor: 'rgba(16, 185, 129, 0.3)', borderWidth: 1,
                            cornerRadius: 8, padding: 10,
                        }
                    },
                    layout: {
                        padding: { top: 20 }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(255,255,255,0.03)', drawBorder: false },
                            ticks: { color: '#94a3b8', font: { size: 8, weight: '500' }, maxRotation: 0 }
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

        // ============================================
        //  GRÁFICO VOTOS POR GRUPO
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
                        backgroundColor: ['#6366f1', '#f59e0b', '#10b981', '#f43f5e', '#06b6d4', '#8b5cf6', '#ec4899', '#84cc16'],
                        borderColor: '#0a0e1a',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '55%',
                    animation: { duration: 800 },
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: '#94a3b8', font: { size: 9 },
                                padding: 6, boxWidth: 10, boxHeight: 10,
                                usePointStyle: true, pointStyle: 'circle',
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.95)',
                            titleColor: '#e2e8f0', bodyColor: '#94a3b8',
                            borderColor: 'rgba(99, 102, 241, 0.3)', borderWidth: 1,
                            cornerRadius: 8, padding: 10,
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                                    return ` ${ctx.label}: ${ctx.parsed.toLocaleString('pt-BR')} (${pct}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // ============================================
        //  GRÁFICO TENDÊNCIA 7 DIAS
        // ============================================
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
                            titleColor: '#e2e8f0', bodyColor: '#94a3b8',
                            borderColor: 'rgba(244, 63, 94, 0.3)', borderWidth: 1,
                            cornerRadius: 8, padding: 10,
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

        // ============================================
        //  COMPARATIVO
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

                // KPIs
                animateValue(document.getElementById('totalVotes'), data.total_votes);

                // Animação +N
                if (lastTotalVotes !== null && data.total_votes > lastTotalVotes) {
                    spawnVoteFloat(data.total_votes - lastTotalVotes);
                }
                lastTotalVotes = data.total_votes;

                animateValue(document.getElementById('totalVoters'), data.total_voters);
                animateValue(document.getElementById('votesToday'), data.votes_today);
                animateValue(document.getElementById('votesLastHour'), data.votes_last_hour);
                animateValue(document.getElementById('totalCategories'), data.total_categories);
                animateValue(document.getElementById('totalCompanies'), data.total_companies);

                // Comparativos
                const yEl = document.getElementById('votesYesterday');
                if (yEl) yEl.textContent = formatNumber(data.votes_yesterday);
                updateCompareBadge('badgeTodayVsYesterday', data.votes_today, data.votes_yesterday);

                animateValue(document.getElementById('votersToday'), data.voters_today);
                const vyEl = document.getElementById('votersYesterday');
                if (vyEl) vyEl.textContent = formatNumber(data.voters_yesterday);
                updateCompareBadge('badgeVotersTodayVsYesterday', data.voters_today, data.voters_yesterday);

                animateValue(document.getElementById('votesThisWeek'), data.votes_this_week);
                const lwEl = document.getElementById('votesLastWeek');
                if (lwEl) lwEl.textContent = formatNumber(data.votes_last_week);
                updateCompareBadge('badgeWeekVotes', data.votes_this_week, data.votes_last_week_same_day);

                // Média por hora
                const avgEl = document.getElementById('avgPerHourToday');
                if (avgEl) avgEl.textContent = data.avg_per_hour_today;

                // Média votos/categoria
                const avgVPC = document.getElementById('avgVotesPerCategory');
                if (avgVPC) avgVPC.textContent = formatNumber(data.avg_votes_per_category);

                // Atividade
                const recentEl = document.getElementById('recentVotes');
                animateValue(recentEl, data.recent_votes);
                if (recentEl) recentEl.style.color = data.recent_votes > 0 ? '#10b981' : '#64748b';

                // Status
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
                if (hourlyChart) {
                    hourlyChart.data.labels = Object.keys(data.votes_per_hour);
                    hourlyChart.data.datasets[0].data = Object.values(data.votes_per_hour);
                    hourlyChart.update('none');
                }

                if (ageChart) {
                    ageChart.data.labels = Object.keys(data.votes_by_age);
                    ageChart.data.datasets[0].data = Object.values(data.votes_by_age);
                    ageChart.update('none');
                }

                if (groupsChart && data.votes_by_group) {
                    groupsChart.data.labels = Object.keys(data.votes_by_group);
                    groupsChart.data.datasets[0].data = Object.values(data.votes_by_group);
                    groupsChart.update('none');
                }

                if (dailyTrendChart && data.votes_per_day) {
                    dailyTrendChart.data.labels = Object.keys(data.votes_per_day);
                    dailyTrendChart.data.datasets[0].data = Object.values(data.votes_per_day);
                    dailyTrendChart.update('none');
                }

                if (registrationsChart && data.registrations_per_hour) {
                    registrationsChart.data.labels = Object.keys(data.registrations_per_hour);
                    registrationsChart.data.datasets[0].data = Object.values(data.registrations_per_hour);
                    registrationsChart.update('none');
                }

                // Estatísticas globais
                const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };

                setEl('statAvgVotesPerVoter', data.avg_votes_per_voter);
                const partEl = document.getElementById('statParticipation');
                if (partEl) partEl.innerHTML = `${data.participation_rate}<span class="text-sm">%</span>`;
                setEl('statCatsPerVoter', data.avg_categories_per_voter);
                setEl('statTotalAudience', formatNumber(data.total_audience));
                setEl('statAvgVotesPerDay', formatNumber(data.avg_votes_per_day));
                setEl('statAvgVotersPerDay', formatNumber(data.avg_voters_per_day));

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
            const savedSessionId = localStorage.getItem('tv_public_session_id');

            if (savedSessionId) {
                tvSessionId = parseInt(savedSessionId);
                fetch(`/bi-publico/check-auth?session_id=${tvSessionId}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.authorized) {
                            showDashboard();
                        } else {
                            localStorage.removeItem('tv_public_session_id');
                            localStorage.removeItem('tv_public_code');
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
