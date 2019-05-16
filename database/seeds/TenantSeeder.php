<?php

use Illuminate\Database\Seeder;
use \App\Tenant;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Tenant::create([
            'nama' => 'Tunjungan Plaza',
            'user_id' => 2
        ]);
        Tenant::create([
            'nama' => 'Grand City',
            'user_id' => 3
        ]);

    }
}
