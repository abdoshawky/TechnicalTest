<?php

use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'ar'    => 'فن',
                'en'    => 'Art'
            ],
            [
                'ar'    => 'رياضة',
                'en'    => 'Sport'
            ],
            [
                'ar'    => 'علوم',
                'en'    => 'Science'
            ],
            [
                'ar'    => 'ألعاب',
                'en'    => 'Games'
            ],
        ];
        if(\App\Models\Category::count() == 0){
            foreach ($categories as $category){
                \App\Models\Category::create(['name'=>$category]);
            }
        }
    }
}
