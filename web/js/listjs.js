$(document).ready(function(){

    getFirstList();

    // script pour le tri par typing
    $("#myInput").on("keyup", function() {
        var val = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
        });
    });



});

getFirstList = () => {
    document.getElementById("loader").style.display = "block";
    //fonction qui fait une requete get
    $.get({
        url: "/api/searchEvent",
        data: {idSite: user.site}
    }).done(function (apiResult) {
        // on peut ensuite récupérer les valeurs en invoquant les clés
        for (let sortie of apiResult){
            addToList(sortie);
        }
        document.getElementById("loader").style.display = "none";
    });
}


selectionSite = () => {

    var idSite = $("#choixSite option:selected").val();
    var isOrganisateur = $('#isOrganisateur').is(':checked');
    var isInscrit = $('#isInscrit').is(':checked');
    var isNotInscrit = $('#isNotInscrit').is(':checked');
    var isArchive = $('#isArchive').is(':checked');

    //supression du tableau en place
    $("#myTable").empty();
    document.getElementById("loader").style.display = "block";

    //la fonction get peut récupérer un string au format json;
    $.get({
        url: "/api/searchEvent",
        data: {idSite: idSite,
            isOrganisateur: isOrganisateur,
            isInscrit: isInscrit,
            isNotInscrit: isNotInscrit,
            isArchive: isArchive}
    })
        .done(function (apiResult) {

            console.log(apiResult);

            // on peut ensuite récupérer les valeurs en invoquant les clés
            for (let sortie of apiResult){
                addToList(sortie);
            }
            document.getElementById("loader").style.display = "none";
        });

}

function addToList(sortie) {
    var isInscrit = 0;
    //bloc tr
    var bloc = document.createElement("tr");
    // liste des éléments du tableau
    var nom = document.createElement('td');
    var dateDebut = document.createElement('td');
    var dateCloture = document.createElement('td');
    var inscriptions = document.createElement('td');
    var etat = document.createElement('td');
    var getInscription = document.createElement('td');
    var organisateur = document.createElement('td');
    var action = document.createElement('td');

    // insersion des données dans les éléments
    nom.innerText = sortie.nom;
    sortie.dateDebut = new Date(sortie.dateDebut);
    dateDebut.innerText = (sortie.dateDebut.getDate() + 1) + '/' + (sortie.dateDebut.getMonth() + 1) + '/'
        +  sortie.dateDebut.getFullYear()
        + " à " + sortie.dateDebut.getHours() + ":" + sortie.dateDebut.getMinutes();
    sortie.dateCloture = new Date(sortie.dateCloture);
    dateCloture.innerText = (sortie.dateCloture.getDate() + 1) + '/' + (sortie.dateCloture.getMonth() + 1) + '/' +  sortie.dateCloture.getFullYear(); // idem
    inscriptions.innerText = sortie.participants.length + "/" + sortie.nbInscriptionsMax
    etat.innerText = sortie.etat.libelle;

    // recherche de l'état d'inscription
    if (sortie.organisateur.username !== user.username)
    {
        for (let participant of sortie.participants) {
            if (participant.username === user.username) {
                getInscription.innerText = "X";
                isInscrit = 1;
            }
        }
    }
    organisateur.innerText = sortie.organisateur.username;

    // conditions d'affichage des actions
    if (sortie.dateCloture > new Date())
    {
        if (sortie.organisateur.username == user.username)
        {
            // creation d'un element a et insertion attribut href
            var httpModifier = document.createElement("a");
            httpModifier.setAttribute("href", "/updateEvent/" + sortie.id);
            httpModifier.innerText = "Modifier";
            action.appendChild(httpModifier);

            // creation d'un element a et insertion attribut href et onClick pour invoquer la methode js
            var httpAnnuler = document.createElement("a");

        }
        else
        {
            if (isInscrit)
            {
                // creation d'un element a et insertion attribut href et onClick pour invoquer la methode js
                var httpDesinscription = document.createElement("a");
                httpDesinscription.setAttribute("href", "#");
                httpDesinscription.setAttribute("onclick", "seDesinscrire("+ sortie.id +")");
                httpDesinscription.innerText = "Se désinscrire";
                action.appendChild(httpDesinscription);
            } else
            {
                // creation d'un element a et insertion attribut href et onClick pour invoquer la methode js
                var httpInscription = document.createElement("a");
                httpInscription.setAttribute("href", "#" + sortie.id);
                httpInscription.setAttribute("onclick", "sInscrire(" + sortie.id + ")");
                httpInscription.innerText = "S'inscrire";
                action.appendChild(httpInscription);
            }
        }
    }
    if (sortie.dateCloture > new Date() && organisateur.username === user.username)
    {
        var httpAnnuler = document.createElement("a");
        httpAnnuler.setAttribute("href", "#" + sortie.id);
        httpAnnuler.innerText = "Annuler";
        action.appendChild(httpAnnuler);
    }

    // insersion des td dans le le bloc
    bloc.appendChild(nom);
    bloc.appendChild(dateDebut);
    bloc.appendChild(dateCloture);
    bloc.appendChild(inscriptions);
    bloc.appendChild(etat);
    bloc.appendChild(getInscription);
    bloc.appendChild(organisateur);
    bloc.append(action);
    $("#myTable").append(bloc);
}

var seDesinscrire = (id) => {
    if (confirm("Etes-vous sûr de vouloir vous désister ?"))
    {
        var idSortie = id
        console.log(idSortie);
        $.ajax(
            {
                url: "api/unRegisterEvent/",
                type: "POST",
                data: {"idSortie": idSortie}
            },


        ).done(
            selectionSite()
    )
    }
}

var sInscrire = (id) => {
    var idSortie = id
    console.log(idSortie);
    if (confirm("Confirmez votre inscription"))
    {
        $.ajax(
            {
                url: "api/registerEvent/",
                type: "POST",
                data: {"idSortie": idSortie}
            },


        ).done(
            selectionSite()
        )

    }
}

