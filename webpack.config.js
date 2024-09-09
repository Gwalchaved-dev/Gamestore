const Encore = require('@symfony/webpack-encore');
const CopyPlugin = require('copy-webpack-plugin'); // Import du plugin

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Dossier de sortie des fichiers compilés
    .setOutputPath('public/build/')
    // Chemin public pour accéder aux fichiers compilés
    .setPublicPath('/build')

    // Entrée JavaScript
    .addEntry('app', './assets/app.js')

    // Entrée SCSS
    .addStyleEntry('styles/app_css', './assets/sass/app.scss')

    // Activer le "runtime" pour avoir un fichier runtime.js séparé
    .enableSingleRuntimeChunk()

    // Activer le support de Sass/SCSS
    .enableSassLoader()

    // Activer les sourcemaps pour le développement
    .enableSourceMaps(!Encore.isProduction())

    // Activer le versioning des fichiers (hashing) pour la production
    .enableVersioning(Encore.isProduction())

    // Nettoyer le dossier de sortie avant chaque build
    .cleanupOutputBeforeBuild()

    // Configurer Babel pour utiliser les polyfills en fonction des besoins
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // Configurer le serveur de développement (hot-reload)
    .configureDevServerOptions(options => {
        options.liveReload = true;
        options.watchFiles = ['templates/**/*.html.twig', 'assets/sass/**/*.scss', 'assets/js/**/*.js'];
    })

    // Ajout du plugin pour copier les fichiers d'images
    .addPlugin(new CopyPlugin({
        patterns: [
            { from: './assets/images', to: 'images' } // Copier toutes les images dans public/build/images
        ]
    }))
;

module.exports = Encore.getWebpackConfig();