<?php

use Illuminate\Database\Seeder;

class CommentsSeeder extends Seeder
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
        $postsIds = \App\Models\Post::all()->pluck('id')->toArray();
        if(\App\Models\Comment::count() == 0){
            for($i = 0; $i< 50; $i++){
                $data = [
                    'post_id'   => array_random($postsIds),
                    'user_id'   => array_random($usersIds),
                    'content'   =>  $faker->paragraph(mt_rand(1,20), true),

                ];
                \App\Models\Comment::create($data);
            }
        }
    }
}
