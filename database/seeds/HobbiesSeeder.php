<?php

use Illuminate\Database\Seeder;

class HobbiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hobbies = [
            'Writing','Reading','Painting','Football','Tennis','Cooking'
        ];

        if(\App\Models\Hobby::count() == 0){
            foreach ($hobbies as $hobby){
                \App\Models\Hobby::create(['name'=>$hobby   ]);
            }
        }

    }
}
