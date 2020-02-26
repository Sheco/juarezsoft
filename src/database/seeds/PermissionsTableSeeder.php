<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name'=>'gerente',
            'sueldo'=>35000
        ]);
        Role::create([
            'name'=>'subgerente',
            'sueldo'=>22000
        ]);
        Role::create([
            'name'=>'jefe de piso',
            'sueldo'=>15000
        ]);
        Role::create([
            'name'=>'vendedor',
            'sueldo'=>5000
        ]);
    }
}
