<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'=>'Administrator',
            'email'=>'admin@localhost',
            'password'=>Hash::make('juarez123'),
        ]);

        $user->assignRole('administrador');

        $user = User::create([
            'name'=>'Bill Gates',
            'email'=>'bgates@localhost',
            'password'=>'xxx'
        ]);
        $user->assignRole('gerente');

        $user = User::create([
            'name'=>'Steve Jobs',
            'email'=>'sjobs@localhost',
            'password'=>'xxx'
        ]);
        $user->assignRole('subgerente');

        $user = User::create([
            'name'=>'Linus Torvalds',
            'email'=>'ltorvalds@localhost',
            'password'=>'xxx'
        ]);
        $user->assignRole('jefe de piso');


        $user = User::create([
            'name'=>'Diego Luna',
            'email'=>'dlnua@localhost',
            'password'=>Hash::make('vendedor1'),
            'frecuenciaVentas'=>60,
        ]);
        $user->assignRole('vendedor');

        $user = User::create([
            'name'=>'Andres Bustamante',
            'email'=>'abustamante@localhost',
            'password'=>Hash::make('vendedor1'),
            'frecuenciaVentas'=>30,
        ]);
        $user->assignRole('vendedor');

        $user = User::create([
            'name'=>'Will Smith',
            'email'=>'wsmith@localhost',
            'password'=>Hash::make('vendedor1'),
            'frecuenciaVentas'=>100,
        ]);
        $user->assignRole('vendedor');
        $user = User::create([
            'name'=>'Scarlett Johansson',
            'email'=>'sjohansson@localhost',
            'password'=>Hash::make('vendedor1'),
            'frecuenciaVentas'=>100,
        ]);
        $user->assignRole('vendedor');
        $user = User::create([
            'name'=>'Mark Zuckerberg',
            'email'=>'mzuckerberg@localhost',
            'password'=>Hash::make('vendedor1'),
            'frecuenciaVentas'=>60,
        ]);
        $user->assignRole('vendedor');
        $user = User::create([
            'name'=>'Salma Hayek',
            'email'=>'shayek@localhost',
            'password'=>Hash::make('vendedor1'),
            'frecuenciaVentas'=>75,
        ]);
        $user->assignRole('vendedor');
        $user = User::create([
            'name'=>'Leonardo Da Vinci',
            'email'=>'lvinci@localhost',
            'password'=>Hash::make('vendedor1'),
            'frecuenciaVentas'=>80,
        ]);
        $user->assignRole('vendedor');
        $user = User::create([
            'name'=>'Donald Trump',
            'email'=>'dtrump@localhost',
            'password'=>Hash::make('vendedor1'),
            'frecuenciaVentas'=>10,
        ]);
        $user->assignRole('vendedor');

    }
}
