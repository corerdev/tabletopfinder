@extends('layouts.basico')

@section('title', $anuncio->titulo)

@section('contenido')

<div class="perfil-container">
    <h2>{{ $anuncio->titulo }}</h2>
    @if($usuarioEsCreador)
  <form action="{{ route('anuncios.destroy', $anuncio->uuid) }}" method="POST" onsubmit="return confirm('¿Estás seguro de borrar esta partida?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="botonEchar">Borrar partida</button>
  </form>
@endif
</div>

<div class="jugador" style="margin-bottom: 20px; text-align: center;">
  <h3>Creador de la partida</h3>
  <a href="{{ route('usuarios.show', $creador->username) }}" target="_blank">
    <img src="{{ asset($fondosAvatar[$creador->avatar]->ruta) }}" alt="Avatar de {{ $creador->username }}">
    <div>{{ $creador->username }}</div>
  </a>
</div>

@if($usuarioYaInscrito && !$usuarioEsCreador && !$usuarioYaSolicitante)
    <form action="{{ route('notificaciones.abandonar') }}" method="POST" class="perfil-form">
        @csrf
        <!-- Los datos que se envían: 
             - notificado: el creador del anuncio 
             - solicitante: el usuario autenticado 
             - anuncio: el uuid del anuncio 
             - tipo: 'solicitud_unirse'
        -->
        <input type="hidden" name="notificado" value="{{ $anuncio->useruuid }}">
        <input type="hidden" name="solicitante" value="{{ auth()->user()->uuid }}">
        <input type="hidden" name="anuncio" value="{{ $anuncio->uuid }}">
        <input type="hidden" name="tipo" value="solicitud_unirse">
        <button class="btn-abandonar" type="submit">Abandonar la partida</button>
    </form>
@endif

@if(!$usuarioYaInscrito && !$usuarioEsCreador && !$usuarioYaSolicitante)
    <form action="{{ route('notificaciones.store') }}" method="POST" class="perfil-form">
        @csrf
        <input type="hidden" name="notificado" value="{{ $anuncio->useruuid }}">
        <input type="hidden" name="solicitante" value="{{ auth()->user()->uuid }}">
        <input type="hidden" name="anuncio" value="{{ $anuncio->uuid }}">
        <input type="hidden" name="tipo" value="solicitud_unirse">
        <button class="btn-ingresar" type="submit">Quiero unirme</button>
    </form>
@endif

<div class="anuncio-container" style="background-image: url('{{ asset($fondo->ruta) }}');">
    <div class="anuncio-titulo">{{ $anuncio->titulo }}</div>
    <div class="anuncio-detalle">Medio: {{ $anuncio->medio }}</div>
    <div class="anuncio-detalle">Juego: {{ $nombreJuego->first()->nombre }}</div>
    <div class="anuncio-detalle">Descripción: {{ $anuncio->descripcion }}</div>
    <div class="anuncio-detalle">Plazas: {{ $anuncio->plazas_ocupadas }} / {{ $anuncio->plazas }}</div>
</div>
<div class="jugadores" style="margin-top: 20px;">
    <h3>Jugadores en la partida</h3>
    <div class="lista-jugadores" style="display: flex; flex-wrap: wrap; gap: 15px;">
        @foreach($usuarios as $usuario)
            <div class="jugador" style="text-align: center;">
                <a href="/tabletopFinder/aplicacion/public/usuarios/{{ $usuario->username }}" target="_blank">
                    <img src="{{ asset($fondosAvatar[$usuario->avatar]->ruta) }}" alt="Avatar de {{ $usuario->username }}">
                    <div>{{ $usuario->username }}</div>
                </a>
                <form action="{{ route('notificaciones.deleteUserFromGame') }}" method="POST" style="margin-top: 5px;">
                    @csrf
                    <input type="hidden" name="user_uuid" value="{{ $usuario->uuid }}">
                    <input type="hidden" name="anuncio_uuid" value="{{ $anuncio->uuid }}">
                    @if($usuarioEsCreador)
                    <button type="submit" class="botonEchar">Echar de la partida</button>
                    @endif
                </form>
            </div>
        @endforeach
    </div>
</div>

@endsection