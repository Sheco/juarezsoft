<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SolicitudCompra;

class SolicitudesCompraController extends Controller
{
    function index($status='nueva') {
        $statuses = ['nueva', 'pagada', 'surtida', 'cancelada'];

        $ordenes = [ 
            'nueva'=>'created_at',
            'pagada'=>'fecha_pago',
            'surtida'=>'fecha_surtida',
            'cancelada'=>'created_at'
        ];

        $columnaFecha = $ordenes[$status];
        $lista = SolicitudCompra::where('status', $status)
            ->orderBy($columnaFecha, 'desc')
            ->get();
        return view('solicitudescompra.index', compact(
            'statuses', 'status', 'lista', 'columnaFecha'
        )); 
    }

    function show(SolicitudCompra $solicitud) {
        return view('solicitudescompra.show', compact('solicitud'));
    }

    function pagada(SolicitudCompra $solicitud) {
        $solicitud->pagar();
        return redirect()->route('solicitudescompra.index.status', 'pagada');
    }

    function surtida(SolicitudCompra $solicitud) {
        $solicitud->surtida();
        return redirect()->route('solicitudescompra.index.status', 'surtida');
    }

    function cancelar(SolicitudCompra $solicitud) {
        $solicitud->cancelar();
        return redirect()->route('solicitudescompra.index.status', 'cancelada');
    }
}
