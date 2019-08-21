$(document).ready(function () {
    var progressive_bar = $('#progress_bar_download');
    var status_download = $('#status_download');
    var status_process = $('#status_process');

    // Función para la descarga de documentos vía SFTP
    $(document).on('click', 'button.button-download', function () {
        console.log($(this).data('url')+'/SFTP_download');

       // getFile($(this).data('url')+'/SFTP_download');
        $.ajax({
            type: 'GET',
            url: $(this).data('url')+'/SFTP_download',
            timeout: 500000,
            beforeSend: function () {
                status_download.html('Descargando archivos del SFTP . . .');

                status_process.html('');
                progressive_bar.attr('style', 'width: 25%');
                progressive_bar.removeClass('progress-bar-success');
                progressive_bar.removeClass('progress-bar-danger');
                $('#download_sftp').hide();
            }
        }).done(function (response) {
            console.log(response);

            if(response.status === 404 || response.status === 401){
                putMessage($response.metadata, $response.status);
            } else if(response.status === 200) {
                status_download.html('Archivos descargados!');
                progressive_bar.attr('style', 'width: 50%');
                process_pedim(response.empresa, response.storage);
            } else {
                status_download.html('Ocurrio un error con la descarga!');
                progressive_bar.attr('style', 'width: 100%');
                progressive_bar.addClass('progress-bar-danger');
            }
        });




    });

    function getFile(url){
        fetch(url)
            .then(function(response) {
                return response.json();
            })
            .then(function(json) {
                console.log(json);
            });
    };

    function process_pedim(empresa, arreglo){
        // Obtiene el id del expediente, sino existe lo crea y trae el id
        $url = $('.button-download').data('url');
        $route_process = $url +'/SFTP_process';
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            url: $route_process,
            type: 'POST',
            timeout: 500000,
            data: {
                _token   : CSRF_TOKEN,
                empresa : empresa,
                arreglo : arreglo
            },
            dataType: 'JSON',
            beforeSend: function () {
                status_process.html('Procesando archivos . . .');
                progressive_bar.attr('style', 'width: 75%');
            },
            success: function (response) {
                if(response.status === 200){
                    status_process.html('Archivos procesdos correctamente!');
                    progressive_bar.attr('style', 'width: 100%');
                    progressive_bar.addClass('progress-bar-success');
                    infoDownload(response);
                } else {
                    status_process.html('Ocurrio un error al procesar los archivos, intentalo de nuevo!');
                    progressive_bar.attr('style', 'width: 100%');
                    progressive_bar.addClass('progress-bar-danger');
                }
            }
        });
    }

    /**
     * Función cuando no se encuentran archivos nuevos en el SFTP
     */
    function putMessage($metadata, $status){
        status_download.html($metadata);
        progressive_bar.removeClass('progress-bar-info');
        if($status === 401){
            progressive_bar.addClass('progress-bar-danger');
        } else if($status === 404){
            progressive_bar.addClass('progress-bar-success');
        }
        progressive_bar.attr('style', 'width: 100%');
    }

    function infoDownload(response){
        $('#download_sftp').show();
        $('#download_total').html(response.file_m + response.file_m_pdf + response.file_cove);
        $('#download_m').html(response.file_m);
        $('#download_m_pdf').html(response.file_m_pdf);
        $('#download_cove').html(response.file_cove);
    }
});