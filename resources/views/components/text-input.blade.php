@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-brand-medium focus:ring-brand-medium rounded-md shadow-sm']) }}>
