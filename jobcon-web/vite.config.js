export default defineConfig({
    plugins: [vue()],
    ssr: {
        noExternal: ['@inertiajs/server']
    },
    build: {
        ssr: true,
        outDir: 'bootstrap/ssr',
        rollupOptions: {
            input: 'resources/js/ssr.js',
        },
    },
});
