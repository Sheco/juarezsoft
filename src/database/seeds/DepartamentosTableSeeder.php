<?php

use Illuminate\Database\Seeder;
use App\Departamento;

class DepartamentosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        new Departamento(['nombre'=>'Caballeros']);
        new Departamento(['nombre'=>'Damas']);
        new Departamento(['nombre'=>'Niños']);
        new Departamento(['nombre'=>'Bebés']);
        new Departamento(['nombre'=>'Júnior']);
        new Departamento(['nombre'=>'Deportes']);
        new Departamento(['nombre'=>'Relojería y joyería']);
        new Departamento(['nombre'=>'Videojuegos']);
        new Departamento(['nombre'=>'Libros, películas y música']);
    }
}
