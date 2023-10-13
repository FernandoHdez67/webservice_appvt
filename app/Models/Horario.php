<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cita;

class Horario extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'tbl_horarios';
    protected $fillable = ['horario'];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'hora_cita', 'idhorario');
    }
}
