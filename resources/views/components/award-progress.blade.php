<div class="mb-12 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-2xl shadow-xl p-6 md:p-8">
    <h2 class="text-2xl font-extrabold text-center mb-3">🎁 Concorra aos Prêmios!</h2>
    <p class="text-center text-gray-700 mb-6">Confira os requisitos de cada prêmio. A participação nos sorteios segue o regulamento.</p>
    @foreach($awards->groupBy('tier')->sortKeys() as $tier => $tierAwards)
        <div class="mb-8">
            <h3 class="text-xl font-bold mb-4">Nível {{ $tier }} - {{ match ((int) $tier) { 1 => 'Prêmios Básicos', 2 => 'Prêmios Intermediários', 3 => 'Prêmio Máximo' } }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($tierAwards as $award)
                    @php
                        $required = $award->requiredVotes();
                        $progress = $prizeProgress[$award->id];
                        $unlocked = $progress >= $required;
                    @endphp
                    <div class="bg-white rounded-xl shadow-md p-4 {{ $audience && $unlocked ? 'ring-2 ring-green-400' : '' }}">
                        @if($award->image_path)
                            <img src="{{ asset('storage/'.$award->image_path) }}" alt="{{ $award->name }}" class="h-32 w-full object-contain mb-3">
                        @endif
                        <h4 class="text-lg font-bold">{{ $award->name }}</h4>
                        @if($award->description)
                            <p class="text-gray-600 text-sm mb-2">{{ $award->description }}</p>
                        @endif
                        <p class="my-2">Vote em <strong>{{ $award->requirementLabel() }}</strong> para concorrer.</p>
                        <span class="text-sm">{{ $award->remainingQuantity() }} disponíveis</span>
                        @if($audience)
                            <p class="mt-3 font-semibold {{ $unlocked ? 'text-green-700' : 'text-orange-700' }}">
                                {{ $progress }} / {{ $required }} votos —
                                @if($unlocked)
                                    ✓ Requisito de votos atingido
                                @else
                                    Faltam {{ $required - $progress }} votos
                                @endif
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
