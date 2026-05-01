import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ command }) => ({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/js/app.js',
                'resources/css/style.scss',
                'resources/js/style.js'
            ],
            refresh: true,
        }),
    ],
}));


//ortak alan
//--------------------------------------------------------------
// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';
//
// export default defineConfig(({ command }) => ({
//     server: {
//         host: '0.0.0.0', // Vite sunucusunu dışarıya açar
//         hmr: {
//             host: '192.168.1.118' // Kendi yerel IP adresini buraya yazmalısın
//         },
//     },
//     plugins: [
//         laravel({
//             input: [
//                 'resources/css/app.scss',
//                 'resources/js/app.js',
//                 'resources/css/style.scss',
//                 'resources/js/style.js'
//             ],
//             refresh: true,
//         }),
//     ],
// }));
