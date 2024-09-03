<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use GuzzleHttp\Client;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(6);
        return view('/index', compact('users'));
    }

    public function store(Request $request)
    {
        //  dd($request);
        // Валідація даних
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }




        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Генерація унікального імені файлу
            $filename = time() . '_' . $file->getClientOriginalName();

            // Збереження файлу у директорії `storage/app/public/uploads`
            $path = $file->storeAs('uploads', $filename, 'public');

            // Отримання локального шляху
            $fullPath = Storage::path($path);
            var_dump($fullPath);

            // Використання бібліотеки Tinify для оптимізації зображення
            \Tinify\setKey("YQq20x4f4RfWLdHbfvCKLWbQ489b591r");

            try {
                // Завантаження зображення з локального файлу
                $source = \Tinify\fromFile($fullPath);

                // Розмір зображення
                $resized = $source->resize([
                    "method" => "fit",
                    "width" => 70,
                    "height" => 70
                ]);

                // Отримання імені файлу без розширення
                $filenameWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);

                // Збереження оптимізованого зображення
                $resizedPath = 'uploads/' . $filenameWithoutExtension . '_thumb.jpg';
                $resized->toFile(Storage::path($resizedPath));

                // Оновлюємо шлях до зображення
                $avatarPath = $resizedPath;

            } catch (\Exception $e) {
                \Log::error('Tinify error: ' . $e->getMessage());
                return response()->json(['error' => 'Image processing error'], 500);
            }
        }
        $url = url(Storage::url($avatarPath));
//dd($url);
        // Створення користувача
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $url ?? null,
        ]);

        return response()->json($user);
    }
}
