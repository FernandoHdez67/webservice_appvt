<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    //use HasFactory;
    public $timestamps = false;
    protected $table="tbl_productos";
    protected $primaryKey="idproducto";
    protected $fillable = [
        'nombre','precio','categoria','marca', 'cantidad', 'descripcion', 'imagen'
    ];
}
