document.addEventListener('DOMContentLoaded', function () {
  // Sélectionner toutes les miniatures
  const thumbnails = document.querySelectorAll('.image-gallery img');
  // Sélectionner l'image principale
  const mainImage = document.querySelector('.main-image img');

  // Ajouter un écouteur d'événements sur chaque miniature
  thumbnails.forEach(function (thumbnail) {
      thumbnail.addEventListener('click', function () {
          // Remplacer la source de l'image principale par la source de la miniature cliquée
          mainImage.src = this.src;
      });
  });
});