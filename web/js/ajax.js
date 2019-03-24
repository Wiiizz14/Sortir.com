$(document).ready(function(){

    $( "select" ).change(function() {

        })

     selectionSite = () => {
        console.log('je suis dans la fonction')
        //var site = "/get/" + $('#choixSite option:selected').val();
        //console.log(site);
        //la fonction getJson permet de récupérer un string au format json
        $.getJSON( {
            url: "/get",
            format: "json",
            success: alert('ca marche')
        })
            .done(function (data) {
                // JSON.parse pour obtenir un objet depuis le json
                var objets = JSON.parse(data);
                console.log(objets)
                // on peut ensuite récupérer les valeurs en invoquant les clés
                console.log(truc.nom)
                for (let objet of objets){
                    addToList(objet);
                }
            });
    }

    function addToList(objet) {
        console.log(objet);
        $("#mytable").append(
            '<tr>' +
            '<td>'+ objet.nom +'</td>' +
            '<td>'+ objet.participants +'</td>' +
            '</tr>'
        )
    }

    // script pour le tri par typing
    $("#myInput").on("keyup", function() {
        var val = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
        });
    });

});

