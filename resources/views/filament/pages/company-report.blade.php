<x-filament-panels::page>
    <style>
        :root {
            --rpt-bg: #ffffff;
            --rpt-bg-hover: rgba(0,0,0,0.02);
            --rpt-border: #e5e7eb;
            --rpt-border-light: #f3f4f6;
            --rpt-shadow: 0 1px 3px rgba(0,0,0,0.08);
            --rpt-text: #111827;
            --rpt-text-secondary: #374151;
            --rpt-text-muted: #6b7280;
            --rpt-text-dim: #9ca3af;
            --rpt-select-bg: #ffffff;
            --rpt-progress-bg: #e5e7eb;
            --rpt-badge-silver-bg: #f3f4f6;
            --rpt-badge-silver-text: #374151;
            --rpt-badge-default-bg: #f3f4f6;
            --rpt-badge-default-text: #6b7280;
            --rpt-badge-blue-bg: #dbeafe;
            --rpt-badge-blue-text: #1e40af;
            --rpt-badge-gold-bg: #fef3c7;
            --rpt-badge-gold-text: #92400e;
            --rpt-badge-bronze-bg: #fed7aa;
            --rpt-badge-bronze-text: #9a3412;
        }
        .dark {
            --rpt-bg: #1f2937;
            --rpt-bg-hover: rgba(255,255,255,0.03);
            --rpt-border: #374151;
            --rpt-border-light: #374151;
            --rpt-shadow: 0 1px 3px rgba(0,0,0,0.3);
            --rpt-text: #f9fafb;
            --rpt-text-secondary: #d1d5db;
            --rpt-text-muted: #9ca3af;
            --rpt-text-dim: #6b7280;
            --rpt-select-bg: #374151;
            --rpt-progress-bg: #4b5563;
            --rpt-badge-silver-bg: #4b5563;
            --rpt-badge-silver-text: #e5e7eb;
            --rpt-badge-default-bg: #4b5563;
            --rpt-badge-default-text: #9ca3af;
            --rpt-badge-blue-bg: #1e3a5f;
            --rpt-badge-blue-text: #93c5fd;
            --rpt-badge-gold-bg: #78350f;
            --rpt-badge-gold-text: #fef3c7;
            --rpt-badge-bronze-bg: #9a3412;
            --rpt-badge-bronze-text: #fed7aa;
        }

        .rpt-card {
            background: var(--rpt-bg);
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: var(--rpt-shadow);
            border: 1px solid var(--rpt-border);
            text-align: center;
        }
        .rpt-section {
            background: var(--rpt-bg);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--rpt-shadow);
            border: 1px solid var(--rpt-border);
        }
        .rpt-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--rpt-text);
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--rpt-border-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .rpt-stat-value { font-size: 2rem; font-weight: 800; line-height: 1; }
        .rpt-stat-value-sm { font-size: 1.75rem; font-weight: 800; line-height: 1; }
        .rpt-stat-label {
            font-size: 0.7rem;
            color: var(--rpt-text-muted);
            margin-top: 0.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .rpt-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 1rem; }
        .rpt-header-info { display: flex; align-items: center; gap: 1rem; }
        .rpt-logo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid var(--rpt-border); }
        .rpt-avatar {
            width: 60px; height: 60px; border-radius: 50%;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 1.5rem; font-weight: 700;
        }
        .rpt-company-name { font-size: 1.375rem; font-weight: 800; color: var(--rpt-text); margin: 0; line-height: 1.3; }
        .rpt-cnpj { font-size: 0.8rem; color: var(--rpt-text-muted); margin: 0.25rem 0 0 0; }
        .rpt-select {
            padding: 0.5rem 2rem 0.5rem 0.75rem;
            border-radius: 8px;
            border: 1px solid var(--rpt-border);
            font-size: 0.875rem;
            background: var(--rpt-select-bg);
            color: var(--rpt-text);
            cursor: pointer;
        }
        .rpt-grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .rpt-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        .rpt-grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .rpt-stack { display: flex; flex-direction: column; gap: 1.5rem; }
        @media (max-width: 768px) {
            .rpt-grid-4 { grid-template-columns: repeat(2, 1fr); }
            .rpt-grid-3 { grid-template-columns: 1fr; }
            .rpt-grid-2 { grid-template-columns: 1fr; }
        }
        .rpt-table { width: 100%; font-size: 0.875rem; border-collapse: collapse; }
        .rpt-table th {
            text-align: left; padding: 0.75rem 1rem; font-weight: 700;
            color: var(--rpt-text-secondary); border-bottom: 2px solid var(--rpt-border);
        }
        .rpt-table td {
            padding: 0.75rem 1rem; border-bottom: 1px solid var(--rpt-border-light);
            color: var(--rpt-text-secondary);
        }
        .rpt-table tr:hover td { background: var(--rpt-bg-hover); }
        .rpt-badge {
            display: inline-block; padding: 0.2rem 0.6rem;
            border-radius: 9999px; font-size: 0.75rem; font-weight: 700;
        }
        .rpt-badge-gold { background: var(--rpt-badge-gold-bg); color: var(--rpt-badge-gold-text); }
        .rpt-badge-silver { background: var(--rpt-badge-silver-bg); color: var(--rpt-badge-silver-text); }
        .rpt-badge-bronze { background: var(--rpt-badge-bronze-bg); color: var(--rpt-badge-bronze-text); }
        .rpt-badge-default { background: var(--rpt-badge-default-bg); color: var(--rpt-badge-default-text); font-weight: 600; }
        .rpt-badge-blue { background: var(--rpt-badge-blue-bg); color: var(--rpt-badge-blue-text); font-weight: 600; }
        .rpt-progress-bg { width: 100%; background: var(--rpt-progress-bg); border-radius: 9999px; height: 8px; overflow: hidden; }
        .rpt-progress-bar { background: #ef4444; height: 100%; border-radius: 9999px; transition: width 0.5s ease; }
        .rpt-chart { padding: 0.5rem 0; }
        .rpt-text-green { color: #16a34a; font-weight: 700; }
        .rpt-text-dim { color: var(--rpt-text-dim); }
        .rpt-text-muted { color: var(--rpt-text-muted); }
        .rpt-voter-name { font-weight: 600; }
        .rpt-voter-email { font-size: 0.75rem; color: var(--rpt-text-dim); }
        .rpt-empty { text-align: center; padding: 2rem; color: var(--rpt-text-dim); }
    </style>

    <div class="rpt-stack">

        {{-- Header --}}
        <div class="rpt-section rpt-header">
            <div class="rpt-header-info">
                @if($this->record->logo_path)
                    <img src="{{ asset('storage/' . $this->record->logo_path) }}" alt="Logo" class="rpt-logo">
                @else
                    <div class="rpt-avatar">{{ substr($this->record->legal_name, 0, 1) }}</div>
                @endif
                <div>
                    <h2 class="rpt-company-name">{{ $this->record->legal_name }}</h2>
                    <p class="rpt-cnpj">CNPJ: {{ preg_replace('/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/', '$1.$2.$3/$4-$5', $this->record->cnpj ?? '') }}</p>
                </div>
            </div>
            <div>
                <select wire:model.live="period" class="rpt-select">
                    <option value="7">Últimos 7 dias</option>
                    <option value="15">Últimos 15 dias</option>
                    <option value="30">Últimos 30 dias</option>
                    <option value="60">Últimos 60 dias</option>
                    <option value="90">Últimos 90 dias</option>
                    <option value="365">Último ano</option>
                </select>
            </div>
        </div>

        {{-- Stats Principais --}}
        <div class="rpt-grid-4">
            <div class="rpt-card">
                <div class="rpt-stat-value" style="color: #ef4444;">{{ number_format($this->totalVotes) }}</div>
                <div class="rpt-stat-label">Total de Votos</div>
            </div>
            <div class="rpt-card">
                <div class="rpt-stat-value" style="color: #16a34a;">{{ number_format($this->todayVotes) }}</div>
                <div class="rpt-stat-label">Votos Hoje</div>
            </div>
            <div class="rpt-card">
                <div class="rpt-stat-value" style="color: #2563eb;">{{ number_format($this->weekVotes) }}</div>
                <div class="rpt-stat-label">Esta Semana</div>
            </div>
            <div class="rpt-card">
                <div class="rpt-stat-value" style="color: #ea580c;">{{ number_format($this->monthVotes) }}</div>
                <div class="rpt-stat-label">Este Mês</div>
            </div>
        </div>

        {{-- Stats Secundários --}}
        <div class="rpt-grid-3">
            <div class="rpt-card">
                <div class="rpt-stat-value-sm" style="color: #7c3aed;">{{ number_format($this->uniqueVoters) }}</div>
                <div class="rpt-stat-label">Votantes Únicos</div>
            </div>
            <div class="rpt-card">
                <div class="rpt-stat-value-sm" style="color: #db2777;">{{ $this->categoriesCount }}</div>
                <div class="rpt-stat-label">Categorias</div>
            </div>
            <div class="rpt-card">
                <div class="rpt-stat-value-sm" style="color: #0d9488;">{{ $this->averageVotesPerDay }}</div>
                <div class="rpt-stat-label">Média por Dia</div>
            </div>
        </div>

        {{-- Ranking por Categoria --}}
        <div class="rpt-section">
            <div class="rpt-title">
                <span style="font-size: 1.25rem;">🏆</span>
                <span>Posição por Categoria</span>
            </div>

            @php $rankings = $this->getCategoryRankings(); @endphp

            @if(count($rankings) > 0)
                <div style="overflow-x: auto;">
                    <table class="rpt-table">
                        <thead>
                            <tr>
                                <th>Categoria</th>
                                <th style="text-align: center;">Posição</th>
                                <th style="text-align: center;">Votos</th>
                                <th style="text-align: center;">% do Total</th>
                                <th style="min-width: 150px;">Participação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rankings as $rank)
                                <tr>
                                    <td style="font-weight: 600;">{{ $rank['category'] }}</td>
                                    <td style="text-align: center;">
                                        @if($rank['position'] === 1)
                                            <span class="rpt-badge rpt-badge-gold">🥇 1º</span>
                                        @elseif($rank['position'] === 2)
                                            <span class="rpt-badge rpt-badge-silver">🥈 2º</span>
                                        @elseif($rank['position'] === 3)
                                            <span class="rpt-badge rpt-badge-bronze">🥉 3º</span>
                                        @else
                                            <span class="rpt-badge rpt-badge-default">{{ $rank['position'] }}º</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="rpt-text-green">{{ $rank['votes'] }}</span>
                                        <span class="rpt-text-dim">/ {{ $rank['total_votes'] }}</span>
                                    </td>
                                    <td style="text-align: center; font-weight: 600;">{{ $rank['percentage'] }}%</td>
                                    <td>
                                        <div class="rpt-progress-bg">
                                            <div class="rpt-progress-bar" style="width: {{ min($rank['percentage'], 100) }}%;"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rpt-empty">Esta empresa ainda não possui categorias vinculadas.</div>
            @endif
        </div>

        {{-- Gráfico Votos por Dia --}}
        <div class="rpt-section">
            <div class="rpt-title">
                <span style="font-size: 1.25rem;">📈</span>
                <span>Votos por Dia (Últimos {{ $this->period }} dias)</span>
            </div>
            @php $dailyChart = $this->getVotesPerDayChart(); @endphp
            <div class="rpt-chart" style="height: 320px;">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        {{-- Grid 2 colunas: Semana + Hora --}}
        <div class="rpt-grid-2">
            <div class="rpt-section">
                <div class="rpt-title">
                    <span style="font-size: 1.25rem;">📅</span>
                    <span>Votos por Dia da Semana</span>
                </div>
                @php $weekdayChart = $this->getWeekdayDistribution(); @endphp
                <div class="rpt-chart" style="height: 280px;">
                    <canvas id="weekdayChart"></canvas>
                </div>
            </div>

            <div class="rpt-section">
                <div class="rpt-title">
                    <span style="font-size: 1.25rem;">🕐</span>
                    <span>Votos por Horário</span>
                </div>
                @php $hourChart = $this->getVotesByHourChart(); @endphp
                <div class="rpt-chart" style="height: 280px;">
                    <canvas id="hourChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Grid 2 colunas: Categorias + Faixa Etária --}}
        <div class="rpt-grid-2">
            <div class="rpt-section">
                <div class="rpt-title">
                    <span style="font-size: 1.25rem;">📊</span>
                    <span>Votos por Categoria</span>
                </div>
                @php $categoryChart = $this->getVotesByCategoryChart(); @endphp
                <div class="rpt-chart" style="height: 280px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <div class="rpt-section">
                <div class="rpt-title">
                    <span style="font-size: 1.25rem;">👥</span>
                    <span>Faixa Etária dos Votantes</span>
                </div>
                @php $ageChart = $this->getAgeDistribution(); @endphp
                <div class="rpt-chart" style="height: 280px;">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Evolução Mensal --}}
        <div class="rpt-section">
            <div class="rpt-title">
                <span style="font-size: 1.25rem;">📆</span>
                <span>Evolução Mensal</span>
            </div>
            @php $monthChart = $this->getVotesPerMonthChart(); @endphp
            <div class="rpt-chart" style="height: 320px;">
                <canvas id="monthChart"></canvas>
            </div>
        </div>

        {{-- Últimos 20 Votos --}}
        <div class="rpt-section">
            <div class="rpt-title">
                <span style="font-size: 1.25rem;">🗳️</span>
                <span>Últimos 20 Votos</span>
            </div>

            @php $recentVotes = $this->getRecentVotes(); @endphp

            @if($recentVotes->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="rpt-table">
                        <thead>
                            <tr>
                                <th>Votante</th>
                                <th>Categoria</th>
                                <th style="text-align: center;">Idade</th>
                                <th style="text-align: right;">Data/Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentVotes as $vote)
                                <tr>
                                    <td>
                                        <div class="rpt-voter-name">{{ $vote->audience?->name ?? 'N/A' }}</div>
                                        <div class="rpt-voter-email">{{ $vote->audience?->email ?? '' }}</div>
                                    </td>
                                    <td>
                                        <span class="rpt-badge rpt-badge-blue">{{ $vote->category?->name ?? 'N/A' }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($vote->audience?->birth_date)
                                            {{ $vote->audience->birth_date->age }} anos
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: right;" class="rpt-text-muted">
                                        {{ $vote->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="rpt-empty">Nenhum voto registrado ainda.</div>
            @endif
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.08)' : 'rgba(0,0,0,0.06)';
            const textColor = isDark ? '#9ca3af' : '#6b7280';
            const pointBorder = isDark ? '#1f2937' : '#ffffff';

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';

            const commonScales = {
                y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: gridColor } },
                x: { grid: { display: false } }
            };
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: commonScales
            };

            // Votos por Dia (Line)
            new Chart(document.getElementById('dailyChart'), {
                type: 'line',
                data: {
                    labels: @json($dailyChart['labels']),
                    datasets: [{
                        label: 'Votos',
                        data: @json($dailyChart['data']),
                        borderColor: 'rgb(239, 68, 68)',
                        backgroundColor: isDark ? 'rgba(239, 68, 68, 0.15)' : 'rgba(239, 68, 68, 0.08)',
                        fill: true, tension: 0.4,
                        pointBackgroundColor: 'rgb(239, 68, 68)',
                        pointBorderColor: pointBorder,
                        pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 7, borderWidth: 3,
                    }]
                },
                options: commonOptions
            });

            // Dia da Semana (Bar)
            new Chart(document.getElementById('weekdayChart'), {
                type: 'bar',
                data: {
                    labels: @json($weekdayChart['labels']),
                    datasets: [{
                        label: 'Votos',
                        data: @json($weekdayChart['data']),
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.75)', 'rgba(59, 130, 246, 0.75)',
                            'rgba(16, 185, 129, 0.75)', 'rgba(245, 158, 11, 0.75)',
                            'rgba(139, 92, 246, 0.75)', 'rgba(236, 72, 153, 0.75)',
                            'rgba(20, 184, 166, 0.75)',
                        ],
                        borderRadius: 8, borderSkipped: false,
                    }]
                },
                options: commonOptions
            });

            // Horário (Bar)
            new Chart(document.getElementById('hourChart'), {
                type: 'bar',
                data: {
                    labels: @json($hourChart['labels']),
                    datasets: [{
                        label: 'Votos',
                        data: @json($hourChart['data']),
                        backgroundColor: 'rgba(59, 130, 246, 0.75)',
                        borderRadius: 4, borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: commonScales.y,
                        x: { grid: { display: false }, ticks: { maxRotation: 90, font: { size: 9 } } }
                    }
                }
            });

            // Categorias (Doughnut)
            const catColors = [
                'rgba(239, 68, 68, 0.8)', 'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(236, 72, 153, 0.8)',
                'rgba(20, 184, 166, 0.8)', 'rgba(251, 146, 60, 0.8)', 'rgba(34, 197, 94, 0.8)',
                'rgba(168, 85, 247, 0.8)',
            ];
            new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($categoryChart['labels']),
                    datasets: [{
                        data: @json($categoryChart['data']),
                        backgroundColor: catColors.slice(0, @json(count($categoryChart['labels']))),
                        borderWidth: 3, borderColor: pointBorder,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '55%',
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14, font: { size: 11 }, color: textColor } } }
                }
            });

            // Faixa Etária (Pie)
            new Chart(document.getElementById('ageChart'), {
                type: 'pie',
                data: {
                    labels: @json($ageChart['labels']),
                    datasets: [{
                        data: @json($ageChart['data']),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)', 'rgba(139, 92, 246, 0.8)', 'rgba(107, 114, 128, 0.8)',
                        ],
                        borderWidth: 3, borderColor: pointBorder,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 14, font: { size: 11 }, color: textColor } } }
                }
            });

            // Evolução Mensal (Bar)
            new Chart(document.getElementById('monthChart'), {
                type: 'bar',
                data: {
                    labels: @json($monthChart['labels']),
                    datasets: [{
                        label: 'Votos',
                        data: @json($monthChart['data']),
                        backgroundColor: 'rgba(16, 185, 129, 0.75)',
                        borderRadius: 8, borderSkipped: false,
                    }]
                },
                options: commonOptions
            });
        });
    </script>
</x-filament-panels::page>
