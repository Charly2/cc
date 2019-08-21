{!! Form::open(['method' => 'DELETE', 'route' => ['usuarios.destroy', $usuario->id], 'onsubmit' =>
'return confirm("¿Estás seguro de eliminar este usuario?")']) !!}
    {{--<input type="submit" value="Eliminar" class="btn btn-danger">--}}
    <button type="submit" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> Eliminar</button>
{!! Form::close() !!}
