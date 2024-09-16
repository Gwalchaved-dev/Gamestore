document.addEventListener('DOMContentLoaded', function () {
    var carousel = document.querySelector('#monCarousel');
    
    // Active Bootstrap carousel
    var bsCarousel = new bootstrap.Carousel(carousel, {
      interval: 3000,  // 3 secondes entre chaque slide
      ride: 'carousel'
    });
  
    // Fonctionnalité des boutons de contrôle
    document.querySelector('.carousel-control-prev').addEventListener('click', function () {
      bsCarousel.prev();
    });
  
    document.querySelector('.carousel-control-next').addEventListener('click', function () {
      bsCarousel.next();
    });
  });