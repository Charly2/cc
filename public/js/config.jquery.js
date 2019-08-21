$(document).ready(function () {
    $("[data-modal]").click(function(){


        $("#modal-content").load($(this).attr('data-href'),function(response,status,xhr){

            if(status == "error"){
                alert('Error al obtener la información');
                return;
            }
            $("#modal").modal();
        });
    });
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})