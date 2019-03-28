$(document).ready(() => {
    $("#lieu").empty();
    var carte = L.map('macarte').setView([46.3630104, 2.9846608], 6);
    console.log(carte);

    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(carte);

    //putLieuInSelect();

    $("#sorties_Publier").on("click", function () {
        var islieuEmpty = document.getElementById("lieu").innerHTML == ""
        console.log(islieuEmpty)
        if (islieuEmpty) {
            alert("Veuillez choisir un lieu.");
        }
    })

});

var putLieuInSelect = () => {
    $("#lieu").empty();
    // vide aussi les elements enfants rue et cp
    $("#rue").empty();
    $("#cp").empty();
    document.getElementById("lieu").style.display = "none";
    document.getElementById("loader").style.display = "block";

    var villeId = document.getElementById("ville").value;
    console.log(villeId);
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
                console.log("val resultat")
                console.log(apiResult)
                for (let lieu of apiResult) {
                    console.log("lieu");
                    console.log(lieu)
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
    console.log(option);
    bloc.appendChild(option);
    addValuesOfLieu();

}

addValuesOfLieu =  () => {
    $("#rue").empty();
    $("#cp").empty();
    document.getElementById("rueloader").style.display = "block";
    document.getElementById("cploader").style.display = "block";
    var lieuId = document.getElementById("lieu").value;
    console.log(lieuId);
    $.get({
            url: "/api/getLieu",
        data: {lieuId: lieuId}
    })
        .done(
        (lieu) => {
            console.log(lieu)
            var rue = document.getElementById("rue");
            var codePostal = document.getElementById("cp");
            rue.innerText = lieu.rue;
            codePostal.innerText = lieu.ville.codePostal;
            document.getElementById("rueloader").style.display = "none";
            document.getElementById("cploader").style.display = "none";
            console.log(lieu.latitude);
            console.log(lieu.longitude);
            var marker = L.marker([lieu.latitude, lieu.longitude]).addTo(carte);
        }

    )
}