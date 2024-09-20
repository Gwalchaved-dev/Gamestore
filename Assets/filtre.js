document.addEventListener('DOMContentLoaded', function () {
    const filterType = document.getElementById('filter-type');
    const minPrice = document.getElementById('min_price');
    const maxPrice = document.getElementById('max_price');
    const genre = document.getElementById('genre');

    // Vérifier que les éléments existent avant d'ajouter des événements
    if (filterType && minPrice && maxPrice && genre) {
        
        // Fonction pour gérer l'affichage des champs de filtre
        const handleFilterChange = () => {
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