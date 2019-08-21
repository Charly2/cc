$(document).ready(function(){
    $("#usertype_id").on("change", function(){
        var usertype_id;
        //usertype_id = $("#usertype_id").val(usertype_id);
        usertype_id = document.getElementById("usertype_id").value;

        if(usertype_id == '2') {
            $("#select-agente").css("display", "block");
            $("#select-empresa").css("display", "none");
        }else if(usertype_id == '3'  ){
            $("#select-agente").css("display", "none");
            $("#select-empresa").css("display", "block");
        }else{
            $("#select-agente").css("display", "none");
            $("#select-empresa").css("display", "none");
        }
    });

    $( "#empresa" ).select2( { placeholder: "Seleccione ua  empresa" , maximumSelectionSize: 10 } );
});