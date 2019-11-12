<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas David Villegas Aguilar">
    <meta name="author" content="David Villegas Aguilar">
    <meta name="keyword" content="sistema, ventas, compras">  
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="css/plantilla.css" rel="stylesheet">
</head>

<body class="app flex-row align-items-center">

    <div class="container">
        @yield('login')
    </div>

    <script src="js/plantilla.js"></script>

</body>
</html>