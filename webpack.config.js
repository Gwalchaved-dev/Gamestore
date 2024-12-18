const Encore = require('@symfony/webpack-encore');
const CopyPlugin = require('copy-webpack-plugin'); // Import du plugin pour copier les fichiers
const path = require('path'); // Import du module path

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Dossier de sortie des fichiers compilés
    .setOutputPath('public/build/')
    
    // Chemin public pour accéder aux fichiers compilés
    .setPublicPath('/build')

    // Définir le préfixe du manifest (clé nécessaire pour les URLs absolues dans les templates)
    .setManifestKeyPrefix('build/')

    // Entrée JavaScript pour le fichier de filtrage
    .addEntry('filtre', './assets/filtre.js')  

    // Entrée JavaScript pour le fichier de galerie Modal
    .addEntry('galerieModal', './assets/galerieModal.js')  

    // Ajouter la ligne suivante pour inclure carousel.js
    .addEntry('carousel', './assets/carousel.js')  

    // Entrée JavaScript
    .addEntry('app', './assets/app.js')

    // Entrée SCSS (pour le CSS principal)
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

    // Copier les fichiers d'images vers le dossier public/build/images
    .addPlugin(new CopyPlugin({
        patterns: [
            { from: './assets/images', to: 'images', force: true } // Copier toutes les images dans public/build/images
        ]
    }))

    // Configurer Babel pour utiliser les polyfills en fonction des besoins
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    });

module.exports = Encore.getWebpackConfig();