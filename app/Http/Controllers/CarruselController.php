<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrusel;

class CarruselController extends Controller
{
        //Muestra todos los productos en formato JSON
        public function index()
        {
            $productos = Carrusel::all();
            return $productos;
        }
}
