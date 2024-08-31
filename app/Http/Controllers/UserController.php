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


        $upload_dir = public_path('storage/uploads/');
        if (!empty($_FILES)) {
            $upload_file = $upload_dir . $_FILES['avatar']['name'];
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_file)) {
                \Tinify\setKey("YQq20x4f4RfWLdHbfvCKLWbQ489b591r");
                $path_info = pathinfo($upload_file);

                $source = \Tinify\fromFile($upload_file);

                $resized = $source->resize(array(
                    "method" => "fit",
                    "width" => 70,
                    "height" => 70
                ));
                $resized->toFile($upload_dir . $path_info['filename'] . '_thumb' . '.' . 'jpg');
                $avatarPath = 'uploads/' . $path_info['filename'] . '_thumb' . '.' . 'jpg';
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
