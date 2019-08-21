<div class="table-responsive">
    <table id="table-expedientes" class="table table-striped ">
        <thead>
        @if(!empty($expedientes))
        <tr>
            <th>N. Expediente</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Creado</th>
            <th>Acción</th>
        </tr>
        </thead>
        <tbody id="pedimentos-loader">
            @foreach($expedientes as $expediente)
                <tr>
                    <td>{{ $expediente->expediente }}</td>
                    <td>{{ $expediente->nombre }}</td>
                    <td>{{ $expediente->descripcion}}</td>
                    <td>{{ $expediente->created_at }}</td>
                    <td>
                        <a class="btn btn-default btn-xs pull-left" href="{{ route('expediente.show',[
                           'id' => $expediente->id,
                           'inicio' => $_GET['inicio'],
                           'fin' => $_GET['final']
                           ]) }}"
                           role="button" aria-label="Left Align" title="Ver pedimento completo">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true">
                            </span>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    @if(!empty($expedientes))
        @if(method_exists($expedientes,'render'))
            {!! $expedientes->render() !!}
        @endif
    @endif

</div>