<?php

use Illuminate\Database\Seeder;
use App\Clients;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Clients::create([
            'nome1' => 'Reginaldo',
            'nome2' => 'Junior'
        ]);
    }
}
