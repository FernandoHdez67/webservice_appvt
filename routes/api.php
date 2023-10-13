<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/productos', [App\Http\Controllers\ProductosController::class,'index']); //muestra todos los registros
Route::get('productos/{idproducto}',[App\Http\Controllers\ProductosController::class,'getCategoriaxid']);//Buscar por id
Route::post('/productos', [App\Http\Controllers\ProductosController::class,'store']); // crea un registro
Route::put('/productos/{idproducto}', [App\Http\Controllers\ProductosController::class,'update']); // actualiza un registro
Route::delete('/productos/{idproducto}', [App\Http\Controllers\ProductosController::class,'destroy']); //elimina un registro


Route::get('/citas', [App\Http\Controllers\CitasController::class,'citas']); //muestra todos los registros
Route::get('/citasss', [App\Http\Controllers\CitasController::class,'index']); //muestra todos los registros pero con la hora con ID
Route::get('citas/{id}',[App\Http\Controllers\CitasController::class,'getCategoriaxid']);//Buscar por id
Route::get('citas/fecha/{fecha}',[App\Http\Controllers\CitasController::class,'getCitasPorFecha']);//Buscar por fecha
Route::post('/citas', [App\Http\Controllers\CitasController::class,'store']); // crea un registro
Route::put('/citas/{id}', [App\Http\Controllers\CitasController::class,'update']); // actualiza un registro
Route::delete('/citas/{id}', [App\Http\Controllers\CitasController::class,'destroy']); //elimina un registro por id

Route::get('/fechas', [App\Http\Controllers\CitasController::class,'buscarFechas']); //Buscar por rango de fechas 
Route::get('/buscar-fechas', [App\Http\Controllers\CitasController::class,'buscaFechaEspecifica']); //Buscar por fecha especifica
Route::get('/cita', [App\Http\Controllers\CitasController::class,'buscaFechaPorId']); //Buscar por Id
Route::get('/citas-del-dia', [App\Http\Controllers\CitasController::class,'citasDeHoy']); //Mostrar citas del dia de hoy
Route::get('/citas-de-manana', [App\Http\Controllers\CitasController::class,'citasDeManana']); //Mostrar citas del dia de mañana
Route::get('/cita-mes', [App\Http\Controllers\CitasController::class,'citasPorMesNumero']); //Mostrar citas por numero de mes 
Route::get('/cita-del-mes', [App\Http\Controllers\CitasController::class,'citasPorMesNombre']); //Mostrar citas por nombre del mes 
Route::get('/citas-dia', [App\Http\Controllers\CitasController::class,'citasPorDiaDelMes']); //Mostrar citas por dia de la semana
Route::delete('/eliminar-cita', [App\Http\Controllers\CitasController::class,'eliminarCitaPorFechaHora']); //elimina un registro por fecha y hora

Route::get('/horarios', [App\Http\Controllers\CitasController::class,'horariosDisponibles']); //Mostrar citas por dia de la semana


Route::get('/servicios', [App\Http\Controllers\ServiciosController::class,'index']); //muestra todos los registros
Route::get('servicios/{idservicios}',[App\Http\Controllers\ServiciosController::class,'getServicioxid']);//Buscar por id
Route::post('/servicios', [App\Http\Controllers\ServiciosController::class,'store']); // crea un registro
Route::put('/servicios/{idservicios}', [App\Http\Controllers\ServiciosController::class,'update']); // actualiza un registro
Route::delete('/servicios/{idservicios}', [App\Http\Controllers\ServiciosController::class,'destroy']); //elimina un registro

Route::get('/servicio-campos', [App\Http\Controllers\ServiciosController::class,'buscarServiciotodosloscampos']); //muestra todos los campos de un servicio
Route::get('/servicio-ccampo', [App\Http\Controllers\ServiciosController::class,'buscarServicioconcampo']); //muestra el nombre del servico con su campo
Route::get('/servicio-scampo', [App\Http\Controllers\ServiciosController::class,'buscarServiciosincampo']); //muestra el nombre del servico sin su campo





