@extends('layout.master')
@section('content')
    <div id="ctn_chart" style="width: 100%; height: 100%;"></div>
    {!! Lava::render('BarChart', 'ImportacionesExportaciones', 'ctn_chart') !!}
@endsection