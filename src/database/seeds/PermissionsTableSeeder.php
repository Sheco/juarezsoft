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
        $role = Role::create([
            'name'=>'administrador',
            'sueldo'=>0
          ]);
        $verReportes = Permission::create(['name'=>'ver reportes']);
        $role->givePermissionTo($verReportes);
        $admin = Permission::create(['name'=>'ver administración']);
        $role->givePermissionTo($admin);
        $adminUsers = Permission::create(['name'=>'administrar usuarios']);
        $role->givePermissionTo($adminUsers);
        $adminInv = Permission::create(['name'=>'administrar inventario']);
        $role->givePermissionTo($adminInv);
        $facturar = Permission::create(['name'=>'facturar']);
        $role->givePermissionTo($facturar);
        $vender = Permission::create(['name'=>'vender']);
        $role->givePermissionTo($vender);

        $role = Role::create([
            'name'=>'gerente',
            'sueldo'=>35000
        ]);
        $role = Role::create([
            'name'=>'subgerente',
            'sueldo'=>22000
        ]);
        $role->givePermissionTo($facturar);
        $role = Role::create([
            'name'=>'jefe de piso',
            'sueldo'=>15000
        ]);
        $role = Role::create([
            'name'=>'vendedor',
            'sueldo'=>5000
          ]);
        $role->givePermissionTo($vender);
    }
}
