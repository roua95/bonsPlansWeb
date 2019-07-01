<?php

use Illuminate\Database\Seeder;
use App\Category;
use App\image;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $category= new Category();
        $category ->category_name = "cafe";
        $category->save();

        $imagePlan = new Image();
        $imagePlan->save();
        $imagePlan->image()->attach($imagePlan);
    }
}
