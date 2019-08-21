@extends('layout.master')

@section('content')

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div style="color: #817d7d;">
                    <h3>INFORMACIÓN DEL PAGO</h3>
                </div>
                <a class="btn btn-default btn-xs pull-right" href="{{url('/expedientes/'.$expediente_id)}}" role="button">
                    <span class="glyphicon glyphicon-arrow-left"></span> Atras
                </a>
            </div>
            <div class="panel-body" style="padding: 35px 35px">
                <div class="row" style="margin: 0px 0px !important;">
                    <div class="col-md-2" style="color: #817d7d;">
                        <div class="row">UUID:</div>
                        <div class="row">Fecha de la Factura:</div>
                        <div class="row">Monto:</div>
                        <div class="row">N° Certificado del SAT:</div>
                        <br>
                        <div class="row">Nombre del Receptor:</div>
                        <div class="row">RFC del receptor:</div>
                        <div class="row">Dirección:</div>
                    </div>

                    <div class="col-md-10">
                        <div class="row"><strong>{{$factura['tfd']['UUID']}}</strong></div>
                        <div class="row"><strong>{{$factura['cfdiComprobante']['Fecha']}}</strong></div>
                        <div class="row"><strong>${{$factura['cfdiComprobante']['Total']}}</strong></div>
                        <div class="row"><strong>{{$factura['tfd']['NoCertificadoSAT']}}</strong></div>
                        <br>
                        <div class="row"><strong>{{isset($factura['Receptor']['Nombre']) ? $factura['Receptor']['Nombre'] : '-'}}</strong></div>
                        <div class="row"><strong>{{$factura['Receptor']['Rfc']}}</strong></div>
                        <div class="row">
                            <strong>
                                {{isset($factura['ReceptorDomicilio']['Calle']) ? $factura['ReceptorDomicilio']['Pais'] : '-' }}
                                {{isset($factura['ReceptorDomicilio']['NoExterior']) ? $factura['ReceptorDomicilio']['NoExterior'] : '' }}
                            </strong>
                        </div>
                    </div>
                </div>
                <br>

                <div class="well m-t">
                    <div class="row">
                        <div class="col-xs-8" style="color: #817d7d;">
                            <p class="h4">
                                <span style="font-size: 18pt;">DESGLOSE DE LA FACTURA</span>
                            </p>
                        </div>
                    </div>
                </div>

                <table id="inv-details" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th width="10%">Cantidad</th>
                            <th width="10%">Unidad</th>
                            <th width="40%">Descripción</th>
                            <th width="20%">Precio Unitario</th>
                            <th width="20%">Importe</th>
                        </tr>
                    </thead>
                    <tbody>

                    @foreach($factura['Conceptos'] as $concepto)
                        <tr>
                            <td width="10%">{{$concepto['cantidad']}}</td>
                            <td width="10%">{{ isset($concepto['unidad']) ? $concepto['unidad'] : '-' }}</td>
                            <td width="40%" >{{$concepto['descripcion']}} </td>
                            <td class="text-right" width="20%" >${{$concepto['valorUnitario']}}</td>
                            <td class="text-right" width="20%" >${{$concepto['importe']}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="7">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-right"><strong>Sub Total</strong></td>
                            <td class="text-right">
                                ${{$factura['cfdiComprobante']['SubTotal']}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-right">
                                <strong>Impuestos {{$factura['Traslado']['Impuesto']}} ({{$factura['Traslado']['TasaOCuota']}}%)</strong>
                            </td>
                            <td class="text-right">
                                ${{$factura['Traslado']['Importe']}}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"></td>
                            <td class="text-right"><strong>Total</strong></td>
                            <td class="text-right">
                                ${{$factura['cfdiComprobante']['Total']}}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection