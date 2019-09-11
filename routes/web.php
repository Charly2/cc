<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Documento;
use App\FacturasCargadas;
use App\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\CatUsertype;
use MrGenis\Library\XmlToArray;
use Symfony\Component\VarDumper\VarDumper;

Route::get('/', function () {
    return Redirect::to('home');
});
Route::get('/form', function () {


    return view('welcome');
});

/**
 * AUTH
 * Funciones de login y logout en el sistema
 *
 * get   /login            Vista que muestra el login
 * post   /logout           Metodo para hacer logout en el sistema
 * post  /login            Metodo para hacer login en el sistema
 */
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

/*// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');*/




Route::get('/home', 'HomeController@index')->name('home');
Route::get('/usuario/empresas','UserController@empresas')->name('usuario.empresas');
Route::get('empresa/registrar/{id}','EmpresasController@registrarEmpresa')->name('usuario.registrar');



Route::group(['middleware'   => ['auth' , 'chk_agencia', 'rfc_empresa', 'session_timeout']], function () {

    /**
     * EXPEDIENTES
     *
     * get      /expedientes/                           Vista que muestra los expedientes de una empresa
     * get      /expedientes/{id}                       Vista que muestra el detalle de un expediente
     * get      /expedientes/create                     Vista para crear un expediente
     * post     /expedientes                            Método para crear un expediente
     * get      /expedientes/{id}/edit                  Vista para editar un expediente
     * put      /expedientes/{id}                       Método para editar un expediente
     * delete   /expedientes/{id}                       Método para eliminar un expediente
     *
     * get      /expediente/{id}/pago/register          ?                                                               MW: expediente_approved
     * get      /expediente/{id}/anticipo/register      ?                                                               MW: expediente_approved
     * get      /expediente/{id}/pedimento/unsigned     Vista que muestra los pedimentos sin asignar a un expediente    MW: expediente_approved
     * get      /expediente/descarga                    Vista para seleccionar los expedientes creados entre 2 fechas
     *                                                  Vista que muestra los expedientes creados entre 2 fechas
     * get      /expediente/carga/{id}                  Descarga de expedientes creados entre 2 fechas
     */
    Route::resource('expedientes', 'ExpedienteController', [
        'except' => ['destroy']
    ]);

    Route::group(['prefix' => 'expediente'], function(){
        Route::get('{id}/pago/register',['middleware' => 'expediente_approved', 'as' => 'expediente.pago.register', 'uses' => 'ExpedienteController@registrarPago']);
        Route::get('{id}/anticipo/register',['middleware' => 'expediente_approved', 'as' => 'expediente.anticipo.register', 'uses' => 'ExpedienteController@registrarAnticipo']);
        Route::get('{id}/pedimento/unsigned',['middleware' => 'expediente_approved', 'as' => 'expediente.pedimento.unsigned', 'uses' => 'ExpedienteController@pedimentosUnsigned']);
        Route::get('descarga', ['as' => 'expediente.filtro_expedientes', 'uses' => 'ExpedienteController@filtro_expedientes']);
        Route::get('carga/{id}', 'ExpedienteController@cargaDocumentos');
    });

    Route::get('/delete_expediente/{action}/{expediente}','ExpedienteController@delete_expediente');
    Route::get('/estado_cuenta/{id}/{tipo_estado}','ExpedienteController@estado_cuenta');
    Route::get('/estado_cuenta_v2/{id}/{tipo_estado}','ExpedienteController@estado_cuenta_v2');
    Route::get('/ver_pago/','ExpedienteController@verPagoShow');
    Route::get('/aplicar_pago/{id}','ExpedienteController@aplicarPagoCreate');
    Route::get('/documentos/','ExpedienteController@showCargarDocumentos');

    /**
     * PEDIMENTO
     *
     * get   /pedimento                                         Vista para ver los pedimentos cargados por empresa
     * get   /pedimento/reporte                                 Vista para escoger el periodo para selccionar pedimentos
     * get   /pedimento/consulta                                Vista de la selección de pedimentos por periodo
     * get   /pedimento/graph/{rfc}                             ?
     * get   /pedimento/{pedimento}/{ejercicio}/{periodo}       Vista del detalle de pedimento
     * get   /pedimento/reporte/facreviewmatch                  ?
     * get   /pedimento/reporte/facreviewnomatch                ?
     * get   /pedimento/reporte/facreviewnomatch/{formato}      ?
     * get   /pedimento/sftp
     *
     * get   /cargar_pedimento/{id}
     * post  /upload_pedimento/{id}                               Sube el archivo M con su archivo PDF**** LOCAL
     * get   /pdf_pedimento/{pedimento}/{expediente_id}/{empresa} ?prim
     * get   /xml_pedimento/{pedimento}/{expediente_id}/{empresa} ?
     * get   /pedimento_vista/{id}/{expediente_id}                ?
     * get   /asigna_pedimentos/{pedimento}/{expedientes}         ?
     * get   /cargaPedimentos/carga                               ?
     */
    // TODO: Cambiar el metodo /pedimento/{pedimento}/{ejercicio}/{periodo} a post

    Route::group(['prefix' => 'pedimento'], function(){
        Route::get('/', ['as' => 'pedimento.index', 'uses' => 'PedimentoController@index']);
        Route::get('reporte', ['as' => 'pedimento.reporte', 'uses' => 'PedimentoController@reporte']);
        Route::get('consulta', ['as' => 'pedimento.consulta',  'uses' => 'PedimentoController@consulta']);
        Route::get('graph/{rfc}', ['uses' => 'PedimentoController@graph']);
        Route::get('{pedimento}/{ejercicio}/{periodo}', ['as' => 'pedimento.ver', 'uses' => 'PedimentoController@pedimento']);
        Route::get('reporte/facreviewmatch', ['as' => 'pedimento.facreviewMatch', 'uses' => 'PedimentoController@matchFacreview']);
        Route::get('reporte/facreviewnomatch', ['as' => 'pedimento.facreviewNoMatch', 'uses' => 'PedimentoController@noMatchFacreview']);
        Route::get('reporte/facreviewnomatch/{formato}', ['as' => 'pedimento.facreviewNoMatchExport', 'uses' => 'PedimentoController@noMatchFacreviewExport']);
    });

    Route::get('/cargar_pedimento/{id}', 'PedimentoController@cargar_pedimento');
    Route::post('/upload_pedimento/{id}', 'PedimentoController@upload_pedimento');
    Route::get('/pdf_pedimento/{pedimento_id}','PedimentoController@pedimentoPDF');
    Route::get('/xml_pedimento/{pedimento_id}','PedimentoController@pedimentosXML');
    Route::get('/pedimento_vista/{id}/{expediente_id}', 'PedimentoController@pedimento_vista');
    Route::get('asigna_pedimentos/{pedimento}/{expedientes}', 'PedimentoController@asigna_pedimentos');
    Route::get('cargaPedimentos/carga', ['as' => 'pedimento.carga', 'uses' => 'PedimentoController@cargaPedimentos']);
    Route::get('/job_pedimento_local','JobUploadPedimentoController@uploadPedimento');
    Route::get('prueba/actualizaPedimento', ['as' => 'prueba.actualizaPedimento', 'uses' => 'CargaPedimentosController@insertaPedimentos']);

    Route::resource('programacion_pedimento', 'JobUploadPedimentoController');  // Se carga la vista para iniciar la descarga de SFTP
    Route::get('programacion_pedimento/finalizar', 'JobUploadPedimentoController@finish')->name('finish');

    /*Route::get('/files_uploaded', 'JobUploadPedimentoController@uploadPedimentoFTP')->name('job_pedimento_ftp');*/
    /*Route::get('/SFTP_download', 'JobUploadPedimentoController@uploadFilesSFTP')->name('SFTP_download');*/
    /*Route::get('/SFTP_test', 'JobUploadPedimentoController@processTestSFTP')->name('SFTP_test');*/
    Route::get('/SFTP_download', 'JobUploadPedimentoController@uploadFilesSFTP')->name('JobUploadPedimentoController');
    Route::post('/SFTP_process', 'JobUploadPedimentoController@processFilesSFTP')->name('SFTP_process');
    Route::get('/SFTP_test', 'JobUploadPedimentoController@processTestSFTP')->name('SFTP_test');
    Route::get('/sftp/password', 'JobUploadPedimentoController@changePassword')->name('sftp.changePassword');
    Route::post('/sftp/password', 'JobUploadPedimentoController@updatePassword')->name('sfpt.updatePassword');

    /* Cove */
    Route::get('/pdf_cove/{id}/{expediente_id}/{empresa}','CovesController@descargarPDFCove');
    Route::get('/xml_cove/{id}/{expediente_id}/{empresa}','CovesController@descargarXMLCove');

});

Route::group(['middleware' => ['auth', 'chk_agencia', 'session_timeout']], function () {

    /**
     * HOME
     *
     * get  /home   Muestra el circulo de inicio del sistema
     */
    Route::get('home', ['as' => 'home.index', 'uses' => 'HomeController@index']);

    /**
     * USUARIO
     *
     * get      /usuarios                            Vista que muestra todos los usuarios en el sistema
     * get      /usuarios/create                     Vista para crear un usuario
     * post     /usuarios                            Método para guardar un usuario nuevo
     * get      /usuarios/{id}/edit                  Vista para editar un usuario
     * patch    /usuarios/{id}                       Método para actualizar un usuario
     * delete   /usuarios/{id}                       Método de eliminación de usuario
     *
     * get      /usuario/{id}/asignar               ?
     * post     /usuario/{id}/asignar               ?
     * get      /usuario/{id}/desasignar/{empresa}  ?
     * get      /usuario/empresas                   Método para cambiar de empresa que tiene asignada el usuario
     */
    Route::resource('usuarios', 'UsuarioController');
    Route::group(['prefix' => 'usuario'], function(){
        Route::get('{id}/asignar', ['as' => 'usuario.asignar', 'uses' => 'UsuarioController@asignar']);
        Route::post('{id}/asignar', ['as' => 'usuario.asignarEmpresa', 'uses' => 'UsuarioController@asignarEmpresa']);
        Route::get('{id}/desasignar/{empresa}', ['middleware' => ['auth', 'chk_agencia', 'is_admin'], 'as' => 'usuario.desasignar', 'uses' => 'UsuarioController@desasignar']);
        Route::get('empresas', ['as' => 'usuario.empresas', 'uses' => 'UsuarioController@empresas']);
    });

    /**
     * EMPRESAS
     *
     * get      /empresas                    Vista de todas las empresas
     * get      /empresas/create             Vista para crear una empresa
     * post     /empresas                    Método para guardar una empresa nueva
     * get      /empresas/{id}/edit          Vista para editar una empresa
     * patch    /empresas/{id}               Método para actualizar una empresa
     * delete   /empresas/{id}               Método para eliminar una empresa
     * get      /empresa/registrar/{id}      Guarda en variables de sesión la empresa
     */
    Route::resource('empresas', 'EmpresasController');
    Route::get('empresa/registrar/{id}', ['as' => 'usuario.registrar', 'uses' => 'EmpresasController@registrarEmpresa']);

    /*Agentes aduanales*/




    /**
     * MOVIMIENTOS
     *
     * get      /movimientos/{id}/registrar/pago        Modal para registrar un pago
     * get      /movimientos/{id}/registrar/anticipo    Modal para registrar un anticipo
     * post     /movimientos                            Método para registrar un pagomovimientos
     * get      /movimientos/download/{id}              ?
     * get      /vista_pago/{id}/{id_expediente}        Vista que muestra el pago a detalle
     */
    Route::group(['prefix' => 'movimientos'], function(){
        Route::get('{id}/registra/pago', ['as' => 'movimientos.pago.register', 'uses' => 'MovimientosController@getFormPago']);
        Route::get('{id}/registra/anticipo', ['as' => 'movimientos.anticipo.register', 'uses' => 'MovimientosController@getFormAnticipo']);
        Route::post('/', ['as' => 'movimientos.store', 'uses' => 'MovimientosController@store']);
        Route::post('/store_anticipo', ['as' => 'movimientos.store', 'uses' => 'MovimientosController@store_anticipo']);
        Route::get('download/{id}', ['as' => 'pago.download', 'uses' =>'MovimientosController@download']);
    });
    Route::get('/vista_pago/{id}/{id_expediente}', 'MovimientosController@indexPago');
});

Route::group(['middleware'   => ['auth', 'chk_agencia', 'is_admin', 'session_timeout']], function () {

    /**
     * FACTURAS
     *
     * get      /facturas/{id}                              Vista que muestra todas las facturas
     * get      /create/{id}                                ?????? Creo que no se ocupa
     * get      /subir_facturas/{id}/{tipo}                 Vista para registrar una factura
     * post     /uploadFactura/{id_empresa}                 Método para registrar una factura
     * get      /vista_factura/{id}/{id_expediente}         Vista de una factura a detalle
     * get      /pdf_factura/{id}/{id_expediente}
     * get      /factura/download/{id}                      Método para guardar el XML de una factura
     * get      /pdf_factura/{factura}/{expediente}/{empresa}
     */

    Route::get('/facturas/{id}',  'FacturasController@show');
    Route::get('/create/{id}',  'FacturasController@formFactura');
    Route::get('/subir_facturas/{id}/{tipo}', 'FacturasController@subirFacturaPagos');
    Route::post('uploadFactura/{id_empresa}', 'FacturasController@uploadFiles');
    Route::get('/vista_factura/{id}/{id_expediente}', 'FacturasController@show_facturaCargada');
    Route::get('/pdf_factura/{id}/{id_expediente}', 'FacturasController@Show_PDF_Factura');
    Route::get('/factura/download/{id}', ['as' => 'factura.download', 'uses' => 'FacturasController@download']);
    Route::get('pdf_factura/{factura_id}', 'FacturasController@facturaPDF');
});

/*movientos_provedor*/

Route::group(['middleware'   => ['auth','chk_agencia', 'is_admin', 'session_timeout']], function () {
    Route::get('/movimientos_provedor',  'MovimientosProveedorController@save_xml');
});


/* Cargas */

Route::group(['middleware' => ['auth','chk_agencia','is_admin','session_timeout']], function (){
    Route::get('cargas','CargasController@show');


    Route::get('/agenteaduanal', ['as'  => 'agentes.index', 'uses' => 'ApiController@index']);
    Route::get('/agenteaduanal/create','AgenteAduanalController@create');
    Route::post('/agenteaduanal/create','AgenteAduanalController@store');

    Route::get('/agenteaduanal/edit/{id}','AgenteAduanalController@edit');
    Route::post('/agenteaduanal/update','AgenteAduanalController@update');
});

Route::group(['middleware' => ['auth', 'chk_agencia', 'is_admin', 'session_timeout']], function () {
    /**
     * COVES
     *
     * get      /coves/{id}                                 Vista que muestra todos los coves
     * get      /cargar_cove/{id}                           Vista para registrar un cove
     * post     /upload_cove/{id}                           Método para registrar un nuevo cove
     * get      /unsigned_cove/{id}                         Vista que muestra los coves no asignados
     * get      /asigna_cove/{id_cove}/{id_expediente}      Método para asignar un cove a un expediente
     * get      /cove_factura/{id_cove}/{id_expediente}     ?
     * get      /cove_json                                  ?
     */
    // TODO: Ver si la asignación de COVE se queda en CovesController o se va a ExpedienteController

    Route::get('/coves/{id}',['as' => 'coves.index', 'uses' => 'CovesController@index']);
    Route::get('/cargar_cove/{id}', 'CovesController@cargar_cove');
    Route::post('/upload_cove/{id}', 'CovesController@upload_cove');
    Route::get('/unsigned_cove/{id}', 'CovesController@show_unsigned_cove');
    Route::get('/asigna_cove/{id_cove}/{id_expediente}', 'CovesController@asigna_cove');
    Route::get('/cove_factura/{id_cove}/{id_expediente}', 'CovesController@coveFacturaShow');
    Route::get('/cove_json',  'CovesController@cove_json');

    Route::get('/job_cove','JobUploadPedimentoController@uploadCove');

    /**
     * DOCUMENTOS
     *
     * get      /create_document/{id}                   Vista para cargar varios documentos
     * resource                                         Conjunto de metodos para los documentos
     * get      /descargar_documentos/{expediente_id}   Método para descargar todos los archivos de un expediente
     * get      /descargar_documento/{documento_id}     Método para descargar un documento
     */
    Route::get('/create_document/{id}','DocumentoController@createDocument');
    Route::resource('documentos','DocumentoController');
    Route::get('/descargar_documentos/{expediente_id}', 'DocumentoController@descargar_documentos');
    Route::get('descargar_documento/{documento_id}', ['as' => 'descargar_documento', 'uses' => 'DocumentoController@descargar_documento']);
});

Route::get('/prueba',  'PruebaController@index');
Route::get('/test',  'PedimentoTestController@index');
Route::get('/create_user',  'Auth\AuthController@create_user');


Route::group(['prefix'=> 'api/', 'middleware' =>['cors']],function (){

});
Route::resource('aviso_alta_contrato', 'AltaContratoController');
Route::get('job_upload_cove','JobUploadCoveController@uploadCove');



Route::get('prueba','PruebaController@index');
Route::get('prueba_get','PruebaController@index_get');
Route::get('showAll','PruebaController@showAll');
Route::post('prueba_get_files','PruebaController@index_get_files');


Route::get('/dep',function (){
    return view('prueba.index');
});



Route::post('/dep_file',function (){

    $file = request()->file('file');
    $url = storage_path('app/test/');
    $status = $file->move($url,$file->getClientOriginalName());
    $importFile = File::get($url.$file->getClientOriginalName());
    $array_cove = XmlToArray::convert($importFile);

    VarDumper::dump($array_cove);

    if ($array_cove['_root']['namespaceURI']['xmlns:oxml']=="http://www.ventanillaunica.gob.mx/cove/ws/oxml/"){
        VarDumper::dump("NameURI OK");
    }else{
        VarDumper::dump("NameURI ERROR");
    }


    //VarDumper::dump($array_cove['comprobantes']);
    //dd($array_cove);





    //dd(request()->file('file'));
});


use App\Library\XMLVAL\XmlValidator;

Route::get('/pp',function (){

    foreach (['CSM9301219B4/13/coves/_XMLCOVE182FWAXS2.XML','test/no.xml'] as $item){
        $validator = new XmlValidator;
        $validated = $validator->validateFeeds($item);

        if ($validated) {
            echo $item."----"."SI <br>";
        } else {
            echo $item."----"."NO <br>";
        }
    }







    dd("s");

});

