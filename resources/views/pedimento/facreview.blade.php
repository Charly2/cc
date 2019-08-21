@extends('layout.master')
@section('content')
<div class="col-md-12">
	<div class="page-header">
    <h4>{{ $title }}</h4>
    <div class="col-md-12">
      <p><strong>Total pedimentos:</strong> {{ number_format($totales->total[0]->contador) }}</p>
      <p><strong>Total pedimentos encontrados:</strong> {{ number_format($totales->totalSi[0]->contador) }}</p>
      <p><strong>Total pedimentos no encontrados:</strong> {{ number_format($totales->totalNo[0]->contador) }}</p>
    </div>
    <a class="btn btn-default {{ Request::is('pedimento/reporte/facreview/match') ? 'active' : '' }}" href="{{ URL::route('pedimento.facreviewMatch') }}" role="button"><span class="glyphicon glyphicon-ok"></span> Encontrados</a>
    <a class="btn btn-default {{ Request::is('pedimento/reporte/facreview/nomatch') ? 'active' : '' }}" href="{{ URL::route('pedimento.facreviewNoMatch') }}" role="button"><span class="glyphicon glyphicon-remove"></span> No Encontrados</a>
    @if(Request::is('pedimento/reporte/facreview/nomatch'))
      <a class="btn btn-success" href="{{ URL::route('pedimento.facreviewNoMatchExport',['formato' => 'excel']) }}" target="_blank" role="button"><span class="glyphicon glyphicon-download-alt"></span> Excel</a>
    @endif
  </div>    
  <div class="col-md-12">
    <div class="table-responsive">
      <table id="table-pedimentos" class="table table-striped txt-small">
        <thead>
          <tr>
            <th>PEDIMENTO</th>
            <th>EMISOR</th>
            <th>EMISOR RFC</th>
            <th>RECEPTOR</th>
            <th>RECEPTOR RFC</th>
            <th>UUID</th>
            <th>.</th>
          </tr>
        </thead>
        <tbody id="pedimentos-loader">
          @if(isset($pedimentos))
            @foreach($pedimentos as $pedimento)
              <tr>
                <td>{{ $pedimento->pedimento }}</td>                  
                <td>{{ $pedimento->nombre_emisor }}</td>
                <td>{{ $pedimento->rfc_emisor }}</td>
                <td>{{ $pedimento->nombre_receptor }}</td>
                <td>{{ $pedimento->rfc_receptor }}</td>
                <td>{{ $pedimento->uuid }}</td>
                <td>
                  <a class="btn btn-default btn-xs pull-left" href="{{ URL::route('pedimento.ver',['pedimento' => $pedimento->pedimento]) }}" role="button" aria-label="Left Align" title="Ver pedimento completo"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
        {!! isset($pedimentos) ? $pedimentos->render() : '' !!}
    </div>
  </div> 
</div> 
@endsection