<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Horario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


class CitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'hora_cita');
    }


    private function formatCitas($citas)
    {
        $citasFormateadas = [];
        foreach ($citas as $cita) {
            $citasFormateadas[] = [
                'id' => $cita->id,
                'nombre_mascota' => $cita->nombre_mascota,
                'raza_mascota' => $cita->raza_mascota,
                'nombre_propietario' => $cita->nombre_propietario,
                'telefono_propietario' => $cita->telefono_propietario,
                'edad_mascota' => $cita->edad_mascota,
                'sexo_mascota' => $cita->sexo_mascota,
                'fecha_cita' => $cita->fecha_cita,
                'hora_cita' => $cita->horario->horario, // obtenemos el dato del horario
                'razon_cita' => $cita->razon_cita,
            ];
        }

        return $citasFormateadas;
    }
    public function horariosDisponibles()
    {
        // Obtenemos solo el campo "horario" de la tabla tbl_horarios
        $horarios = Horario::select('horario')->get();

        // Retorna los horarios en formato JSON como respuesta de la API
        return response()->json($horarios);
    }

    public function citas()
    {
        // Obtenemos las citas con el horario relacionado
        $citas = Cita::with('horario')->get();

        // Formateamos los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($citas);

        // Retornamos el JSON
        return response()->json($citasFormateadas);
    }


    //Buscar citas por rango de fechas
    public function buscarFechas(Request $request)
    {
        // Obtener los parámetros de búsqueda
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        // Realizar la búsqueda en la base de datos
        $resultados = Cita::whereBetween('fecha_cita', [$fechaInicio, $fechaFin])->get();

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($resultados);

        // Retornar los resultados formateados como respuesta JSON
        return response()->json($citasFormateadas);
    }

    //Buscar citas por fecha especifica
    public function buscaFechaEspecifica(Request $request)
    {
        $fecha = $request->input('fecha');

        // Buscar eventos en la base de datos con la fecha dada
        $eventos = Cita::whereDate('fecha_cita', $fecha)->get();

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($eventos);

        return response()->json($citasFormateadas);
    }

    //Buscar citas por ID 
    public function buscaFechaPorId(Request $request)
    {
        $eventoId = $request->input('id');

        // Buscar eventos en la base de datos con el ID dado
        $cita = Cita::find($eventoId);

        if (!$cita) {
            return response()->json(['message' => 'Cita no encontrada'], 404);
        }

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas([$cita]);

        return response()->json($citasFormateadas[0]);
    }


    //Mostrar citas del dia de hoy
    public function citasDeHoy()
    {
        // Obtenemos la fecha actual
        $fechaActual = Carbon::today();

        // Usamos Eloquent para obtener las citas de hoy
        $citasHoy = Cita::whereDate('fecha_cita', $fechaActual)->get();

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($citasHoy);

        return response()->json($citasFormateadas);
    }

    //Muestra las citas de mañana
    public function citasDeManana()
    {
        // Obtenemos la fecha de mañana sumando un día a la fecha actual
        $fechaManana = Carbon::tomorrow();

        // Usamos Eloquent para obtener las citas de mañana
        $citasManana = Cita::whereDate('fecha_cita', $fechaManana)->get();

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($citasManana);

        return response()->json($citasFormateadas);
    }

    //Muestra todos las citas en formato JSON
    public function index()
    {
        $citas = Cita::all();

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($citas);

        return response()->json($citasFormateadas);
    }

    //Mostrar citas por mes por numero de mes
    public function citasPorMesNumero(Request $request)
    {
        // Obtener el mes deseado desde la solicitud (por defecto, será el mes actual)
        $mes = $request->input('mes') ?: Carbon::now()->month;

        // Obtener el primer y último día del mes
        $primerDia = Carbon::create(Carbon::now()->year, $mes, 1)->startOfDay();
        $ultimoDia = Carbon::create(Carbon::now()->year, $mes, 1)->endOfMonth()->endOfDay();

        // Buscar citas en la base de datos que estén dentro del rango del mes
        $citasPorMes = Cita::whereBetween('fecha_cita', [$primerDia, $ultimoDia])->get();

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($citasPorMes);

        return response()->json($citasFormateadas);
    }

    //Mostrar citas por mes por nombre
    public function citasPorMesNombre(Request $request)
    {
        // Obtener el nombre del mes desde la solicitud (por defecto, será el mes actual)
        $nombreMes = $request->input('mes') ?: Carbon::now()->format('F');

        // Array con los nombres de los meses en español y su equivalente numérico
        $mesesEnEspanol = [
            'enero' => 1,
            'febrero' => 2,
            'marzo' => 3,
            'abril' => 4,
            'mayo' => 5,
            'junio' => 6,
            'julio' => 7,
            'agosto' => 8,
            'septiembre' => 9,
            'octubre' => 10,
            'noviembre' => 11,
            'diciembre' => 12,
        ];

        // Convertir el nombre del mes a su equivalente numérico (buscándolo en el array)
        $numeroMes = $mesesEnEspanol[strtolower($nombreMes)];

        // Obtener el primer y último día del mes
        $primerDia = Carbon::create(Carbon::now()->year, $numeroMes, 1)->startOfDay();
        $ultimoDia = Carbon::create(Carbon::now()->year, $numeroMes, 1)->endOfMonth()->endOfDay();

        // Buscar citas en la base de datos que estén dentro del rango del mes
        $citasPorMes = Cita::whereBetween('fecha_cita', [$primerDia, $ultimoDia])->get();

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($citasPorMes);

        return response()->json($citasFormateadas);
    }

    //Mostrar citas por dia del mes actual
    public function citasPorDiaDelMes(Request $request)
    {

        // Obtener el día deseado desde la solicitud (por defecto, será el día actual)
        $diaDeseado = $request->input('dia') ?: Carbon::now()->day;

        // Obtener el mes actual y año actual
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        // Verificar si el día deseado es válido para el mes actual
        $ultimoDiaDelMes = Carbon::create($anioActual, $mesActual)->endOfMonth()->day;
        if ($diaDeseado > $ultimoDiaDelMes) {
            return response()->json(['mensaje' => 'El día ingresado no es válido para el mes actual.']);
        }

        // Construir la fecha en formato "Y-m-d" para hacer la consulta en la base de datos
        $fechaDeseada = "$anioActual-$mesActual-$diaDeseado";

        // Obtener las citas para el día específico
        $citas = Cita::whereDate('fecha_cita', $fechaDeseada)->get();

        // Verificar si hay citas para el día buscado
        if ($citas->isEmpty()) {
            return response()->json(['mensaje' => 'No hay citas para este día del mes.']);
        }

        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($citas);

        return response()->json($citasFormateadas);
    }

    //Muestra los productos por id
    public function getCategoriaxid($id)
    {

        $cita = Cita::find($id);
        if (is_null($cita)) {
            return response()->json(['Mensaje' => 'Registro no encontrado'], 404);
        }
        // Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas([$cita]);

        return response()->json($citasFormateadas[0]);
    }

    //mostrar citas por fecha
    public function getCitasPorFecha($fecha)
    {
        $citas = Cita::whereDate('fecha_cita', $fecha)->get();

        if ($citas->isEmpty()) {
            return response()->json(['Mensaje' => 'No se encontraron citas para la fecha especificada'], 404);
        }

        //Formatear los datos para obtener el JSON deseado
        $citasFormateadas = $this->formatCitas($citas);

        return response()->json($citasFormateadas);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    //Insertar una nueva cita
    public function store(Request $request)
    {
        // Validar los datos recibidos en la solicitud
        $validator = Validator::make($request->all(), [
            'nombre_mascota' => 'required|string',
            'raza_mascota' => 'required|string',
            'nombre_propietario' => 'required|string',
            'telefono_propietario' => 'required|string',
            'edad_mascota' => 'required|integer',
            'sexo_mascota' => 'required|string',
            'fecha_cita' => 'required|date',
            'hora_cita' => 'required|date_format:H:i:s',
            'razon_cita' => 'required|string',
        ]);

        // Si la validación falla, devolver una respuesta JSON con los errores
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Buscar el horario correspondiente a la hora ingresada
        $horario = Horario::where('horario', $request->hora_cita)->first();

        // Si el horario no está disponible, devolver una respuesta JSON con error
        if (!$horario) {
            return response()->json(['error' => 'El horario no está disponible.'], 400);
        }

        // Verificar si ya existe una cita con el mismo horario en la misma fecha
        $citaExistente = Cita::where('fecha_cita', $request->fecha_cita)
            ->where('hora_cita', $horario->idhorario)
            ->first();

        // Si ya existe una cita con el mismo horario y fecha, devolver una respuesta JSON con error
        if ($citaExistente) {
            return response()->json(['error' => 'Ya existe una cita en ese horario y fecha.'], 400);
        }

        // Guardar la cita con el id del horario relacionado
        $cita = new Cita();
        $cita->nombre_mascota = $request->nombre_mascota;
        $cita->raza_mascota = $request->raza_mascota;
        $cita->nombre_propietario = $request->nombre_propietario;
        $cita->telefono_propietario = $request->telefono_propietario;
        $cita->edad_mascota = $request->edad_mascota;
        $cita->sexo_mascota = $request->sexo_mascota;
        $cita->fecha_cita = $request->fecha_cita;
        $cita->hora_cita = $horario->idhorario;
        $cita->razon_cita = $request->razon_cita;
        $cita->save();

        // Devolver una respuesta JSON con mensaje de éxito
        return response()->json(['message' => 'Cita registrada exitosamente.'], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //Actualizar una cita
    public function update(Request $request)
    {
        $cita = Cita::findOrFail($request->id);
        $cita->nombre_mascota = $request->nombre_mascota;
        $cita->raza_mascota = $request->raza_mascota;
        $cita->nombre_propietario = $request->nombre_propietario;
        $cita->telefono_propietario = $request->telefono_propietario;
        $cita->edad_mascota = $request->edad_mascota;
        $cita->sexo_mascota = $request->sexo_mascota;
        $cita->fecha_cita = $request->fecha_cita;
        $cita->hora_cita = $request->hora_cita;
        $cita->razon_cita = $request->razon_cita;

        $cita->save();

        return $cita;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function eliminarCitaPorFechaHora(Request $request)
    {
        $fechaCita = $request->input('fecha_cita');
        $horaCita = $request->input('hora_cita'); // Formato HH:MM:SS

        // Buscar el horario correspondiente en la tabla tbl_horarios
        $horario = Horario::where('horario', $horaCita)->first();

        if (!$horario) {
            return response()->json(['message' => 'El horario no existe'], 404);
        }

        // Buscar y eliminar la cita por fecha y hora
        $cita = Cita::where('fecha_cita', $fechaCita)
            ->where('hora_cita', $horario->idhorario)
            ->first();

        if (!$cita) {
            return response()->json(['message' => 'La cita no existe'], 404);
        }

        $cita->delete();

        return response()->json(['message' => 'Cita eliminada correctamente'], 200);
    }

    //Eliminar una cita
    public function destroy(Request $request)
    {
        $cita = Cita::destroy($request->id);
        return $cita;
    }
}
