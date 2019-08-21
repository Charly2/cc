<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Empresas</h4>
            </div>
            <div class="modal-body">
                <p>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Empresa</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($empresas as $empresa)
                            <tr>
                                <td>{{ $empresa->id }}</td>
                                <td><a href="{{ route('usuario.registrar',$empresa->id) }}">{{ $empresa->nombre }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>