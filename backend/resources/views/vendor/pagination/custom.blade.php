@if ($paginator->lastPage() > 1)
<div class="flex justify-center gap-2 mt-12">
    @foreach (range(1, $paginator->lastPage()) as $p)
        <a
            href="{{ $paginator->url($p) }}"
            class="w-9 h-9 text-sm font-medium transition-colors inline-flex items-center justify-center
                   {{ $p === $paginator->currentPage() ? 'bg-primary-700 text-white' : 'text-gray-400 hover:text-gray-800' }}"
        >{{ $p }}</a>
    @endforeach
</div>
@endif
