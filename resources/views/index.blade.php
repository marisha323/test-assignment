<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .card-img-top {
            height: 200px; /* Задайте бажану висоту для зображення */
            object-fit: cover; /* Зберігає пропорції зображення */
        }
        .card {
            width: 100%; /* Заповнює ширину колонок */
            border: 1px solid #ddd; /* Тонка межа для картки */
        }
        .card-body {
            padding: 1.5rem; /* Трохи більше відступів для тексту */
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Список користувачів</h1>
    <div class="row">
        @foreach($users as $user)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $user->avatar }}" class="card-img-top" alt="Avatar">
                    <div class="card-body">
                        <h5 class="card-title">{{ $user->name }}</h5>
                        <p class="card-text">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links() }}
    </div>
</div>

</body>
</html>
