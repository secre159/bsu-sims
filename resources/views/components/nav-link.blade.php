@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-2 pt-1 border-b-2 border-brand-light bg-white/10 rounded-t text-sm font-semibold leading-5 text-white focus:outline-none focus:border-brand-medium transition duration-150 ease-in-out'
            : 'inline-flex items-center px-2 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white/80 hover:text-white hover:border-brand-light focus:outline-none focus:text-white focus:border-brand-light transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes . ' nav-link-animated']) }} @if($active) aria-current="page" @endif>
    {{ $slot }}
    <span class="nav-link-bar top"></span>
    <span class="nav-link-bar bottom"></span>
</a>
