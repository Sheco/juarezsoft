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
            'password'=>Hash::make('juarez123')
        ]);

        $user->assignRole('administrador');

    }
}
