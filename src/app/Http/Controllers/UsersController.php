<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{
    function nomina(Request $request) {
        $fecha_inicio = $request->input('fecha_inicio');

        $datos = User::reporteNomina($fecha_inicio);
        return view('users.nomina', compact(
            'fecha_inicio', 'datos'
        ));
    }
}
