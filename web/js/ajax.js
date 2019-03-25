$(document).ready(function(){
    console.log(user);
    $( "select" ).change(function() {


        })

     selectionSite = () => {
        console.log('je suis dans la fonction')
        //var site = "/get/" + $('#choixSite option:selected').val();
        //la fonction getJson permet de récupérer un string au format json
        $.getJSON( {
            url: "/get",
            format: "json",
        })
            .done(function (apiResult) {
                // JSON.parse pour obtenir un objet depuis le json
                //supression du tableau en place
                $("#myTable").empty();
                // on peut ensuite récupérer les valeurs en invoquant les clés
                for (let sortie of apiResult){
                    addToList(sortie);
                }
            });
    }

    function addToList(sortie) {
        var isInscrit = 0;

        console.log(sortie);
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
        nom.innerText = sortie.nom
        dateDebut.innerText = sortie.dateDebut; // a reformatter
        dateCloture.innerText = sortie.dateCloture; // idem
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
            if (organisateur.username === user.username)
            {
                var httpModifier = document.createElement("a");
                httpModifier.setAttribute("href", "/updateEvent/" + sortie.id);
                httpModifier.innerText = "Modifier";
                action.appendChild(httpModifier);
            }
            else
            {
                if (isInscrit)
                {
                    var httpInscription = document.createElement("a");
                    httpInscription.setAttribute("href", "#" + sortie.id);
                    httpInscription.innerText = "S'inscrire";
                    action.appendChild(httpInscription);
                } else
                {
                    var httpDesinscription = document.createElement("a");
                    httpDesinscription.setAttribute("href", "#" + sortie.id);
                    httpDesinscription.innerText = "Se désincrire";
                    action.appendChild(httpDesinscription);
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
        console.log(bloc)
        bloc.append(action);
        $("#myTable").append(bloc);
    }

    // script pour le tri par typing
    $("#myInput").on("keyup", function() {
        var val = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
        });
    });

});

