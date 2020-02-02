<?php

use Illuminate\Database\Seeder;

class IndustriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Industry::class, 50)->make()->each(function ($industry) {
            $industry->save();
        });
    }
}
