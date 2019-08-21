@extends('layout.master')
@section('content')
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Información del expediente
                <a class="btn btn-default btn-xs pull-right"
                   @if(isset($_GET['inicio']))
                   href="{{ route('expediente.filtro_expedientes', [
                    'inicio' => $_GET['inicio'],
                    'final' => $_GET['fin']
                   ]) }}"
                   @else
                   href="{{ route('expedientes.index') }}"
                   @endif
                   role="button">
                    <span class="glyphicon glyphicon-arrow-left">
                    </span> Atras
                </a>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="glyphicon glyphicon-folder-open"></span>
                                N.Expediente: <span class="pull-right">{{ $expediente->expediente }}</span>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item">
                                <span class="glyphicon glyphicon-folder-open"></span>
                                Nombre: <span class="pull-right">{{ $expediente->nombre }}</span>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item"><span class="glyphicon glyphicon-calendar">
                                </span> Creado: <span class="pull-right">{{ $expediente->created_at }}</span>
                                <div class="clearfix"></div>
                            </li>
                            <li class="list-group-item"><span class="glyphicon glyphicon-user">
                                </span> Agente Aduanal: <span class="pull-right">{{ $expediente->nombre_agente }}</span>
                                <div class="clearfix"></div>
                            </li>
                            @if ($pedimentos_asignados)
                            <li class="list-group-item"><span class="glyphicon glyphicon-folder-close">
                                </span> Pedimento: <span class="pull-right"></span>
                                <div class="clearfix"></div>
                            </li>
                            @endif

                        </ul>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <li class="list-group-item">
                            <span class="glyphicon glyphicon-globe"></span> Aduana:
                            <p>{{ isset($expediente->aduana->denominacion) ? $expediente->aduana->denominacion : 'N/A' }}</p>
                            <div class="clearfix"></div>
                        </li>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="glyphicon glyphicon-info-sign"></span> Descripción:
                                <p>{{ $expediente->descripcion }}</p>
                                <div class="clearfix"></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4><span class="label label-default">Operaciones: </span></h4>
                        <div class="well">
                            <a role="button" class="btn btn-primary" href="{{ route('expediente.pedimento.unsigned',['id' => $expediente->id]) }}" ><span class="glyphicon glyphicon-link"></span> Asignar pedimento(s)</a>
                            <a role="button" class="btn btn-primary" href="{{ url('unsigned_cove',['id_expediente' => $expediente->id])}}" ><span class="glyphicon glyphicon-link"></span> Asignar cove(s)</a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                Carga de documentos <span class="caret"></span></button>
                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a role="button" class="" href="{{ url('/facturas',['id'=>$expediente->id])}}" ><span class="glyphicon glyphicon-link"></span> Facturas de servicios</a>
                                    </li>
                                    <li>
                                        <a role="button" class="" href="{{ url('/create_document',['id'=>$expediente->id])}}" ><span class="glyphicon glyphicon-link"></span> Documentos de expediente</a>
                                    </li>
                                    <li>
                                        <a role="button" class="" href="{{ url('expediente/carga',['id' => $expediente->id])}}" ><span class="glyphicon glyphicon-link"></span>Subir cove o pedimento</a>
                                    </li>
                                </ul>
                            </div>
                            <a id="form-pago-open" role="button" class="btn btn-primary" data-modal="true" data-href="{{ route('movimientos.pago.register',['id' => $expediente->id]) }}"><span class="glyphicon glyphicon-usd"></span> Registrar pago</a>
                            <a role="button" class="btn btn-primary" data-modal="true" data-href="{{ route('movimientos.anticipo.register',['id' => $expediente->id]) }}"><span class="glyphicon glyphicon-usd"></span> Registrar anticipo</a>
                            <a role="button" class="btn btn-primary"  href="{{ url('estado_cuenta',['id' => $expediente->id ,'tipo_estado' => 'general' ]) }}"><span class="glyphicon glyphicon-list-alt"></span> Estado de cuenta</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Coves</h4>
                        <table class="table table-striped table-bordered small">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Factura</th>
                                    <th>Clave</th>
                                    <th>Moneda</th>
                                    <th>Total Dolares</th>
                                    <th>Valor Aduana</th>
                                    <th>Tipo de Cambio</th>
                                    <th>País Facturación</th>
                                    <th>Proveedor</th>
                                    <th>Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if  (count($coves)>'0')

                                    @foreach($coves as $key => $cove)
                                        @php
                                            $collection = collect(json_decode($cove->json_cove,true));
                                            $collection = new App\Collector\Collector($collection);
                                            $mercancias = $collection->comprobantes['mercancias'];
                                            if(isset($collection['comprobantes'])){
                                                $totalDolares = $collection->sum('valorDolares');
                                            }
                                            if(isset($collection['facturas'])){
                                                $totalDolares = $collection['facturas']->sum('valorDolares');
                                            }
                                            //informacion proveniente del xml del cove
                                            $cove_json = json_decode($cove->json_cove,true);
                                            //informacion proveniente del pedimento
                                            $cove_montos = json_decode($cove->json,true);
                                        @endphp
                                        <tr>
                                            <td width="90">{{ isset($arr_cove[$key]['fechaexpedicion']) ? $arr_cove[$key]['fechaexpedicion'] : 'N/A' }}</td>
                                            @if(isset($arr_cove[$key]['numerofacturarelacionfacturas']))
                                                <td>{{isset($arr_cove[$key]["numerofacturarelacionfacturas"]) ? $arr_cove[$key]["numerofacturarelacionfacturas"] : 'N/A' }}</td>
                                            @elseif($arr_cove[$key]['numerofacturaoriginal'])
                                                <td>{{isset($arr_cove[$key]['numerofacturaoriginal']) ? $arr_cove[$key]['numerofacturaoriginal'] : 'N/A'}}</td>
                                            @endif
                                            <td>{{ $cove->usr_num_cove }}</td>
                                            @if((isset($arr_cove[$key]["mercancias"]["tipomoneda"])))
                                                <td>{{isset($arr_cove[$key]["mercancias"]["tipomoneda"]) ? $arr_cove[$key]["mercancias"]["tipomoneda"] : 'N/A' }}</td>
                                            @else
                                                <td>{{isset($arr_cove[$key]["mercancias"][0]["tipomoneda"]) ? $arr_cove[$key]["mercancias"][0]["tipomoneda"] : 'N/A' }}</td>
                                            @endif
                                            <td>${{isset($cove_montos["datos_cove"]['0']['valorTotalDollar']) ? number_format($cove_montos["datos_cove"]['0']['valorTotalDollar'],2) : 'N/A' }}</td>
                                            <td>${{isset($cove_montos["datos_cove"]['0']["valorTotalMoneda"]) ? number_format($cove_montos["datos_cove"]['0']["valorTotalMoneda"],2) : 'N/A' }}</td>
                                            <td>
                                                {{isset($cove_montos["datos_pedimento"]['0']["tipo_cambio"]) ? $cove_montos["datos_pedimento"]['0']["tipo_cambio"] : 'N/A' }}
                                            </td>
                                            <td>{{isset($arr_cove[$key]["emisor"]["domicilio"]["pais"]) ? $arr_cove[$key]["emisor"]["domicilio"]["pais"] : 'N/A' }}</td>
                                            <td>{{isset($arr_cove[$key]["emisor"]["nombre"]) ? $arr_cove[$key]["emisor"]["nombre"] : 'N/A' }}</td>
                                            <td width="130">
                                                <a href="{{url('/xml_cove',['xml' => $cove->xml, 'expediente_id' => $expediente->id,'empresas' => $folderEmpresa->value]) }}" role="button" aria-label="Left Align" title="Descargar XML" >
                                                    <img class="imagen" width="35" height="30" src="/img/save_xml.png" >
                                                </a>
                                                <a href="{{url('cove_factura',['id_cove'=>$cove->id,'id_expediente' => $expediente->id])}}" role="button" aria-label="Left Align" title="Ver Factura COVE" >
                                                    <img class="imagen" width="35" src="/img/infopago.png" >
                                                </a>
                                                <a href="{{ url('/pdf_cove',['xml' => $cove->xml,'expediente_id' => $expediente->id,'empresa' => $folderEmpresa->value]) }}" role="button" aria-label="Left Align" title="Descargar Coves" target="_blank" >
                                                    <img class="imagen" width="35" src="/img/icono-PDF.png" >
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <h4>Pedimentos</h4>
                        <table class="table table-striped table-bordered small">
                            <thead>
                                <tr>
                                    <th>Pedimento</th>
                                    <th>Aduana</th>
                                    <th>Archivo M</th>
                                    <th>Exp / Imp</th>
                                    <th>Tipo operación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($pedimentos_asignados as $pedimento)
                                    <tr>
                                        <td>{{ isset($pedimento->pedimento) ? $pedimento->pedimento : 'N/A' }}</td>
                                        <td>{{ isset($pedimento->aduanaDespacho) ? $pedimento->aduanaDespacho : 'N/A' }}</td>
                                        <td>{{ isset($pedimento->archivoM) ? $pedimento->archivoM : 'N/A' }}</td>
                                        <td>{{ isset($pedimento->impExpNombre) ? $pedimento->impExpNombre : 'N/A' }}</td>
                                        <td>{{ isset($pedimento->tipoOperacion) ? ($pedimento->tipoOperacion==1) ? 'Importacion' : 'Exportacion' : 'N/A'}}</td>
                                        <td width="130">
                                            <a href="{{url('/xml_pedimento', ['id' => $pedimento->id])}}" role="button" aria-label="Left Align" title="Descargar XML" >
                                                <img class="imagen" width="35" height="35" src="/img/save_xml.png" />
                                            </a>
                                            <a href="{{ url('/pedimento_vista',['id' => $pedimento->id,'expediente_id' => $expediente->id ]) }}" role="button" aria-label="Left Align" title="Ver pedimento completo" >
                                                <img class="imagen" width="35" src="/img/infopago.png" >
                                            </a>
                                            <a  href="{{ url('/pdf_pedimento',['id' => $pedimento->id]) }}" role="button" aria-label="Left Align" title="Descargar Coves" target="_blank" >
                                                <img class="imagen" width="35" src="/img/icono-PDF.png" >
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <h4>Facturas</h4>
                        <table class="table table-striped table-bordered small">
                            <thead>
                                <tr>
                                    <th>UUID</th>
                                    <th>RFC Emisor</th>
                                    <th>Fecha Exp.</th>
                                    <th>Poliza</th>
                                    <th>Monto</th>
                                    <th>Status</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pagos_External as $factura)
                                    @php
                                        $factura_json=json_decode($factura->json_cfdi,true);
                                    @endphp
                                    <tr> {{$factura->monto_anterior}}
                                        <td>{{ $factura_json['tfd']['UUID'] }}</td>
                                        <td>{{ $factura_json['Emisor']['Rfc'] }}</td>
                                        <td>{{ $factura_json['cfdiComprobante']['Fecha'] }}</td>
                                        <td>{{$factura->poliza}}</td>
                                        <td style="text-align: right;">${{ number_format($factura_json['cfdiComprobante']['Total'],2) }}</td>
                                        <td>{{ isset($factura->status_factura) ? $factura->status_factura : 'Sin pagar' }}</td>
                                        <td width="130">
                                            <a href="{{ route('factura.download', ['id' => $factura->id]) }}" role="button" aria-label="Left Align" title="Descargar XML">
                                                <img class="imagen" width="35" src="/img/save_xml.png" >
                                            </a>
                                            <a href="{{ url('/vista_factura', ['id' => $factura->id,'expediente_id'=> $expediente->id]) }}" role="button" aria-label="Left Align" title="Ver Factura">
                                                <img class="imagen" width="35" src="/img/infopago.png" >
                                            </a>
                                            <a  href="{{ url('/pdf_factura', ['factura' => $factura->id]) }}" role="button" aria-label="Left Align" title="Descargar PDF" target="_blank">
                                                <img class="imagen" width="35" src="/img/icono-PDF.png" >
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <h4>Pagos</h4>
                        <table class="table table-striped table-bordered small">
                            <thead>
                                <tr>
                                    <th >RFC</th>
                                    <th >Proveedor</th>
                                    <th >Forma de pago</th>
                                    <th >Fecha de pago</th>
                                    <th >Folio</th>
                                    <th >Monto</th>
                                    <th >Monto Anterior</th>
                                    <th >Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pagos as $pago)
                                    <tr>
                                        <td>{{ $pago->emisor_rfc }}</td>
                                        <td>{{ $pago->emisor_nombre }}</td>
                                        <td>{{ $pago->uidPago }}</td>
                                        <td>{{ $pago->fechaPago }}</td>
                                        <td>{{ $pago->polizaContable }} </td>
                                        <td style="text-align: right;">${{ number_format($pago->monto_factura,2) }}</td>
                                            <td>{{ isset($pago->monto_anterior) ? '$'.number_format($pago->monto_anterior,2) : '' }}</td>
                                        
                                        <td width="130">
                          
                                    
                                
                                            {{--<a href="{{ url($pago->xml_file) }}" role="button" aria-label="Left Align" title="Descargar XML" target="_blank">--}}
                                            <a href="{{ route('pago.download',array('id' => $pago->id)) }}" role="button" aria-label="Left Align" title="Descargar XML">
                                                <img class="imagen" width="35" src="/img/save_xml.png" >
                                            </a>  

                                            <a href="{{ url('/vista_pago',['id' => $pago->id,'expediente_id'=> $expediente->id]) }}" role="button" aria-label="Left Align" title="Ver Factura" >
                                                <img class="imagen" width="35" src="/img/infopago.png" >
                                            </a>  
                                            
                                            <a  href="{{ url('/pdf_factura',['id' => $pago->id,'expediente_id'=> $expediente->id]) }}" role="button" aria-label="Left Align" title="Descargar Factura" target="_blank" >
                                                <img class="imagen" width="35" src="/img/icono-PDF.png" >
                                            </a>  
                                            
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <h4>Anticipos</h4>
                        <table class="table table-striped table-bordered small">
                            <thead>
                                <tr>
                                    <th>RFC</th>
                                    <th>Empresa</th>
                                    <th>Forma de pago</th>
                                    <th>Fecha de pago</th>
                                    <th>N° Transacción CxP</th>
                                    <th>N° Poliza Contable</th>
                                    <th>Monto</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($anticipos as $anticipo)
                                    <tr>
                                        <td>{{ $anticipo->rfc }}</td>
                                        <td>{{ $anticipo->agente }}</td>
                                        <td>{{ $anticipo->uidPago }}</td>
                                        <td>{{ $anticipo->fechaPago }}</td>
                                        <td><a data-href="http://ctrade.cpalumis.com.mx/servicesFinancials.php" data-modal="true">{{ $anticipo->transaccionCP }}</a></td>
                                        <td><a data-href="http://ctrade.cpalumis.com.mx/servicesFinancials.php" data-modal="true">{{ $anticipo->polizaContable }}</a></td>
                                         <td style="text-align: right;">${{ number_format($anticipo->monto,2) }}</td>
                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div id="focus" class="col-md-12" name="documentos_expediente">
                        <h4>Documentos del expediente </h4>
                        <a style="margin-left: 10px;" role="button" class="btn btn-primary pull-right" href="{{url('descargar_documentos',['expediente->id' => $expediente->id])}}"><span class="glyphicon glyphicon-download"></span> Descargar Documentos</a>
                        @if(session()->has('success'))
                            <inpu id="success" type="hidden" value="asldjf">
                        @endif
                        <table class="table table-striped table-bordered small">
                            <thead>
                                <tr>
                                    <th>Notas</th>
                                    <th>Nombre de documento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documentos as $documento)
                                    <tr id="row-documento-{{$documento->id}}">
                                        <td style="width: 50%">

                                            <label style="float: left; width: 10%">Nota: </label>

                                            {{--<form style="display: none" id="form-note-{{$documento->id}}" method="post" action="{{ route('documentos.update', $documento->id) }}">
                                                {!! method_field('PUT') !!}
                                                {!! csrf_field() !!}
                                                    <input class="form-control" style="width: 80%; float: left" type="text" name="note" value="{{ $documento->nota }}">
                                                    <button type="submit" class="btn btn-success btn-xs pull-right"><span class="glyphicon glyphicon-ok"></span></button>
                                            </form>--}}
                                            <div id="form-note-{{$documento->id}}" style="display: none">
                                                <input id="input-note-{{$documento->id}}" type="text" name="note" class="form-control" style="width: 80%; float: left" value="{{ $documento->nota }}" title="nota">
                                                <button id="button-ok-{{$documento->id}}" class="btn btn-success btn-xs pull-right button-update" data-token="{{ csrf_token() }}" data-url="{{ url('/documentos') }}"><span class="glyphicon glyphicon-ok"></span></button>
                                            </div>

                                            <label class="font-weight-light" id="label-note-{{$documento->id}}">{{ $documento->nota }}</label>
                                            <button id="button-pencil-{{$documento->id}}" class="btn btn-primary btn-xs pull-right button-edit"><span class="glyphicon glyphicon-pencil"></span></button>
                                        </td>

                                        <td>{{ $documento->nombreDocumento }}</td>
                                        <td>
                                            &nbsp;
                                            @php
                                                $nombreDoc = explode('.', $documento->nombreDocumento);
                                                $ext = $nombreDoc[count($nombreDoc)-1];
                                            @endphp
                                            @if($ext === 'pdf' || $ext === 'PDF')
                                                <a class="icon-fa" href="{{url('/descargar_documento',['expediente_id' => $documento->id]) }}" role="button" aria-label="Left Align" title="Descargar PDF" target="_blank">
                                                    <i class="far fa-file-pdf fa-2x"></i>
                                                </a>
                                            @elseif($ext === 'xml' || $ext === 'XML')
                                                <a class="icon-fa" href="{{url('/descargar_documento',['expediente_id' => $documento->id]) }}" role="button" aria-label="Left Align" title="Descargar XML" >
                                                    <i class="far fa-file-code fa-2x"></i>
                                                </a>
                                            @elseif($ext === 'xlsx' || $ext === 'XLSX' || $ext === 'xls' || $ext === 'XLS')
                                                <a class="icon-fa" href="{{url('/descargar_documento',['expediente_id' => $documento->id]) }}" role="button" aria-label="Left Align" title="Descargar EXCEL" >
                                                    <i class="far fa-file-excel fa-2x"></i>
                                                </a>
                                            @elseif($ext === 'docx' || $ext === 'DOCX' || $ext === 'doc' || $ext === 'DOC')
                                                <a class="icon-fa" href="{{url('/descargar_documento',['expediente_id' => $documento->id]) }}" role="button" aria-label="Left Align" title="Descargar WORD" >
                                                    <i class="far fa-file-word fa-2x"></i>
                                                </a>
                                            @elseif($ext === 'pptx' || $ext === 'PPTX' || $ext === 'ppt' || $ext === 'PPT')
                                                <a class="icon-fa" href="{{url('/descargar_documento',['expediente_id' => $documento->id]) }}" role="button" aria-label="Left Align" title="Descargar POWER POINT" >
                                                    <i class="far fa-file-powerpoint fa-2x"></i>
                                                </a>
                                            @elseif($ext === 'png' || $ext === 'PNG' || $ext === 'jpg' || $ext === 'JPG' )
                                                <a class="icon-fa" href="{{url('/descargar_documento',['expediente_id' => $documento->id]) }}" role="button" aria-label="Left Align" title="Descargar Imagen" >
                                                    <i class="far fa-file-image fa-2x"></i>
                                                </a>
                                            @else
                                                <a class="icon-fa" href="{{ route('descargar_documento', ['documento_id' => $documento->id]) }}" role="button">
                                                    <i class="far fa-file-alt fa-2x"></i>
                                                </a>
                                            @endif
                                            &nbsp;
                                            <button id="button-remove-{{$documento->id}}" class="btn btn-xs button-remove" style="background: #ffffff; border-color: #cccccc; margin-bottom: 8px !important;"
                                                    data-token="{{ csrf_token() }}"
                                                    data-url="{{ url('/documentos') }}">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script></script>
    <script type="text/javascript" src="{{ URL::asset('js/expediente.js') }}"></script>
@endpush

