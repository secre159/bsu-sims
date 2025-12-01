<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white border border-brand-light rounded-md font-semibold text-xs text-brand-primary uppercase tracking-widest shadow-sm hover:bg-brand-pale focus:outline-none focus:ring-2 focus:ring-brand-medium focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
