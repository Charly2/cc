@extends('layout.master')
@section('head')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
@stop

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Documentos del expediente
                <a class="btn btn-default btn-xs pull-right" href="{{ url('/expedientes/'.$expediente_id) }}" role="button">
                    <span class="glyphicon glyphicon-arrow-left"></span>
                    Atras
                </a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">

                        <button id="submit" class="btn btn-primary pull-right" >
                            <span class="glyphicon glyphicon-plus"></span>
                            Subir Documentos
                        </button>

                        <h4>Documentos</h4>
                        <div class="panel-body">
                            {!! Form::open([ 'method' => 'POST','url' => ['documentos'], 'files' => true, 'class' => 'dropzone','id'=>"files"]) !!}
                                <input type="hidden" name="expediente_id" value='{{$expediente_id}}'>
                            {!! Form::close() !!}
                        </div>

                        <h4>Estado</h4>
                         <ul id="ul_file">
                             {{--<li style="color: #5cb85c;">dsfdasklfd.txt - Correcto </li>
                             <li style="color: red;">dsfdasklfd.pdf - Error </li>--}}
                         </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function () {
            console.log('hola');
            Dropzone.options.files = {
                // Prevents Dropzone from uploading dropped files immediately
                autoProcessQueue: false,
                dictDefaultMessage: "Arrastre sus documentos para anexarlos al Expediente",
                parallelUploads: 10,
                paramName: "files",
                maxFilesize: 100 ,// Tamaño máximo en MB
                //acceptedFiles: ".xml",

                init: function() {
                    var submitButton = document.querySelector("#submit")
                    myDropzone = this; // closure

                    submitButton.addEventListener("click", function() {
                        myDropzone.processQueue(); // Tell Dropzone to process all queued files.
                    });

                    // You might want to show the submit button only when
                    // files are dropped here:
                    this.on("addedfile", function() {
                        // Show submit button here and/or inform user to click it.
                    });

                    this.on("queuecomplete", function (file, response) {
                        console.log(response);
                        console.log('se ha terminado de cargar las facturas');
                    });
                },
                success: function(file, response) {
                    console.log(response);
                    /*var json = JSON.parse(response)
                    if(json.estatus == "ok"){

                        $('#ul_file').append('<li style="color: #5cb85c;">'+json.file+' - Ok </li>');
                    }else{

                        $('#ul_file').append('<li style="color: red;">'+json.file+' - Error </li>');
                    }*/



                }
            };
        });
    </script>
@endsection
@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>
@stop



