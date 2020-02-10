<?php

use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Team::class)->create(['name' => 'Estech']);
        factory(\App\Team::class)->times(99)->create();
    }
}
