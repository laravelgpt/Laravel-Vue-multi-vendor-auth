import prettier from 'eslint-config-prettier';

export default [
    {
        ignores: ['vendor', 'node_modules', 'public', 'bootstrap/ssr', 'tailwind.config.js'],
    },
    {
        rules: {
            // Add any custom rules for Livewire/Blade projects here
        },
    },
    prettier,
];
