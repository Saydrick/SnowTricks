$(document).ready(function() {
    // Obtenir l'URL actuelle
    const currentUrl = window.location.href;

    // Créer une instance de URLSearchParams avec la partie query de l'URL
    const urlParams = new URLSearchParams(window.location.search);

    // Vérifier si le paramètre 'limit' existe et obtenir sa valeur
    const limit = urlParams.get('limit');

    if (limit !== null) {
        // Convertir la valeur de 'limit' en entier
        const limitValue = parseInt(limit, 10);

        // Si la valeur de 'limit' est un nombre valide, scroller en bas de la page
        if (!isNaN(limitValue)) {
            window.scrollTo(0, document.body.scrollHeight);
        }
    }
});