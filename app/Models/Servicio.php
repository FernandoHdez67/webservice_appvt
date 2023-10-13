<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    //use HasFactory;
    public $timestamps = false;
    protected $table="tbl_servicios";
    protected $primaryKey="idservicio";
    protected $fillable = [
        'tipo','descripcion','imagen'
    ];
}
