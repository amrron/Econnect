<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
    <form action="/register" method="post">
        @csrf
        {{-- <input type="text" name="username" id="" placeholder="username">
        <input type="text" name="password" id="" placeholder="password"> --}}
        <input type="email" name="email" id="" placeholder="email">
        <input type="text" name="password" id="" placeholder="password">
        {{-- <input type="text" name="name" id="" placeholder="name"> --}}
        <button type="submit">Kirim</button>
    </form>
</body>
</html>