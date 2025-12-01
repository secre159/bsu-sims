<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-brand-deep border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-brand-primary focus:bg-brand-primary active:bg-brand-deep focus:outline-none focus:ring-2 focus:ring-brand-medium focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
