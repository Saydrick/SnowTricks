document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.show-media-button').addEventListener('click', function() {
        const gallery = document.querySelector('.gallery');
        if (gallery.style.display === 'block') {
            gallery.style.display = 'none';
            this.textContent = 'Voir médias';
        } else {
            gallery.style.display = 'block';
            this.textContent = 'Masquer médias';
        }
    });
});