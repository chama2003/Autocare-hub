@props(['class' => '', 'title' => null, 'action' => null])

<div {{ $attributes->merge(['class' => 'card-shell flex h-full flex-col p-5 ' . $class]) }}>
    @if($title || $action)
        <div class="mb-4 flex items-center justify-between gap-3">
            @if($title)
                <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
            @endif
            @if($action)
                <div>{{ $action }}</div>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
