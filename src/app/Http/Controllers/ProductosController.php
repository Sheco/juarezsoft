<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\Departamento;
use Carbon\Carbon;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('productos.index', [
            'datos' => Producto::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('productos.create', [
            'departamentos'=>Departamento::all(),
            'obj'=>new Producto
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'=>'required',
            'codigo'=>'required',
            'precio'=>'required',
            'frecuenciaCompras'=>'required',
            'departamento_id'
        ]);

        $producto = Producto::create($request->all());
        return redirect()->route('productos.show', $producto);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        $fecha_final = Carbon::now();
        $fecha_inicio = $fecha_final->clone()->subtract(30, 'days');

        $stats = $producto->estadisticas($fecha_inicio, $fecha_final);
        $fechas = $stats->keys();

        $vendidos = $stats->map(function($row) { 
                return intval($row->vendidos); 
            })->values();


        return view('productos.show', [
            'obj'=>$producto,
            'vendidos'=>$vendidos,
            'fechas'=>$fechas
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', [
            'departamentos'=>Departamento::all(),
            'obj'=>$producto
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Producto $producto, Request $request)
    {
        $request->validate([
            'nombre'=>'required',
            'codigo'=>'required',
            'precio'=>'required',
            'frecuenciaCompras'=>'required',
            'departamento_id'
        ]);

        $producto->update($request->all());
        return redirect()->route('productos.show', $producto);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route('productos.index');
    }
}
