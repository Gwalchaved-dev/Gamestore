const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // Public path used by the web server to access the output path
    .setPublicPath('public/build')

    // JavaScript entry
    .addEntry('app', './assets/app.js')

    // SCSS entry
    .addStyleEntry('styles/app_css', './assets/sass/app.scss') // Nom unique pour l'entrée CSS

    // Enable single runtime chunk (fix required)
    .enableSingleRuntimeChunk()

    // Enable Sass/SCSS loader
    .enableSassLoader()

    // Enable source maps during development
    .enableSourceMaps(!Encore.isProduction())

    // Enable versioning of files for production
    .enableVersioning(Encore.isProduction())

    // Clean up output before each build
    .cleanupOutputBeforeBuild()

    // Enable hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // Configure Babel, with polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    // Configure the DevServer with live reload
    .configureDevServerOptions(options => {
        options.liveReload = true;
        options.watchFiles = ['templates/**/*.html.twig', 'assets/sass/**/*.scss', 'assets/js/**/*.js'];
    })
;

module.exports = Encore.getWebpackConfig();