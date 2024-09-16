/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Importation de jQuery
import $ from 'jquery';

// Importation de Bootstrap pour activer les composants Bootstrap (carrousel, modales, etc.)
import 'bootstrap';

// Importation du fichier SCSS pour inclure le style
import './sass/app.scss'; // Correction du chemin relatif (si ton fichier SCSS est dans `assets/sass`)

// Initialisation du carrousel lorsque le DOM est prêt
$(document).ready(function() {
    // Si tu utilises des fonctionnalités jQuery, tu peux ajouter ton code ici
    
    // Initialisation manuelle du carrousel si nécessaire
    var myCarousel = document.querySelector('#monCarousel');
    if (myCarousel) {  // Assurer que l'élément existe avant de l'initialiser
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 2000,  // Définit l'intervalle de changement de slide à 2000ms (2s)
            ride: 'carousel' // Le carrousel commence automatiquement
        });
    }

    console.log('DOM ready, jQuery and Bootstrap initialized');
});