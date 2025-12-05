@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 pt-1 border-b-2 border-emerald-400 text-sm font-semibold leading-5 text-white focus:outline-none transition duration-150 ease-in-out relative z-10'
            : 'inline-flex items-center px-3 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white/90 hover:text-white hover:border-emerald-300 focus:outline-none focus:text-white transition duration-150 ease-in-out relative z-10';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if($active) aria-current="page" @endif>
    {{ $slot }}
</a>
