@if($log)
    <div class='divMenu'>
        <a href="{{ route('anuncios.create') }}">Crear partida </a>
    </div>
    <div class='divMenu'>
        <a href="{{ route('anuncios.buscarPartidas') }}">Buscar partidas</a>
    </div>
    <div class="dropdown-menu-container">
        <button class="dropdown-toggle">
            @php
                // Obtener la ruta del avatar desde la tabla fondos
                $avatarRuta = app('App\Http\Controllers\UsuariosController')->getAvatarRuta($authUser->avatar);
            @endphp
            <img src="{{ $avatarRuta ? asset($avatarRuta) : asset('images/fondos/avi1.jpg') }}" alt="Avatar" class="user-avatar" title="{{ $authUser->nombre }}">
        </button>
        <div class="dropdown-menu">
        <a href="{{ route('usuarios.miPerfil') }}">Mi Perfil</a>
        <a href="{{ route('notificaciones.notificaciones') }}">Notificaciones</a>
        </div>
    </div>
    <div class='divMenu'>
    <form action="{{ route('usuarios.logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-button" title="Cerrar sesión">
            <img src="{{ asset('images/cerrarsesion.png') }}" alt="Cerrar sesión" class="user-avatar">
        </button>
    </form>
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