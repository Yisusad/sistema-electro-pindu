<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movimiento;
use App\Concepto;


class MovimientoController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if (!$request->ajax()) return redirect('/');
            $caja_movimientos = Movimiento::join('caja_conceptos','caja_movimientos.idcaja_conceptos','=','caja_conceptos.id')
            ->join('users','caja_movimientos.idusuario','=','users.id')
            ->select('caja_movimientos.id','caja_movimientos.idcaja_conceptos','users.usuario','caja_conceptos.descripcion as descripcion_concepto',
            'caja_conceptos.tipo_movimientos as tipo','caja_movimientos.importe_i','caja_movimientos.importe_e','caja_movimientos.fecha_hora',
            'caja_movimientos.estado')
            ->orderBy('caja_movimientos.id', 'desc')->paginate(10);
      
            return [
                'pagination' => [
                    'total'        => $caja_movimientos->total(),
                    'current_page' => $caja_movimientos->currentPage(),
                    'per_page'     => $caja_movimientos->perPage(),
                    'last_page'    => $caja_movimientos->lastPage(),
                    'from'         => $caja_movimientos->firstItem(),
                    'to'           => $caja_movimientos->lastItem(),
                ],
                'caja_movimientos' => $caja_movimientos
            ];
    }  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');

        $movimiento = new Movimiento();
        $movimiento->id_cajaconceptos = $request->id_cajaconceptos;
        $movimiento->idusuario = \Auth::user()->id;
        $movimiento->importe_i = $request->importe_i;
        $movimiento->importe_e = $request->importe_e;
        $movimiento->fecha_hora = $mytime->toDateString();
        $movimiento->estado = 'Activo';
        $movimiento->save();
    }
}
