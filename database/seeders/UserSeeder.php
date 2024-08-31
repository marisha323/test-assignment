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
        $originalAvatarPath = public_path('avatars\user.png'); // Шлях до оригінального зображення

        // Отримуємо URL до збереженого зображення
        $url = url(Storage::url('avatars\user.png'));

//        $url = 'http://127.0.0.1:8000' . Storage::url('avatars/user.png');

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
