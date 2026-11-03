<form method="GET" style="position:fixed;bottom:8px;right:12px;z-index:60;color:white;background:#1e293b;padding:6px;border-radius:6px">
    <label for="bi-year">Ano</label>
    <select id="bi-year" name="year" onchange="this.form.submit()" style="color:#111827">
        @foreach(\App\Services\ReportYear::options() as $value => $label)
            <option value="{{ $value }}" @selected($year == $value)>{{ $label }}</option>
        @endforeach
    </select>
    @if(request()->routeIs('bi.public') || auth()->check())
        <a href="{{ request()->routeIs('bi.public') ? route('bi.public.export', ['year' => $year]) : route('bi.export', ['year' => $year]) }}" style="margin-left:12px;text-decoration:underline">Extrair Excel</a>
    @endif
</form>
