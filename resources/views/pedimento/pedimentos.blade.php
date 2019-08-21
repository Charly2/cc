@extends('layout.master')
@section('content')
<div>
	<div class="page-header">
		<h4>Consulta de pedimentos</h4>
	</div>
	<form action="{{ URL::route('pedimento.consulta') }}" class="form-inline" method="GET" pagination-form>
		<div class="form-group">
			<select class="form-control" name="ejercicio" id="ejercicio">
				@for($i=(int)date("Y")-2;$i<=(int)date("Y");$i++)
				<option value="{{ $i }}" @if($i==(isset($ejercicio) ? $ejercicio :'')) selected="selected" @endif>{{ $i }}</option>
				@endfor
			</select>
		</div>
		<div class="form-group">
			<select class="form-control" name="periodo" id="periodo">
				@for($i=1;$i<13;$i++)
				<option value="{{ $i }}" @if($i==(isset($periodo)? $periodo: '')) selected="selected" @endif>{{ $i }}</option>
				@endfor
			</select>
		</div>
		<button type="sumbit" class="btn btn-primary" pagination-action>Consultar</button>
	</form>
    <div id="content-data-pagination">
        @include('pedimento.pdmtos')
    </div>   
</div> 
@endsection
@push('scripts')
<script src="{{ asset('js/ajax.pagination.js') }}"></script>
@endpush