<div class="table-responsive">
    <table id="table-pedimentos" class="table table-striped txt-small">
        <thead>
        <tr>
            <td>Pedimento</td>
            <td>Aduana despacho</td>
            <td>Fecha pedimento</td>
            <td>Imp / Exp Nombre</td>
            <td>Tipo operacion</td>
            <td>.</td>
        </tr>
        </thead>
        <tbody id="pedimentos-loader">
        @if(isset($pedimentos))
            @foreach($pedimentos as $pedimento)
                @php
                $json = json_decode($pedimento->json)
                @endphp
                <tr>
                    <td>{{ $pedimento->pedimento }}</td>
                    <td>{{ $pedimento->aduana->denominacion }}</td>
                    <td>{{ $pedimento->created_at }}</td>
                    <td>{{ $pedimento->impExpNombre }}</td>
                    <td>{{ ($pedimento->tipoOperacion==1) ? 'Importacion' : 'Exportacion' }}</td>
                    <td>
                        <a class="btn btn-default btn-xs pull-left" href="{{ route('pedimento.ver',[
                           'pedimento' => $pedimento->pedimento,
                           'ejercicio' => $ejercicio,
                           'periodo' => $periodo
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
    @if(method_exists($pedimentos,'render'))
        {!! $pedimentos->render() !!}
    @endif

</div>