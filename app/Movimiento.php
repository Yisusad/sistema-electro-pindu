<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    protected $table = 'caja_movimientos';
    protected $fillable =[
        'id_cajaconceptos',
        'idusuario',
        'importe_i',
        'importe_e',
        'fecha_hora',
        'estado'
    ];
    public $timestamps = false;
    public function concepto()
    {
        return $this->belongsTo('App\Concepto');
    }
    public function users()
    {
        return $this->hasMany('App\User');
    }
}
