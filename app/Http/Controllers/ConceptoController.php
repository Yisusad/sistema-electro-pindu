<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Concepto;

class ConceptoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if (!$request->ajax()) return redirect('/');

        $caja_conceptos = Concepto::paginate(5);

        return [
            'pagination' => [
                'total'        => $caja_conceptos->total(),
                'current_page' => $caja_conceptos->currentPage(),
                'per_page'     => $caja_conceptos->perPage(),
                'last_page'    => $caja_conceptos->lastPage(),
                'from'         => $caja_conceptos->firstItem(),
                'to'           => $caja_conceptos->lastItem(),
            ],
            'caja_conceptos' => $caja_conceptos
        ];
    }
    
    public function selectConcepto(Request $request){
        //if (!$request->ajax()) return redirect('/');

        $caja_conceptos = Concepto::where('id','>','1')
        ->select('id','tipo_movimientos','descripcion')
        ->orderBy('descripcion', 'asc')->get();
 
        return ['caja_conceptos' => $caja_conceptos];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $concepto = new Concepto();
        $concepto->tipo_movimientos = $request->tipo_movimientos;
        $concepto->descripcion = $request->descripcion;
        $concepto->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $concepto = Concepto::findOrFail($request->id);
        $concepto->tipo_movimientos = $request->tipo_movimientos;
        $concepto->descripcion = $request->descripcion;
        $concepto->save();
    }
}
