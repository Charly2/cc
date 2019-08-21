@extends('layout/master')

@section('content')

    <div class="container">
        <h1>Bajar Archivos</h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                Carga de archivos del servidor SFTP
                <a href="http://ctrade.test/pedimento" class="btn btn-default btn-xs pull-right" role="button">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                    Atras
                </a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            @if(isset($sftp))
                                <table id="myTable" class="table table-striped table-bordered dataTable table-responsive">
                                    <thead>
                                    <tr>
                                        <th>Host</th>
                                        <th>Usuario</th>
                                        <th>Ruta</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $sftp->host }}</td>
                                        <td>{{ $sftp->user }}</td>
                                        <td>{{ $sftp->path }}</td>
                                        <td>{{ $sftp->created_at }}</td>
                                        <td>
                                            <a href="{{ route('programacion_pedimento.edit', ['id' => $sftp->id]) }}" class="btn btn-default btn-sm" role="button" title="Editar conexión"><span class="glyphicon glyphicon-pencil"></span></a>
                                            {{--<a href="{{ route('SFTP_download') }}" class="btn btn-default btn-sm" role="button" title="Descargar archivos"><span class="glyphicon glyphicon-arrow-down"></span></a>--}}
                                            <button  class="btn btn-default btn-sm button-download"  id="init_dow" title="Descargar archivos"><span class="glyphicon glyphicon-arrow-down"></span></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                       <div class="main_cont">
                           {{--<button class="btn btn-success  mx-auto" id="init_dow" style="display: block; margin: auto">Iniciar Descarga</button>--}}

                           <div class="" id="prossa" style="display: none">
                               <div class="progress mb-0">
                                   <div id="progress_bar_download" class="progress-bar progress-bar-striped bg-success" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">

                                   </div>
                               </div>
                               <div class="" id="porcenrt"></div>
                           </div>

                           <div id="download_sftp"  style="display: none">
                               <div class="col-md-4">
                                   <div class="card reuldo" style="background-color: #3498db">
                                       <div class="card-header">
                                           <div class="">Pedimentos:</div>
                                           <div id="download_m" class=""></div>
                                       </div>
                                       <div class="card-body">
                                           <div id="pul"></div>
                                       </div>
                                   </div>
                               </div>
                               <div class="col-md-4">
                                   <div class="card reuldo" style="background-color: #27ae60">
                                       <div class="card-header">
                                           <div class="">Coves:</div>
                                           <div id="download_cove" class=""></div>
                                       </div>
                                       <div class="card-body">
                                           <div id="cul"></div>
                                       </div>
                                   </div>
                               </div>
                               <div class="col-md-4">
                                   <div class="card reuldo" style="background-color: #e74c3c">
                                       <div class="card-header">ñ
                                           <div class="">Errores:</div>
                                           <div id="errors_a" class=""></div>
                                       </div>
                                       <div class="card-body">
                                           <div id="eul"></div>
                                       </div>
                                   </div>
                               </div>






                           </div>

                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        var progressive_bar = $('#progress_bar_download');
        var porcenrt = $('#porcenrt');
        $(document).ready(function (e) {

            $('#init_dow').click(function (e) {
                e.preventDefault();
                this.disabled = true;

                $('#prossa').fadeIn();
                progressive_bar.attr('style', 'width: 10%');
                porcenrt.html("Conectando al servidor");


                setTimeout(function () {
                    porcenrt.html("Analizando archivos");
                    progressive_bar.attr('style', 'width: 25%');
                    getDile();
                },500)





            });

        });


        function getDile() {
            $.get('/prueba_get',).done(function(response) {
                console.log(response);


                if (response.length){

                    //showlist(response,'myItemList')

                    analisa_files(response);
                } else{
                    alert("sin docs");
                }


            });
        }

        function showlist(response,s) {
            var ul = document.createElement('ul');
            document.getElementById(s).appendChild(ul);
            response.forEach(function (item) {
                var li = document.createElement('li');
                ul.appendChild(li);
                li.innerHTML += item;
            });
        }


        function analisa_files(items) {
            porcenrt.html("Descargando archivos");
            progressive_bar.attr('style', 'width: 65%');
            $.post('/prueba_get_files',{
                files:items,
                "_token": "{{ csrf_token() }}",
            }).done(function(response) {
                var data = JSON.parse(response);
                $('#download_sftp').fadeIn();
                console.log(data);

                if (data.pedimento){
                    $('#download_m').html(data.pedimento.length);
                    showlist(data.pedimento,'pul');
                }
                if (data.coves){
                    $('#download_cove').html(data.coves.length);
                    showlist(data.coves,'cul')
                }
                if (data.yaexiste){
                    $('#errors_a').html(data.yaexiste.length);
                    showlist(data.yaexiste,'eul')
                }

                porcenrt.html("Finalizando");
                progressive_bar.attr('style', 'width: 100%');
                if((data.pedimento.length==0) && (data.coves.length==0) ){

                    progressive_bar.css('background-color', '#27ae60');
                } if(data.yaexiste.length>1){
                    progressive_bar.css('background-color', '#e74c3c');
                }


            });

        }




    </script>


@endsection

