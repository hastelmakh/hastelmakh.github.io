// Import all assets (workaround for vite to copy assets to dist)
Object.values(import.meta.glob('/src/**/assets/**/*.!(css|js)', {eager: true, query: 'url'}));
console.log('This should not be executed.');
