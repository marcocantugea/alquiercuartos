<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('api/v1/auth', 'AuthenticateController@authenticateUser');
$router->get('api/v1/version','VersionController@GetVersion');

$router->group(['middleware' => 'auth'], function () use ($router) {
    //rutas cuartos
    $router->get('api/v1/cuartos', 'CuartosController@getAllCuartos');
    $router->get('api/v1/cuarto/{publicId}', 'CuartosController@getCuarto');
    $router->put('api/v1/cuarto/{publicId}', 'CuartosController@updateCuarto');
    $router->delete('api/v1/cuarto/{publicId}', 'CuartosController@deleteCuarto');
    $router->put('api/v1/cuarto/{publicId}/activar', 'CuartosController@activaCuarto');
    $router->put('api/v1/cuarto/{publicId}/desactivar', 'CuartosController@desactivarCuarto');
    $router->put('api/v1/cuarto/{publicId}/suspender', 'CuartosController@suspenderCuarto');
    $router->post('api/v1/cuarto', 'CuartosController@addCuarto');
    $router->post('api/v1/cuartos/crear/sinlimite', 'CuartosController@addCuartosSinLimite');
    $router->post('api/v1/cuartos/restaurar/basicos', 'CuartosController@restoreCuartosBasicos');

    //rutas configuracion cuartos
    $router->get('api/v1/cuartos/configuracion', 'CuartosConfiguracionController@getAll');
    $router->get('api/v1/cuartos/configuracion/{publicId}', 'CuartosConfiguracionController@get');
    $router->get('api/v1/cuartos/configuracion/valor/{nombre}', 'CuartosConfiguracionController@getByName');
    $router->post('api/v1/cuartos/configuracion', 'CuartosConfiguracionController@addConfiguracion');
    $router->put('api/v1/cuartos/configuracion/{publicId}', 'CuartosConfiguracionController@updateConfiguration');
    $router->delete('api/v1/cuartos/configuracion/{publicId}', 'CuartosConfiguracionController@deleteConfiguracion');

    //rutas alquiler
    $router->post('api/v1/alquiler', 'AlquilerController@startAlquiler');
    $router->get('api/v1/alquiler', 'AlquilerController@getAlquileresActivos');
    $router->get('api/v1/alquiler/search/folio', 'AlquilerController@searchAlquilerByTokenFolio');
    $router->post('api/v1/alquiler/finalizar/{publicId}', 'AlquilerController@stopAlquiler');
    $router->post('api/v1/alquiler/pre/finalizar/{publicId}', 'AlquilerController@previewAlquiler');
    $router->post('api/v1/alquiler/{alquilerId}/cancelacion/parcial', 'CancelacionesController@addCancelacionParcial');

    //ruta usuarios
    $router->post('api/v1/usuario', 'UsuarioController@addUsuario');
    $router->post('api/v1/usuario/{publicId}/desactivar', 'UsuarioController@deactivateUsuario');
    $router->post('api/v1/usuario/{publicId}/activar', 'UsuarioController@activateUsuario');
    $router->delete('api/v1/usuario/{publicId}', 'UsuarioController@deleteUsuario');
    $router->get('api/v1/usuario/{publicId}', 'UsuarioController@getUsuario');
    $router->get('api/v1/usuario', 'UsuarioController@getUsuarios');
    $router->get('api/v1/usuarios/roles', 'UsuarioController@getRoles');
    $router->put('api/v1/usuario/{publicId}/password', 'UsuarioController@updatePassword');

    //ruta cancelaciones
    $router->put('api/v1/cancelacion/{publicId}/aprobar', 'CancelacionesController@aprobarCancelacion');

    //ruta configuraciones
    $router->post('api/v1/configuracion', 'ConfiguracionController@addConfiguracion');
    $router->get('api/v1/configuracion/{publicId}', 'ConfiguracionController@getConfiguracion');
    $router->get('api/v1/configuracion', 'ConfiguracionController@getConfiguraciones');
    $router->put('api/v1/configuracion/{publicId}', 'ConfiguracionController@updateConfiguracion');
    $router->delete('api/v1/configuracion/{publicId}', 'ConfiguracionController@deleteConfiguracion');

    //ruta tickets
    $router->get('api/v1/ticket/prueba', 'TicketController@PrintTestTicket');
    $router->post('api/v1/ticket/alquiler/{alquierId}/incio', 'TicketController@PrintTicketInicioAlquiler');
    $router->post('api/v1/ticket/alquiler/{alquierId}/cobro', 'TicketController@PrintTicketCobro');
    $router->get('api/v1/ticket/corte/caja/fecha', 'TicketController@PrintCorteCajaResumen');
    $router->get('api/v1/ticket/alquier/reimprimir/{folio}','TicketController@PrintTicketByFolio');

    //ruta cortes
    $router->get('api/v1/corte/caja/resumen/fecha', 'CortesController@GetCorteResumen');
    $router->get('api/v1/corte/caja/detalle/fecha', 'CortesController@GetCorteDetalle');

    //debuger
    $router->get('api/v1/debug/printer/test/codigobarras', 'TestPrinterCodeBarsController@TestPrinterCodeBar');
    $router->get('api/v1/debug/printer/test/textsize', 'TestPrinterCodeBarsController@TestPrinterTextSize');
    $router->get('api/v1/debug/printer/test/demo', 'TestPrinterCodeBarsController@demoExample');
    $router->post('api/v1/debug/notification/test/email','MailController@mail');

    //reportes
    $router->get('api/v1/reportes/mensual/montos','ReportesController@getMontosTotalesPorMes');
});