$(document).ready(function(){
    var value = "";
    $( "select" ).change(function() {
            $( "select option:selected" ).each(function() {
                if ($( this ).id) {
                    value = ".site" + $( this ).id;
                } else
                {
                    value = ".sitenone";
                }
                $(value).filter(
                    function() {
                    $(this).toggle($(this).className)
                }
                );
            });

        })



    $("#myInput").on("keyup", function() {
        var val = $(this).val().toLowerCase();
        $("#myTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1)
        });
    });
});