<?php

use Illuminate\Database\Seeder;
use \App\User;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'username' => 'fwpvt',
            'password' => Hash::make('Felixawpw121'),
            'roles' => 1
        ]);
        User::create([
            'username' => 'tp',
            'password' => Hash::make('Felixawpw121'),
            'roles' => 2
        ]);
        User::create([
            'username' => 'gc',
            'password' => Hash::make('Felixawpw121'),
            'roles' => 2
        ]);

    }
}
