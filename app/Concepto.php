<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
    protected $table = 'caja_conceptos';
    protected $fillable =[
        'tipo_movimientos','descripcion'
    ];
    public $timestamps = false;
    public function cajatipo()
    {
        return $this->belongsTo('App\CajaTipo');
    }
    public function movimiento()
    {
        return $this->hasMany('App\Movimiento');
    }
}
