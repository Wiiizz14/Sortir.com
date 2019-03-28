var carte;
var marker = {};

$(document).ready(() => {
    $("#lieu").empty();
    carte = L.map('macarte').setView([47.838848, -1.609945], 8);
    if (marker != undefined) {
        carte.removeLayer(marker);
    }

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(carte);

    $("#ville").on("change", function () {
        carte.setView([47.838848, -1.609945], 8);
    });

    $("#sorties_Publier").on("click", function () {
        var islieuEmpty = document.getElementById("lieu").innerHTML == ""
        if (islieuEmpty) {
            alert("Veuillez choisir un lieu.");
        }
    })

});

addValuesOfLieu =  () => {
    $("#rue").empty();
    $("#cp").empty();
    document.getElementById("rueloader").style.display = "block";
    document.getElementById("cploader").style.display = "block";
    var lieuId = document.getElementById("lieu").value;
    $.get({
        url: "/api/getLieu",
        data: {lieuId: lieuId}
    })
        .done(
            (lieu) => {
                var rue = document.getElementById("rue");
                var codePostal = document.getElementById("cp");
                rue.innerText = lieu.rue;
                codePostal.innerText = lieu.ville.codePostal;
                document.getElementById("rueloader").style.display = "none";
                document.getElementById("cploader").style.display = "none";

                carte.setView([lieu.latitude, lieu.longitude], 14)
                marker = L.marker([lieu.latitude, lieu.longitude]).addTo(carte);
            }
        )
}

var putLieuInSelect = function () {
    $("#lieu").empty();
    // vide aussi les elements enfants rue et cp
    $("#rue").empty();
    $("#cp").empty();

    document.getElementById("lieu").style.display = "none";
    document.getElementById("loader").style.display = "block";

    var villeId = document.getElementById("ville").value;
    $.get({
        url: "/api/getLieux",
        data: {villeId: villeId},
        statusCode: {
            500: function() {
                document.getElementById("loader").style.display = "none";
            }
        }
    })
        .done(function (apiResult) {
                if (apiResult.length > 0) {
                    for (let lieu of apiResult) {
                        addLieuToSelect(lieu);
                    }
                    document.getElementById("loader").style.display = "none";
                    document.getElementById("lieu").style.display = "block";
                } else
                {
                    document.getElementById("loader").style.display = "none";
                }

            }
        )
}

addLieuToSelect = (lieu) => {
    var bloc = document.getElementById("lieu");
    var option = document.createElement("option");
    option.setAttribute("value", lieu.id);
    option.innerText = lieu.nom;
    bloc.appendChild(option);
    addValuesOfLieu();
}