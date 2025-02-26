@if($log)
    <div class='divMenu'>
        <a href="{{ route('anuncios.create') }}">Crear partida </a>
    </div>
    <div class='divMenu'>
        <a href="{{ route('anuncios.buscarPartidas') }}">Buscar partidas</a>
    </div>
    <div class="dropdown-menu-container">
        <button class="dropdown-toggle">Hola, {{$nombre}}</button>
        <div class="dropdown-menu">
        <a href="{{ route('usuarios.miPerfil') }}">Mi Perfil</a>
        <a href="{{ route('notificaciones.notificaciones') }}">Notificaciones</a>
        </div>
    </div>
    <div class='divMenu'>
        <a href="{{ route('usuarios.logout') }}">Cerrar sesión</a>
    </div>
    @if(isset($authUser) && $authUser->isAdmin == 1)


    <div class="dropdown-menu-container">
        <button class="dropdown-toggle">Admin</button>
        <div class="dropdown-menu">
            <a href="{{ route('fondos.create') }}">Subir imagen</a>
            <a href="{{ route('juegos.create') }}">Registrar juego</a>
            <a href="{{ route('usuarios.admin') }}">Panel de administrador</a>
        </div>
    </div>
    @endif
@else

    <div class='divMenu'>
        <a href="{{ route('usuarios.login') }}">Identifícate</a>
    </div>

@endif