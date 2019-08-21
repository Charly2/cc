@extends('layout.master')

@section('content')
    <div>
        <div class="page-header">
            <h4>Descarga masiva de expedientes</h4>
        </div>

        <form action="{{ route('expediente.filtro_expedientes') }}" class="form-inline" method="GET">
            <div class="row">
                <div class="col-md-2">Fecha inicial</div>
                <div class="col-md-2">Fecha final</div>
            </div>

            <div class="row">
                <div class="form-group col-md-2">
                    <input type="date" id="fecha_inicio" class="form-control" name="inicio" value="{{ isset($_GET['inicio'])? $_GET['inicio']:'' }}">
                </div>
                <div class="form-group col-md-2">
                    <input type="date" id="fecha_final" class="form-control" name="final" value="{{ isset($_GET['final'])? $_GET['final']:'' }}">
                </div>
                <div class="col-md-2">
                    <button name="consultar" type="submit" class="btn btn-primary">Consultar</button>
                </div>
                <div class="col-md-2">
                    <button name="descargar" type="submit" class="btn btn-primary">Descargar expedientes</button>
                </div>
            </div>
        </form>
        <br>
        <div id="content-data-pagination">
            @include('expediente.expedientes_filtrados')
        </div>
    </div>
@endsection
@push('scripts')
    <script src=""></script>
    <script>
        $(document).ready(function(){
            $('#fecha_inicio').change(function(){
                // Función para que la fecha final solo sea mayor que la inicial por 3 meses
                var fecha = new Date($('#fecha_inicio').val());
                fecha.setDate(fecha.getDate() + 1);
                var day = fecha.getDate();
                var month = fecha.getMonth();
                var year = fecha.getFullYear();
                var fecha_inicio = '';
                var fecha_final = '';
                // Días de diferencia entre fecha inicial y final para seleccionar
                var dias = 90;
                console.log(month + ' ' + day);
                month++;
                console.log(month + ' ' + day);
                if(month < 10){
                    month = '0' + month;
                }
                if(day < 10){
                    day = '0' + day;
                }
                fecha_inicio = year+'-'+month+'-'+day;
                console.log(fecha_inicio);

                fecha.setDate(fecha.getDate() + dias);

                day = fecha.getDate();
                month = fecha.getMonth();
                year = fecha.getFullYear();

                month++;

                if(month < 10){
                    month = '0' + month
                }
                if(day < 10){
                    day = '0' + day;
                }

                fecha_final = year+'-'+month+'-'+day;
                console.log(fecha_final);

                $('#fecha_final').val(fecha_inicio);
                $('#fecha_final').attr('min', fecha_inicio);
                $('#fecha_final').attr('max', fecha_final);
            });
        });

    </script>
@endpush