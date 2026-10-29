@props(['disabled' => false, 'error' => null, 'label' => null, 'name' => null, 'type' => 'text'])

<div>
    @if($label)
        <label for="{{ $name ?? $attributes->get('id') }}" class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-200">{{ $label }}</label>
    @endif

    <input
        {{ $disabled ? 'disabled' : '' }}
        type="{{ $type }}"
        {{ $attributes->class([
            'input-base',
            'border-rose-300 focus:border-rose-400 focus:ring-rose-100 dark:border-rose-500/40 dark:focus:ring-rose-500/20' => $error,
        ]) }}
    >

    @if($error)
        <p class="mt-2 text-xs font-medium text-rose-600 dark:text-rose-300">{{ $error }}</p>
    @endif
</div>
