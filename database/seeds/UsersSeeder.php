<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        if(\App\Models\User::count() == 0){
            for($i = 0; $i< 50; $i++){
                $token = str_random(60);
                while (\App\Models\User::where('api_token',$token)->count() > 0){
                    $token = str_random(60);
                }
                $data = [
                    'name'      => $faker->name,
                    'email'     => $faker->email,
                    'password'  => bcrypt(123456789),
                    'api_token' => $token,
                    'verified'  => 1,
                    'gender'    => 'male',
                    'image'     => 'default_user.png',
                    'phone'     => $faker->phoneNumber,
                    'address'   => $faker->address
                ];
                \App\Models\User::create($data);
            }
        }
    }
}
