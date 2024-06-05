document.addEventListener('DOMContentLoaded', function() {
    // Fonction pour ajouter un nouvel élément MediaType
    function addMediaType() {
        // Récupérer le conteneur des champs de médias
        var mediaContainer = document.getElementById('medias-fields');
        
        // Récupérer le prototype du formulaire pour MediaType
        var mediaPrototype = mediaContainer.getAttribute('data-prototype');
        
        // Obtenir le nombre actuel de champs de médias
        var mediaIndex = mediaContainer.children.length;
    
        // Remplacer "__name__" par un identifiant unique pour le nouveau champ
        var newMediaPrototype = mediaPrototype.replace(/__name__/g, mediaIndex);
    
        // Créer un nouvel élément div pour le champ de média
        var mediaDiv = document.createElement('div');
        mediaDiv.innerHTML = newMediaPrototype;
    
        // Ajouter le nouvel élément MediaType au conteneur des champs de médias
        mediaContainer.appendChild(mediaDiv);
    }

    // Écouter l'événement de clic sur le bouton "Add Media"
    var addMediaButton = document.getElementById('add_media');
    if (addMediaButton) {
        addMediaButton.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du lien
            addMediaType(); 
        });
    }
});