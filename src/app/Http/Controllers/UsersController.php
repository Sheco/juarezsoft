<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\User;

class UsersController extends Controller
{
    function nomina(Request $request) {
        $fecha_inicio = $request->input('fecha_inicio', date('Y-m-d'));

        $datos = User::reporteNomina($fecha_inicio);
        return view('users.nomina', compact(
            'fecha_inicio', 'datos'
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datos = User::orderBy('name')->get();
        return view('users.index', compact(
            'datos'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create', [
            'obj'=>new User
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
            'name'=>'required',
            'email'=>'required',
            'password'=>'required',
            'frecuenciaVentas'=>'required'
        ]);

        $user = User::create($request->all());

        return redirect()->route('users.edit', [ 'user'=> $user->id ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('users.show', [
            "obj" => User::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'obj' => $user,
            'roles'=>Role::all()
        ]);
    }

    public function roles(User $user) {
        return view('users.roles', [
            'obj'=>$user,
            'roles'=>Role::all()
        ]);
    }

    public function addRole(User $user, Request $request) {
        $user->assignRole($request->input('role'));
        return $this->roles($user);
    }

    public function delRole(User $user, Request $request) {
        $user->removeRole($request->input('role'));
        return $this->roles($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'frecuenciaVentas'=>'required'
        ]);

        $data = $request->all();
        if(!$data["password"])
            unset($data["password"]);

        $user = User::findOrFail($id);
        $user->update($data);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route("users.index");
    }

    public function ventas(User $user, Request $request) {
        $fecha = $request->get('fecha', date('Y-m-d'));

        return view('users.ventas', [
            'fecha'=>$fecha,
            'user'=>$user,
            'datos'=> $user->reporteVentas($fecha)
        ]);
    }
}
