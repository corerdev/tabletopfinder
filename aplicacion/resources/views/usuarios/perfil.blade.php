@extends('layouts.basico')

@section('title', 'Perfil de ' . $usuario->username)

@section('contenido')

    <div class="perfil-container">
        <h2>{{ $usuario->username }}</h2>
        <div class="imagen-container">
            <img id="imagen-preview" src="{{ asset($ruta) }}" alt="Vista previa">
        </div>
        <a href="{{ route('notificaciones.mostrarChat', $usuario->uuid) }}" class="chat-button">Ver Chat</a>

        <div class="perfil-info">
            <p><strong>Nombre:</strong> {{ $usuario->username }}</p>
        </div>

        <div class="perfil-info">
            <p><strong>Email:</strong> {{ $usuario->email }}</p>
        </div>

        <div class="perfil-info">
            <p><strong>Descripción:</strong> {{ $usuario->descripcion }}</p>
        </div>
        @if($usuario->esTienda == 1)
        <h3>Información de la tienda</h3>
        <p><strong>Dirección de la tienda:</strong> {{ $usuario->dirTienda }}</p>
        <p><strong>Descripción de la tienda:</strong> {{ $usuario->descTienda }}</p>
        <p><strong>Email tienda:</strong> {{ $usuario->emailTienda }}</p>
        <p><strong>Teléfono tienda:</strong> {{ $usuario->telfTienda }}</p>
    @endif
    </div>
    
    <div class="tabla-container">
        <h3>Anuncios de {{ $usuario->username }}</h3>
        <table  class="perfil-table">
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
                        <td><a href="{{ route('juegos.show', $anuncio->nombre_juego) }}">{{ $anuncio->nombre_juego }}</td>
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

@endsection