<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Vue TodoList App</title>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+Da+2:wght@500&display=swap" rel="stylesheet">

    <script src="https://use.fontawesome.com/186d66fd50.js"></script>
    <style>
        html, body {
            font-family: 'Baloo Da 2', cursive;
            background-color: #45eada;
        }
    </style>
    @vite('resources/js/app.js')
</head>
<body>
<main id="app">
    <todo-list-component></todo-list-component>
</main>

</body>
</html>
