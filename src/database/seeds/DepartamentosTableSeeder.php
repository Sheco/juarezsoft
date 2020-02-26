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
        Departamento::create(['nombre'=>'Caballeros']);
        Departamento::create(['nombre'=>'Damas']);
        Departamento::create(['nombre'=>'Niños']);
        Departamento::create(['nombre'=>'Bebés']);
        Departamento::create(['nombre'=>'Júnior']);
        Departamento::create(['nombre'=>'Deportes']);
        Departamento::create(['nombre'=>'Relojería y joyería']);
        Departamento::create(['nombre'=>'Videojuegos']);
        Departamento::create(['nombre'=>'Libros, películas y música']);
    }
}
