<div class="h-[100px]">
    <div class="bg-gradient-to-r from-red-600 to-red-700 shadow-2xl text-white text-center flex items-center justify-center h-full px-4">
        <div class="flex items-center gap-4 md:gap-6 flex-wrap justify-center">
            <h2 class="text-lg md:text-xl font-bold">ENCERRA EM</h2>
            <div class="countdown-timer flex gap-3 md:gap-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-2 min-w-[60px]">
                    <div class="text-2xl md:text-3xl font-bold countdown-days">00</div>
                    <div class="text-xs mt-1 opacity-90">Dias</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-2 min-w-[60px]">
                    <div class="text-2xl md:text-3xl font-bold countdown-hours">00</div>
                    <div class="text-xs mt-1 opacity-90">Horas</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-2 min-w-[60px]">
                    <div class="text-2xl md:text-3xl font-bold countdown-minutes">00</div>
                    <div class="text-xs mt-1 opacity-90">Minutos</div>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-2 min-w-[60px]">
                    <div class="text-2xl md:text-3xl font-bold countdown-seconds">00</div>
                    <div class="text-xs mt-1 opacity-90">Segundos</div>
                </div>
            </div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
(function() {
    function updateCountdown() {
        const endDate = new Date('2026-03-15T23:59:59').getTime();
        const now = new Date().getTime();
        const distance = endDate - now;

        document.querySelectorAll('.countdown-timer').forEach(timer => {
            if (distance < 0) {
                timer.innerHTML = '<div class="text-2xl font-bold">Votação Encerrada!</div>';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            const daysEl = timer.querySelector('.countdown-days');
            const hoursEl = timer.querySelector('.countdown-hours');
            const minutesEl = timer.querySelector('.countdown-minutes');
            const secondsEl = timer.querySelector('.countdown-seconds');

            if (daysEl) daysEl.textContent = String(days).padStart(2, '0');
            if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
            if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
            if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
        });
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
})();
</script>
@endpush
@endonce
