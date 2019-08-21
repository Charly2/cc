<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(env('APP_ENV') == 'testing')
        <title>C&T Testing</title>
    @else
        <title>CUSTOMS & TRADE</title>
    @endif
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"/>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <!-- Datatables para mostrar de forma mas dinamica la informacion -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.16/r-2.2.0/sc-1.4.3/sl-1.2.3/datatables.min.css"/>

    <!--  plugin para las cajas de seleccion-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/css/select2.min.css" rel="stylesheet" />


    <link rel="stylesheet" type="text/css" href="/fonts/ctrade_icons.css"/>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
@yield('head')

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body ng-app="ctradeApp">
<div class="container">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                        aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="{{ asset('img/logo_opt.png') }}" alt="logo" style="display:inline;">Customs & Trade</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                @if(!Auth::guest())
                    <ul class="nav navbar-nav" style="display: flex; align-items: center">
                        <li class="dropdown {{ Request::is('expediente*') ? 'active' : '' }}">
                            <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Expedientes <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::is('expedientes.index') ? 'active' : '' }}"><a href="{{ url('/expedientes') }}">Listado</a></li>
                                <li class="{{ Request::is('expediente.filtro_expedientes') ? 'active' : '' }}">
                                    <a href="{{ url('/descarga') }}">Descarga Masiva</a>
                                </li>
                            </ul>
                        </li>

                        <li class="dropdown {{ Request::is('coves*') ? 'active' : '' }}">
                            <a href="{{ url('pedimento.index') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Coves <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                {{--<li class=""><a href="#">Reporte de coves</a></li>--}}
                                <li class=""><a href="{{url('coves',['id_empresa'=> Session::get('id')])}}">Carga de Coves</a></li>
                            </ul>
                        </li>

                        <li class="dropdown {{ Request::is('pedimento*') ? 'active' : '' }}">
                            <a href="{{ url('pedimento.index') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pedimentos <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="{{ Request::is('pedimento') ? 'active' : '' }}"><a href="{{url('pedimento')}}">Carga de Pedimentos</a></li>
                                <li class="{{ Request::is('pedimento/reporte') ? 'active' : '' }}"><a href="{{ url('pedimento.reporte') }}">Reporte</a></li>
                                <li class="{{ Request::is('pedimento/reporte/facreview/*') ? 'active' : '' }}"><a href="{{ url('pedimento.facreviewMatch') }}">Reporte FacReview</a></li>
                            </ul>
                        </li>
                        @if (Auth::User()->usertype_id=!"2")
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Catalogo Agencias <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li class="{{ Request::is('usuario*') ? 'active' : '' }}"><a href="{{ url('agentes.index') }}">Lista Agencias Aduanales</a></li>
                                </ul>
                            </li>
                        @endif


                        <li class="dropdown {{ Request::is('usuario') || Request::is('empresa') ? 'active' : '' }}">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administracion <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                @if (Auth::User()->usertype_id==false)
                                    <li class="{{ Request::is('usuario*') ? 'active' : '' }}"><a href="{{ url('/usuarios') }}">Usuarios</a></li>

                                    <li class="{{ Request::is('empresa*') ? 'active' : '' }}"><a href="{{ url('/empresas') }}">Empresas</a></li>
                                    <li class="{{ Request::is('agenteaduanal*') ? 'active' : '' }}"><a href="{{ url('/agenteaduanal') }}">Agentes Aduanales</a></li>
                                @endif
                                <li class="{{ Request::is('') }}"><a href="#">Configuración</a></li>
                                <li class="{{ Request::is('') }}"><a href="#">Catalogos de operación</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="/">
                                @if(Session::has('empresa'))
                                    <span class="label label-default">
                                        {{ Session::get('empresa') }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        <li><a data-modal="true" data-href="{{ route('usuario.empresas') }}" id="definirEmpresa"><span class="glyphicon glyphicon-transfer pointer"></span></a></li>
                        <li><a href="{{ route('logout') }}">Salir</a></li>
                    </ul>
                @endif
            </div>
        </div>
    </nav>
    <br>
    <div class="col-md-12 wrapper">

        @yield('content')
    </div>
    <div id="modal-content"></div>
</div>
<script data-require="jquery@2.1.3" data-semver="2.1.3" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script type="text/css" src="{{ asset('js/all.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="{{ asset('js/config.jquery.js') }}"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.16/r-2.2.0/sc-1.4.3/sl-1.2.3/datatables.min.js"></script>



<script>
    @if(!Session::has('id'))
    $(document).ready(function () {
        $("#definirEmpresa").click();
    });
    @endif

    @stack('scripts')
</script>

@yield('footer')
</body>
</html>