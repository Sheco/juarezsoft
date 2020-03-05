<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'frecuenciaVentas'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    public static function reporteNomina($fecha) {
        if(!$fecha) return collect([]);

        $fecha = new Carbon($fecha);
        $inicio = $fecha->clone()->startOfMonth()->format('Y-m-d');
        $fin = $fecha->clone()->endOfMonth()->format('Y-m-d');

        return DB::table('users')
            ->join('model_has_roles', 'model_id', '=', 'users.id')
            ->where('model_has_roles.model_type', 'App\User')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->leftJoin('ventas', function($join)  use ($inicio, $fin) {
                $join->on('users.id', '=', 'ventas.user_id')
                     ->whereBetween('fecha', [ $inicio, $fin] );
            })
            ->select('users.name as nombre', 
                DB::raw('count(distinct ventas.id) as cantidad'),
                'roles.name as rol',
                'sueldo',
                DB::raw('sum(total) as ventas'),
                DB::raw('sum(total)*0.02 as comision'),
                DB::raw('sueldo+coalesce(sum(total)*0.02,0) as sueldo_final')
            )
            ->groupBy('users.name')
            ->orderByRaw('roles.name,sueldo+coalesce(sum(total)*0.02,0) desc')
            ->get();
    }

    public function reporteVentas($fecha) {
        $fecha = new Carbon($fecha);

        return DB::table('ventas')
            ->join('venta_productos', 'venta_productos.venta_id', '=', 'ventas.id')
            ->select(
                'ventas.id',
                'fecha_hora',
                'total',
                DB::raw('count(distinct venta_productos.id) as productos')
            )            
            ->orderBy('fecha_hora', 'asc')
            ->where('user_id', $this->id)
            ->where('fecha', $fecha->format('Y-m-d'))
            ->groupBy('ventas.id')
            ->get();
    }
}
