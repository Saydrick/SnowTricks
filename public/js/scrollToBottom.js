$(document).ready(function() {
    // Vérifier si le paramètre "scrollToBottom" est présent dans l'URL et s'il est un entier
    // const currentUrl = window.location.href;

    // // Vérifiez si l'URL contient des chiffres à la fin (ce qui pourrait être le paramètre)
    // const match = currentUrl.match(/\/(\d+)$/);

    // // Si un match est trouvé, faites défiler vers le bas
    // if (match) {
    //     // Fonction pour faire défiler vers le bas de la page
    //     function scrollToBottom() {
    //         $('html, body').animate({ scrollTop: $(document).height() }, 'slow');
    //     }

    //     // Appeler la fonction pour faire défiler vers le bas
    //     scrollToBottom();
    // }
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