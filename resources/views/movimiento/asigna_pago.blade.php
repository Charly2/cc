@section('head')

<link rel="stylesheet" type="text/css" href="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.css">

<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form" method="POST" action="">
             {!! csrf_field() !!}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Registrar Anticipo</h4>
                </div>
                <div class="modal-body">
                    <div class="col-md-12"> 

                    <table class="table sorted_table small">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Emisor Nombre</th>
                            <th>Total</th>
                            <th></th>
                            
                            
                            
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                            <tr>
                            <td><input type="checkbox" name="factura" value="{{$row['id']}}"></td>
                            <td>{{$row['emisor_rfc']}}</td>
                            <td>{{$row['total']}}</td>
                            <td></td>


                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"></td>
                                <td><strong>Saldo a Favor</strong></td>
                                <td>{{$saldo}}</td>
                            </tr>
                        </tbody>
                    </table>


                    </div>
      
                    <div class="clearfix"></div>            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Registrar Anticipo</button>
                </div>
            </form>
        </div>
    </div>
</div>


