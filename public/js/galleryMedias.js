document.addEventListener('DOMContentLoaded', function() {
    const galleryContainer = document.querySelector('.gallery-container');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    prevButton.addEventListener('click', function() {
        galleryContainer.scrollBy({
            left: -200, // Ajustez la valeur selon la largeur de vos images
            behavior: 'smooth'
        });
    });

    nextButton.addEventListener('click', function() {
        galleryContainer.scrollBy({
            left: 200, // Ajustez la valeur selon la largeur de vos images
            behavior: 'smooth'
        });
    });
});