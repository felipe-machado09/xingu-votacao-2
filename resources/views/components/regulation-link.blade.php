@if($regulationUrl = \App\Models\LandingPageSection::regulationUrl())
    <a href="{{ $regulationUrl }}" target="_blank" rel="noopener" class="block text-center py-3 underline">Regulamento</a>
@endif
