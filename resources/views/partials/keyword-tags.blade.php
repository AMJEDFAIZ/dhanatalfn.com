@if (isset($keywords) && $keywords->count() > 0)
<div class="flex gap-2 flex-wrap">
    @foreach ($keywords as $k)
    <a href="{{ route('keywords.show', $k->slug) }}"
        title="{{ $k->name }}"
        class="bg-gray-100 hover:bg-accent hover:text-white px-3 py-1 rounded-full text-sm text-gray-700 transition-colors font-bold">
        {{ $k->name }}
    </a>
    @endforeach
</div>
@endif