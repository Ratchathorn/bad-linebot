<?php

namespace Database\Seeders;

use App\Models\GoldType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $obj = new GoldType();
        $obj->name = "Necklace";
        $obj->size = "12";
        $obj->save();

        $obj = new GoldType();
        $obj->name = "Ring";
        $obj->size = fake()->randomNumber(3);
        $obj->save();

        $obj = new GoldType();
        $obj->name = "Earpiece";
        $obj->size = fake()->randomNumber(3);
        $obj->save();

        $obj = new GoldType();
        $obj->name = "Ring";
        $obj->size = fake()->randomNumber(3);
        $obj->save();
    }
}
