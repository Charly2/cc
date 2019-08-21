@extends('layout.master')
@section('head')
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css">
@stop

@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Informacion del expediente <a class="btn btn-default btn-xs pull-right" href="#" role="button"><span class="glyphicon glyphicon-arrow-left"></span> Atras</a></div>
            <div class="panel-body">
          
                <div class="row">
                    <div class="col-md-12">

                        <button id="submit" class="btn btn-primary pull-right" ><span class="glyphicon glyphicon-plus"></span>  Subir Pedimentos</button>


                        <h4>Pedimentos</h4>
                        <div class="panel-body">


                        {!! Form::open([ 'route' => [ 'PedimentosDell.uploadFiles' ], 'files' => true, 'class' => 'dropzone','id'=>"files"]) !!}
                        <input type="hidden" name="expediente_id" value='#'>
                         
                        <input type="hidden" name="tipo_factura" value='#'>
                        {!! Form::close() !!}

                        </div>
                    
                </div>
            </div>
        </div>
    </div>






 @endsection
@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.js"></script>

@stop
@push('scripts')


   $(document).ready(function () {
Dropzone.options.files = {

        // Prevents Dropzone from uploading dropped files immediately
        autoProcessQueue: false,
        dictDefaultMessage: "Arrastre sus Archivos XML o de click AQUI para cargar sus archivos",
        parallelUploads: 10,
        paramName: "files",
        maxFilesize: 100 ,// Tamaño máximo en MB
        acceptedFiles: ".asc",

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

      this.on("queuecomplete", function (file) {
          alert("Todos los archivos se han cargo correctamente");
      });

      },
      success: function(file, response) {
        console.log(response);
      }



    };
});



 @endpush



