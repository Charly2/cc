<table id="table-pedimentos" class="table table-striped txt-small">
  <thead>
    <tr>
      <th>PEDIMENTO</th>
      <th>EMISOR</th>
      <th>EMISOR RFC</th>
      <th>RECEPTOR</th>
      <th>RECEPTOR RFC</th>
      <th>UUID</th>
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
        </tr>
      @endforeach
    @endif
  </tbody>
</table>