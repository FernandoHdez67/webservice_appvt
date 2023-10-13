<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;


class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //Muestra todos los productos en formato JSON
    public function index()
    {
        $Servicios = Servicio::all();
        return $Servicios;
    }

    //Muestra los servicios por id
    public function getServicioxid($idServicio)
    {
        $Servicios = Servicio::find($idServicio);
        if (is_null($Servicios)) {
            return response()->json(['Mensaje' => 'Registro no encontrado'], 404);
        }
        return response()->json($Servicios::find($idServicio), 200);
    }

    //Muestra por nombre del servicio
    public function buscarServiciotodosloscampos(Request $request)
    {
        $servicio = $request->input('servicio');

        // Buscar eventos en la base de datos con el tipo
        $servicios = Servicio::where('tipo', $servicio)->get();

        return response()->json($servicios);
    }


    // Muestra por nombre del servicio y devuelve un array asociativo con el nombre del campo y el valor en formato JSON
    public function buscarServicioconcampo(Request $request)
    {
        // Obtén el valor del parámetro 'servicio' de la URL
        $servicio = $request->input('servicio');

        // Busca el servicio en la base de datos por el nombre (campo 'tipo')
        $servicios = Servicio::where('tipo', $servicio)->first();

        // Verifica si se encontró el servicio
        if ($servicios) {
            // Devuelve un array asociativo con el nombre del campo y el valor en formato JSON
            return response()->json(['tipo' => $servicios->tipo]);
        } else {
            // Si no se encuentra el servicio, devuelve un mensaje de error en formato JSON
            return response()->json(['error' => 'Servicio no encontrado'], 404);
        }
    }

    // Muestra por nombre del servicio y devuelve solo el nombre en formato JSON
    public function buscarServiciosincampo(Request $request)
    {
        // Obtén el valor del parámetro 'servicio' de la URL
        $servicio = $request->input('servicio');

        // Busca el servicio en la base de datos por el nombre (campo 'tipo') y selecciona solo el campo 'tipo'
        $servicios = Servicio::where('tipo', $servicio)->pluck('tipo');

        // Devuelve la respuesta en formato JSON con solo el nombre del servicio
        return response()->json($servicios);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //Insertar un nuevo servicio
    public function store(Request $request)
    {
        $Servicios = new Servicio();
        $Servicios->tipo = $request->tipo;
        $Servicios->descripcion = $request->descripcion;
        $Servicios->imagen = $request->imagen;


        $Servicios->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Actualizar un producto
    public function update(Request $request)
    {
        $Servicios = Servicio::findOrFail($request->idservicio);
        $Servicios->tipo = $request->tipo;
        $Servicios->descripcion = $request->descripcion;
        $Servicios->imagen = $request->imagen;

        $Servicios->save();

        return $Servicios;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Eliminar un producto
    public function destroy(Request $request)
    {
        $Servicios = Servicio::destroy($request->idservicio);
        return $Servicios;
    }
}
