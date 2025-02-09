import { globSync } from 'glob';

export default {
    build: {
        outDir: './dist_assets',
        assetsDir: './assets', // location of assets in dist (base dir referenced in manifest)
        manifest: 'manifest.json',
        emptyOutDir: true,
        publicDir: false,
        assetsInlineLimit: 0, // disable inlining of assets
        rollupOptions: {
            input: [
                ...globSync('./src/Shared/Resources/assets/asset_importer.js'), // referencing assets to be copied to dist
                ...globSync('./src/*/Resources/**/assets/**/main.js'), // entry-points
            ],
        },
    },
    server: {
        host: true, // makes HMR accessible on network
        port: 5173,
        cors: true, // makes requests from all hosts allowed
        origin: '//localhost:5173', // required to correctly resolve CSS imports
    },
    css: {
        devSourcemap: true, // enable sourcemaps in dev (only)
    },
}
