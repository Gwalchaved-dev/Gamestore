(self["webpackChunk"] = self["webpackChunk"] || []).push([["carousel"],{

/***/ "./assets/carousel.js":
/*!****************************!*\
  !*** ./assets/carousel.js ***!
  \****************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  var carousel = document.querySelector('#monCarousel');
  if (carousel) {
    // Active Bootstrap carousel avec les options souhaitées
    var bsCarousel = new bootstrap.Carousel(carousel, {
      interval: 3000,
      // 3 secondes entre chaque slide
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

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/carousel.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiY2Fyb3VzZWwuanMiLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7QUFBQUEsUUFBUSxDQUFDQyxnQkFBZ0IsQ0FBQyxrQkFBa0IsRUFBRSxZQUFZO0VBQ3hELElBQUlDLFFBQVEsR0FBR0YsUUFBUSxDQUFDRyxhQUFhLENBQUMsY0FBYyxDQUFDO0VBRXJELElBQUlELFFBQVEsRUFBRTtJQUNWO0lBQ0EsSUFBSUUsVUFBVSxHQUFHLElBQUlDLFNBQVMsQ0FBQ0MsUUFBUSxDQUFDSixRQUFRLEVBQUU7TUFDOUNLLFFBQVEsRUFBRSxJQUFJO01BQUc7TUFDakJDLElBQUksRUFBRTtJQUNWLENBQUMsQ0FBQzs7SUFFRjtJQUNBLElBQUlDLFVBQVUsR0FBR1QsUUFBUSxDQUFDRyxhQUFhLENBQUMsd0JBQXdCLENBQUM7SUFDakUsSUFBSU8sVUFBVSxHQUFHVixRQUFRLENBQUNHLGFBQWEsQ0FBQyx3QkFBd0IsQ0FBQztJQUVqRSxJQUFJTSxVQUFVLEVBQUU7TUFDWkEsVUFBVSxDQUFDUixnQkFBZ0IsQ0FBQyxPQUFPLEVBQUUsWUFBWTtRQUM3Q0csVUFBVSxDQUFDTyxJQUFJLENBQUMsQ0FBQztNQUNyQixDQUFDLENBQUM7SUFDTjtJQUVBLElBQUlELFVBQVUsRUFBRTtNQUNaQSxVQUFVLENBQUNULGdCQUFnQixDQUFDLE9BQU8sRUFBRSxZQUFZO1FBQzdDRyxVQUFVLENBQUNRLElBQUksQ0FBQyxDQUFDO01BQ3JCLENBQUMsQ0FBQztJQUNOO0VBQ0o7QUFDRixDQUFDLENBQUMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvY2Fyb3VzZWwuanMiXSwic291cmNlc0NvbnRlbnQiOlsiZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGZ1bmN0aW9uICgpIHtcbiAgdmFyIGNhcm91c2VsID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignI21vbkNhcm91c2VsJyk7XG5cbiAgaWYgKGNhcm91c2VsKSB7XG4gICAgICAvLyBBY3RpdmUgQm9vdHN0cmFwIGNhcm91c2VsIGF2ZWMgbGVzIG9wdGlvbnMgc291aGFpdMOpZXNcbiAgICAgIHZhciBic0Nhcm91c2VsID0gbmV3IGJvb3RzdHJhcC5DYXJvdXNlbChjYXJvdXNlbCwge1xuICAgICAgICAgIGludGVydmFsOiAzMDAwLCAgLy8gMyBzZWNvbmRlcyBlbnRyZSBjaGFxdWUgc2xpZGVcbiAgICAgICAgICByaWRlOiAnY2Fyb3VzZWwnXG4gICAgICB9KTtcblxuICAgICAgLy8gVsOpcmlmaWVyIGV0IGFqb3V0ZXIgbGVzIMOpdsOpbmVtZW50cyBzdXIgbGVzIGJvdXRvbnMgZGUgY29udHLDtGxlXG4gICAgICB2YXIgcHJldkJ1dHRvbiA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5jYXJvdXNlbC1jb250cm9sLXByZXYnKTtcbiAgICAgIHZhciBuZXh0QnV0dG9uID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmNhcm91c2VsLWNvbnRyb2wtbmV4dCcpO1xuXG4gICAgICBpZiAocHJldkJ1dHRvbikge1xuICAgICAgICAgIHByZXZCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgIGJzQ2Fyb3VzZWwucHJldigpO1xuICAgICAgICAgIH0pO1xuICAgICAgfVxuXG4gICAgICBpZiAobmV4dEJ1dHRvbikge1xuICAgICAgICAgIG5leHRCdXR0b24uYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICAgIGJzQ2Fyb3VzZWwubmV4dCgpO1xuICAgICAgICAgIH0pO1xuICAgICAgfVxuICB9XG59KTsiXSwibmFtZXMiOlsiZG9jdW1lbnQiLCJhZGRFdmVudExpc3RlbmVyIiwiY2Fyb3VzZWwiLCJxdWVyeVNlbGVjdG9yIiwiYnNDYXJvdXNlbCIsImJvb3RzdHJhcCIsIkNhcm91c2VsIiwiaW50ZXJ2YWwiLCJyaWRlIiwicHJldkJ1dHRvbiIsIm5leHRCdXR0b24iLCJwcmV2IiwibmV4dCJdLCJzb3VyY2VSb290IjoiIn0=