@extends('layouts.basico')

@section('title', 'Perfil de ' . $usuario->username)

@section('contenido')

<div class="perfil-container">
    <div class="imagen-container">
        <img id="imagen-preview" src="{{ asset($ruta) }}" alt="Vista previa">
    </div>
    <div class="avatar-edit-container">
        <a href="{{ route('usuarios.editAvatar', $usuario->uuid) }}" class="edit-button edit-avatar-button">Editar avatar</a>
    </div>

    <h2>Perfil personal de {{ $usuario->username }}</h2>

    <div class="perfil-info">
        <p><strong>Nombre:</strong> {{ $usuario->username }}</p>
        <a href="{{ route('usuarios.editUser', $usuario->uuid) }}" class="edit-button">✎</a>
    </div>

    <div class="perfil-info">
        <p><strong>Email:</strong> {{ $usuario->email }}</p>
        <a href="{{ route('usuarios.editEmail', $usuario->uuid) }}" class="edit-button">✎</a>
    </div>

    <div class="perfil-info">
        <p><strong>Descripción:</strong> {{ $usuario->descripcion }}</p>
        <a href="{{ route('usuarios.editDesc', $usuario->uuid) }}" class="edit-button">✎</a>
    </div>


@if($usuario->esTienda == 1)
    <h3>Información de la Tienda</h3>
    <div class="perfil-info">
        <p><strong>Dirección de la Tienda:</strong> {{ $usuario->dirTienda }}</p>
    </div>
    <div class="perfil-info">
        <p><strong>Descripción de la Tienda:</strong> {{ $usuario->descTienda }}</p>
    </div>
    <div class="perfil-info">
        <p><strong>Email Tienda:</strong> {{ $usuario->emailTienda }}</p>
    </div>
    <div class="perfil-info">
        <p><strong>Teléfono Tienda:</strong> {{ $usuario->telfTienda }}</p>
    </div>
    <a href="{{ route('usuarios.editTienda', $usuario->uuid) }}" class="edit-button">Editar tienda</a>
@endif

<h3>Partidas en las que participas</h3>
<div class="tabla-container">
    <table class="perfil-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Juego</th>
                <th>Plazas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($participa as $parti)
                <tr>
                    <td><a href="{{ route('anuncios.show', $parti->uuid) }}">{{ $parti->titulo }}</a></td>
                    <td>{{ $parti->descripcion }}</td>
                    <td><a href="{{ route('juegos.show', $parti->nombre_juego) }}">{{ $parti->nombre_juego }}</a></td>
                    <td>{{ $parti->plazas_ocupadas }} / {{ $parti->plazas }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Este usuario no ha publicado anuncios.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3>Tus anuncios</h3>
<div class="tabla-container">
    <table class="perfil-table">
        <thead>
            <tr>
                <th>Título</th>
                <th>Descripción</th>
                <th>Juego</th>
                <th>Plazas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anuncios as $anuncio)
                <tr>
                    <td><a href="{{ route('anuncios.show', $anuncio->uuid) }}">{{ $anuncio->titulo }}</a></td>
                    <td>{{ $anuncio->descripcion }}</td>
                    <td><a href="{{ route('juegos.show', $anuncio->nombre_juego) }}">{{ $anuncio->nombre_juego }}</a></td>
                    <td>{{ $anuncio->plazas_ocupadas }} / {{ $anuncio->plazas }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Este usuario no ha publicado anuncios.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<h3>Chats Activos</h3>
@if ($chats->isEmpty())
    <p>No tienes conversaciones activas.</p>
@else
    <div class="lista-chats">
        @foreach($chats as $chat)
            <div class="chat-usuario">
                <a href="{{ route('notificaciones.mostrarChat', $chat->uuid) }}">
                    <img src="{{ asset($fondosAvatar[$chat->avatar]->ruta ?? 'default-avatar.png') }}" 
                         alt="Avatar de {{ $chat->username }}">
                    <div>{{ $chat->username }}</div>
                </a>
            </div>
        @endforeach
    </div>
@endif
</div>
@endsection
