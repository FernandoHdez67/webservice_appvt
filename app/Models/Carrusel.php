<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrusel extends Model
{
        //use HasFactory;
        public $timestamps = false;
        protected $table="tbl_carrucel";
        protected $primaryKey="idimagen";
        protected $fillable = [
            'nombre','imagen'
        ];
}
