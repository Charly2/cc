{!! Form::open(['method' => 'DELETE', 'route' => ['empresas.destroy', $empresa->id], 'onsubmit' =>'return confirm("¿Estas seguro de eliminar este producto?")']) !!}
    {{--<input type='submit' value='Eliminar producto' class='btn btn-danger btn-sm'>--}}
    <button type="submit" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i>&nbsp;Eliminar</button>
{!! Form::close() !!}
