<?php

use Illuminate\Database\Seeder;
use App\Plan;
class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cafe = \App\Category::where("category_name", "cafe")->first();

        $plan = new Plan();
        $plan->title = "café Name";
        $plan->adresse = "Sahloul";
        $plan->description = "c'est un café de qualité ";
        $plan->longitude = "10";
        $plan->lattitude = "12";
        $plan->region = "Sousse";
        $plan->rate = 10;
        $plan->description = "c'est un café de qualité ";
        $plan->user_id="1";

        $plan->save();
        $plan->category()->attach($cafe);
    }
}
