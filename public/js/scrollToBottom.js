$(document).ready(function() {
    // Vérifier si le paramètre "scrollToBottom" est présent dans l'URL et s'il est un entier
    const currentUrl = window.location.href;

    // Vérifiez si l'URL contient des chiffres à la fin (ce qui pourrait être le paramètre)
    const match = currentUrl.match(/\/(\d+)$/);

    // Si un match est trouvé, faites défiler vers le bas
    if (match) {
        // Fonction pour faire défiler vers le bas de la page
        function scrollToBottom() {
            $('html, body').animate({ scrollTop: $(document).height() }, 'slow');
        }

        // Appeler la fonction pour faire défiler vers le bas
        scrollToBottom();
    }
});