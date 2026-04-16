{{--
  Breadcrumb component.
  Props: $items — array of ['label' => '...', 'url' => '...'] (url is optional for last item)
  Usage: <x-breadcrumb :items="[['label'=>'Pages','url'=>route('admin.pages.index')],['label'=>'Edit']]" />
--}}
@props(['items' => []])

@if(count($items))
    <nav class="flex items-center gap-1 text-sm" aria-label="Breadcrumb">
        @foreach($items as $i => $item)
            @if($i > 0)
                <svg class="w-3.5 h-3.5 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            @endif

            @if(isset($item['url']) && $i < count($items) - 1)
                <a href="{{ $item['url'] }}"
                   class="text-slate-400 hover:text-slate-600 font-medium transition-colors truncate max-w-[120px]">
                    {{ $item['label'] }}
                </a>
            @else
                <span class="font-semibold text-slate-700 truncate max-w-[160px]">{{ $item['label'] }}</span>
            @endif
        @endforeach
    </nav>
@endif
