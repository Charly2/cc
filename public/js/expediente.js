$(document).ready(function(){

    // Función que en la sección de documentos cambia el formulario de
    // editar las notas del documento.
    $(document).on('click', 'button.button-edit', function(){
        var button_id = this.id;
        var id = button_id.split('-');
        id = id[2];
        $('#form-note-'+id).show();
        $('#label-note-'+id).hide();
        $('#button-pencil-'+id).hide();
    });

    // Actualización de la nota de un documento
    $(document).on('click', 'button.button-update', function(){
        var button_id = this.id;
        var id = button_id.split('-');
        id = id[2];
        var input = $('#input-note-'+id).val();
        $.ajax({
            url: $(this).data('url') + "/" + id,
            type: 'patch',
            data: {
                id: id,
                note: input,
                _token: $(this).data('token')
            },
            success: function(response){
                $('#form-note-'+id).hide();
                $('#label-note-'+id).html(input);
                $('#label-note-'+id).show();
                $('#button-pencil-'+id).show();
            },
            error: function (response) {
                console.log('Error !!');
            }
        });
    });

    // Función para remover documentos
    $(document).on('click', 'button.button-remove', function(){
        var button_id = this.id;
        var id = button_id.split('-');
        id = id[2];

        $.ajax({
            url: $(this).data('url') + "/" + id,
            type: 'delete',
            data: {
                id: id,
                _token: $(this).data('token')
            },
            success: function (response) {
                $('#row-documento-'+id).remove();
                console.log(response);
            },
            error: function (response) {
                console.log('Eliminación incorrecta' + response);
            }
        });
    });

    // Funciones para que el modal no se abra mas de una vez al hacerle clic
    $(document).on('click', '#form-pago-open', function () {
        $('#form-pago-open').addClass('disabled');
    });

    $(document).on('click', '.form-pago-close', function () {
        $('#form-pago-open').removeClass('disabled');
    });

    $(document).on('click', '#modal', function () {
        $('#form-pago-open').removeClass('disabled');
    });
});
