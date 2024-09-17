document.addEventListener('DOMContentLoaded', function () {
  var carousel = document.querySelector('#monCarousel');

  if (carousel) {
      // Active Bootstrap carousel avec les options souhaitées
      var bsCarousel = new bootstrap.Carousel(carousel, {
          interval: 3000,  // 3 secondes entre chaque slide
          ride: 'carousel'
      });

      // Vérifier et ajouter les événements sur les boutons de contrôle
      var prevButton = document.querySelector('.carousel-control-prev');
      var nextButton = document.querySelector('.carousel-control-next');

      if (prevButton) {
          prevButton.addEventListener('click', function () {
              bsCarousel.prev();
          });
      }

      if (nextButton) {
          nextButton.addEventListener('click', function () {
              bsCarousel.next();
          });
      }
  }
});