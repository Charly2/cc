@extends('layout.master')


@section('content')

    <form action="{{url('/documentos')}}" method="post" enctype="multipart/form-data">
        {{csrf_field()}}
        <input type="file" name="files">
        <button>Subir</button>
    </form>

@endsection

