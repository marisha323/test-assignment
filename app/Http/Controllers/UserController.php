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


        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Генерація унікального імені файлу
            $filename = time() . '_' . $file->getClientOriginalName();

            // Збереження файлу у директорії `storage/app/public/uploads`
            $filePath = $file->storeAs('uploads', $filename, 'public');

            // Виведення шляху для перевірки
            $fullPath = Storage::path($filePath);
            dd('File path: ' . $fullPath);

            // Використання бібліотеки Tinify для оптимізації зображення
            \Tinify\setKey("YQq20x4f4RfWLdHbfvCKLWbQ489b591r");

            try {
                $source = \Tinify\fromFile($fullPath);

                $resized = $source->resize([
                    "method" => "fit",
                    "width" => 70,
                    "height" => 70
                ]);

                $resizedPath = 'uploads/' . pathinfo($filename, PATHINFO_FILENAME) . '_thumb.jpg';
                $resized->toFile(Storage::path($resizedPath));

                $avatarPath = $resizedPath;

            } catch (\Exception $e) {
                \Log::error('Tinify error: ' . $e->getMessage());
                return response()->json(['error' => 'Image processing error'], 500);
            }
        }

        $url =url(Storage::url($avatarPath));
//dd($url);
        // Створення користувача
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $url,
        ]);

        return response()->json($user);
    }
}
