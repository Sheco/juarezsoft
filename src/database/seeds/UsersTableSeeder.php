<?php

use Illuminate\Database\Seeder;
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
        DB::transaction(function() {
            $user = User::create([
                'name'=>'Administrator',
                'email'=>'admin@localhost',
                'password'=>'juarez123',
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

            factory(App\User::class, 100)->create()->each(function($user) {
                $user->assignRole('vendedor');
                $user->frecuenciaVentas = rand(30, 100);
                $user->save();
            });
        });

    }
}
