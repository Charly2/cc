<!DOCTYPE html>
<html>
    <head>
        <title>Pagina no encontrada.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 52px;
                margin-bottom: 40px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <img src="{{ asset('img/logo1.png') }}" alt="logo">
                <div class="title">No tiene permisos para ver esta página.</div>
                <h4><br>Regresar al <a href="{{url('/home')}}">Inicio de la página</a></h4>
            </div>
        </div>
    </body>
</html>
