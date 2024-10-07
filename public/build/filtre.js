(self["webpackChunk"] = self["webpackChunk"] || []).push([["filtre"],{

/***/ "./assets/filtre.js":
/*!**************************!*\
  !*** ./assets/filtre.js ***!
  \**************************/
/***/ (() => {

document.addEventListener('DOMContentLoaded', function () {
  var filterType = document.getElementById('filter-type');
  var minPrice = document.getElementById('min_price');
  var maxPrice = document.getElementById('max_price');
  var genre = document.getElementById('genre');

  // Vérifier que les éléments existent avant d'ajouter des événements
  if (filterType && minPrice && maxPrice && genre) {
    // Fonction pour gérer l'affichage des champs de filtre
    var handleFilterChange = function handleFilterChange() {
      if (filterType.value === 'price') {
        minPrice.style.display = 'block';
        maxPrice.style.display = 'block';
        genre.style.display = 'none';
      } else if (filterType.value === 'genre') {
        genre.style.display = 'block';
        minPrice.style.display = 'none';
        maxPrice.style.display = 'none';
      } else {
        minPrice.style.display = 'none';
        maxPrice.style.display = 'none';
        genre.style.display = 'none';
      }
    };

    // Gérer le changement de type de filtre
    filterType.addEventListener('change', handleFilterChange);

    // Initialiser l'affichage correct au chargement de la page
    handleFilterChange();
  }
});

/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ var __webpack_exports__ = (__webpack_exec__("./assets/filtre.js"));
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiZmlsdHJlLmpzIiwibWFwcGluZ3MiOiI7Ozs7Ozs7O0FBQUFBLFFBQVEsQ0FBQ0MsZ0JBQWdCLENBQUMsa0JBQWtCLEVBQUUsWUFBWTtFQUN0RCxJQUFNQyxVQUFVLEdBQUdGLFFBQVEsQ0FBQ0csY0FBYyxDQUFDLGFBQWEsQ0FBQztFQUN6RCxJQUFNQyxRQUFRLEdBQUdKLFFBQVEsQ0FBQ0csY0FBYyxDQUFDLFdBQVcsQ0FBQztFQUNyRCxJQUFNRSxRQUFRLEdBQUdMLFFBQVEsQ0FBQ0csY0FBYyxDQUFDLFdBQVcsQ0FBQztFQUNyRCxJQUFNRyxLQUFLLEdBQUdOLFFBQVEsQ0FBQ0csY0FBYyxDQUFDLE9BQU8sQ0FBQzs7RUFFOUM7RUFDQSxJQUFJRCxVQUFVLElBQUlFLFFBQVEsSUFBSUMsUUFBUSxJQUFJQyxLQUFLLEVBQUU7SUFFN0M7SUFDQSxJQUFNQyxrQkFBa0IsR0FBRyxTQUFyQkEsa0JBQWtCQSxDQUFBLEVBQVM7TUFDN0IsSUFBSUwsVUFBVSxDQUFDTSxLQUFLLEtBQUssT0FBTyxFQUFFO1FBQzlCSixRQUFRLENBQUNLLEtBQUssQ0FBQ0MsT0FBTyxHQUFHLE9BQU87UUFDaENMLFFBQVEsQ0FBQ0ksS0FBSyxDQUFDQyxPQUFPLEdBQUcsT0FBTztRQUNoQ0osS0FBSyxDQUFDRyxLQUFLLENBQUNDLE9BQU8sR0FBRyxNQUFNO01BQ2hDLENBQUMsTUFBTSxJQUFJUixVQUFVLENBQUNNLEtBQUssS0FBSyxPQUFPLEVBQUU7UUFDckNGLEtBQUssQ0FBQ0csS0FBSyxDQUFDQyxPQUFPLEdBQUcsT0FBTztRQUM3Qk4sUUFBUSxDQUFDSyxLQUFLLENBQUNDLE9BQU8sR0FBRyxNQUFNO1FBQy9CTCxRQUFRLENBQUNJLEtBQUssQ0FBQ0MsT0FBTyxHQUFHLE1BQU07TUFDbkMsQ0FBQyxNQUFNO1FBQ0hOLFFBQVEsQ0FBQ0ssS0FBSyxDQUFDQyxPQUFPLEdBQUcsTUFBTTtRQUMvQkwsUUFBUSxDQUFDSSxLQUFLLENBQUNDLE9BQU8sR0FBRyxNQUFNO1FBQy9CSixLQUFLLENBQUNHLEtBQUssQ0FBQ0MsT0FBTyxHQUFHLE1BQU07TUFDaEM7SUFDSixDQUFDOztJQUVEO0lBQ0FSLFVBQVUsQ0FBQ0QsZ0JBQWdCLENBQUMsUUFBUSxFQUFFTSxrQkFBa0IsQ0FBQzs7SUFFekQ7SUFDQUEsa0JBQWtCLENBQUMsQ0FBQztFQUN4QjtBQUNKLENBQUMsQ0FBQyIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9maWx0cmUuanMiXSwic291cmNlc0NvbnRlbnQiOlsiZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGZ1bmN0aW9uICgpIHtcbiAgICBjb25zdCBmaWx0ZXJUeXBlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2ZpbHRlci10eXBlJyk7XG4gICAgY29uc3QgbWluUHJpY2UgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbWluX3ByaWNlJyk7XG4gICAgY29uc3QgbWF4UHJpY2UgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnbWF4X3ByaWNlJyk7XG4gICAgY29uc3QgZ2VucmUgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnZ2VucmUnKTtcblxuICAgIC8vIFbDqXJpZmllciBxdWUgbGVzIMOpbMOpbWVudHMgZXhpc3RlbnQgYXZhbnQgZCdham91dGVyIGRlcyDDqXbDqW5lbWVudHNcbiAgICBpZiAoZmlsdGVyVHlwZSAmJiBtaW5QcmljZSAmJiBtYXhQcmljZSAmJiBnZW5yZSkge1xuICAgICAgICBcbiAgICAgICAgLy8gRm9uY3Rpb24gcG91ciBnw6lyZXIgbCdhZmZpY2hhZ2UgZGVzIGNoYW1wcyBkZSBmaWx0cmVcbiAgICAgICAgY29uc3QgaGFuZGxlRmlsdGVyQ2hhbmdlID0gKCkgPT4ge1xuICAgICAgICAgICAgaWYgKGZpbHRlclR5cGUudmFsdWUgPT09ICdwcmljZScpIHtcbiAgICAgICAgICAgICAgICBtaW5QcmljZS5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICBtYXhQcmljZS5zdHlsZS5kaXNwbGF5ID0gJ2Jsb2NrJztcbiAgICAgICAgICAgICAgICBnZW5yZS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgfSBlbHNlIGlmIChmaWx0ZXJUeXBlLnZhbHVlID09PSAnZ2VucmUnKSB7XG4gICAgICAgICAgICAgICAgZ2VucmUuc3R5bGUuZGlzcGxheSA9ICdibG9jayc7XG4gICAgICAgICAgICAgICAgbWluUHJpY2Uuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgICAgICBtYXhQcmljZS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICBtaW5QcmljZS5zdHlsZS5kaXNwbGF5ID0gJ25vbmUnO1xuICAgICAgICAgICAgICAgIG1heFByaWNlLnN0eWxlLmRpc3BsYXkgPSAnbm9uZSc7XG4gICAgICAgICAgICAgICAgZ2VucmUuc3R5bGUuZGlzcGxheSA9ICdub25lJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgfTtcblxuICAgICAgICAvLyBHw6lyZXIgbGUgY2hhbmdlbWVudCBkZSB0eXBlIGRlIGZpbHRyZVxuICAgICAgICBmaWx0ZXJUeXBlLmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGhhbmRsZUZpbHRlckNoYW5nZSk7XG5cbiAgICAgICAgLy8gSW5pdGlhbGlzZXIgbCdhZmZpY2hhZ2UgY29ycmVjdCBhdSBjaGFyZ2VtZW50IGRlIGxhIHBhZ2VcbiAgICAgICAgaGFuZGxlRmlsdGVyQ2hhbmdlKCk7XG4gICAgfVxufSk7Il0sIm5hbWVzIjpbImRvY3VtZW50IiwiYWRkRXZlbnRMaXN0ZW5lciIsImZpbHRlclR5cGUiLCJnZXRFbGVtZW50QnlJZCIsIm1pblByaWNlIiwibWF4UHJpY2UiLCJnZW5yZSIsImhhbmRsZUZpbHRlckNoYW5nZSIsInZhbHVlIiwic3R5bGUiLCJkaXNwbGF5Il0sInNvdXJjZVJvb3QiOiIifQ==