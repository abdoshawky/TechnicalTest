<?php

use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $usersIds = \App\Models\User::all()->pluck('id')->toArray();
        $categoriesIds = \App\Models\Category::all()->pluck('id')->toArray();
        if(\App\Models\Post::count() == 0){
            for($i = 0; $i< 50; $i++){
                $data = [
                    'category_id'   => array_random($categoriesIds),
                    'user_id'       => array_random($usersIds),
                    'title'         => $faker->sentence(mt_rand(1,20), true),
                    'content'       => $faker->paragraph(mt_rand(1,20), true)
                ];
                \App\Models\Post::create($data);
            }
        }

    }
}
