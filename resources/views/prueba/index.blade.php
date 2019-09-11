@extends('layout.master')


@section('content')

<h1>Formulario De XML</h1>

<form action="/dep_file" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="file" name="file">
    <button style="margin-top: 30px" class="btn btn-success">Enviar</button>
</form>

@endsection