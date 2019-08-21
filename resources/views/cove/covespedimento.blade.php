<div class="table-responsive">
    <table class="table table-striped txt-small">
        <thead>
            <tr>
                <th>Pedimento</th>
                <th>Factura</th>
                <th>Fecha</th>
                <th>Incoterms</th>
                <th>Moneda</th>
                <th>Pais</th>
                <th>Prov / Comp</th>
                <th>TaxId</th>
                <th>Valor Dolares</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coves as $cove)
                <tr>
                    <td>{{ $cove->pedimento }}</td>
                    <td>{{ $cove->factura }}</td>
                    <td>{{ $cove->fecha }}</td>
                    <td title="{{ ($cove->descripcion===NULL) ? 'No definido' : $cove->descripcion }}">{{ $cove->incoterms }}</td>
                    <td>{{ $cove->moneda }}</td>
                    <td>{{ $cove->pais }}</td>
                    <td>{{ $cove->proveedorOComprador }}</td>
                    <td>{{ $cove->taxId }}</td>
                    <td class="text-right">{{ number_format($cove->valorDolares) }}</td>
                    <td class="text-right">{{ number_format($cove->valorMonedaOriginal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>