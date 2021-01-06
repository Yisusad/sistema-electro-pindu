<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CajaTipo extends Model
{
    protected $table = 'caja_tipo_movimientos';
    protected $fillable =[
        'descripcion'
    ];
    public $timestamps = false;
    public function concepto()
    {
        return $this->hasMany('App\Concepto');
    }
}
