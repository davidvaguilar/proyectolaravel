<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Sistema Ventas David Villegas Aguilar">
    <meta name="author" content="David Villegas Aguilar">
    <meta name="keyword" content="sistema, ventas, compras"> 
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>{{ config('app.name', 'Laravel') }}</title>    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
    <link href="css/plantilla.css" rel="stylesheet">
</head>
<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
    <div id="app">
        <header class="app-header navbar">
            <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!--PONER LOGO
            <a class="navbar-brand" href="#"> </a>-->
            <button class="navbar-toggler sidebar-toggler d-md-down-none"> <!--  type="button"-->
                <span class="navbar-toggler-icon"></span>
            </button>
            <ul class="nav navbar-nav d-md-down-none">
                <li class="nav-item px-3">
                    <a class="nav-link" href="#">{{ config('app.name', 'Laravel') }}</a>
                </li>
            </ul>
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="img/usuario/{{ Auth::user()->imagen }}" class="img-avatar" alt="{{ Auth::user()->usuario }}">
                        <span class="d-md-down-none">{{ Auth::user()->nombre }}</span> 
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-center">
                            <strong>Cuenta</strong>
                        </div>
                        <a class="dropdown-item" href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-lock"></i> Cerrar sesión</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
        </header>
        <div class="app-body">
            @if( Auth::check() )
                @if( Auth::user()->idrol == 1 )
                    @include('plantilla.sidebaradministrador')
                @elseif( Auth::user()->idrol == 2 )
                    @include('plantilla.sidebarvendedor')
                @elseif( Auth::user()->idrol == 3 )
                    @include('plantilla.sidebarcomprador')
                @else
                @endif
            @endif
            <!-- Contenido Principal -->
            @yield('contenido')
            <!-- /Fin del contenido principal -->
        </div>   
    </div>
    <footer class="app-footer">
        <span><a href="http://www.dyi.cl/">dyi.cl</a> &copy; 2019</span>
        <span class="ml-auto">Desarrollado por <a href="http://www.dyi.cl/">dyi.cl</a></span>
    </footer>

    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
</body>
</html>