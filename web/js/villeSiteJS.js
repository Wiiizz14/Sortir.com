/**
 * Permet de gérer la recherche dynamique sur les pages de gestion des villes et des sites.
 */
$(document).ready(function(){

    // script pour le tri par typing
    $("#myInput").on("keyup", function() {
        var val = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
        });
    });

});