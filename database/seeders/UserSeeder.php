<?php

namespace Database\Seeders;

use App\Models\User;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Intervention\Image\Laravel\Facades\Image;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker=Faker::create();


        // Отримуємо URL до збереженого зображення
        $url = url('https://cdn-icons-png.flaticon.com/512/8307/8307221.png');

        for ($i=0;$i<45;$i++){
            User::create([
            'name'=>$faker->name,
            'email'=> $faker->unique()->safeEmail,
            'password'=>Hash::make('password'),
            'avatar' => $url

            ]);
        }
    }
}
