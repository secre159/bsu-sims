@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-brand-medium text-start text-base font-medium text-brand-medium bg-brand-pale focus:outline-none focus:text-brand-medium focus:bg-brand-light/30 focus:border-brand-medium transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-700 hover:text-brand-primary hover:bg-brand-pale hover:border-brand-light focus:outline-none focus:text-brand-primary focus:bg-brand-pale focus:border-brand-light transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
