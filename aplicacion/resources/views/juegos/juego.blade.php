@extends('layouts.basico')

@section('title', $juego->nombre)

@section('contenido')

<div class="perfil-container">
    <div class="imagen-container">
        <h2>{{ $juego->nombre }}</h2>
        <img src="{{ asset('images/juegos/' . $juego->rutaimagen) }}"/>
        <p>{{ $juego->descripcion }}</p>
        @if($juego->tipo == 'versus')
            <p>
                <strong>Juego de jugador contra jugador</strong> 
            </p>
        @endif
        @if($juego->tipo == 'coop')
            <p>
                <strong>Juego cooperativo</strong> 
            </p>
        @endif  
        @if($juego->tipo == 'versus/coop')
            <p>
                <strong>Juego cooperativo y jugador contra jugador</strong> 
            </p>
        @endif          
    </div>   
</div>

<div class="tabla-container">
<h3>Anuncios de {{ $juego->nombre }}</h3>
<table  class="perfil-table">
    <thead>
        <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Plazas</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($anuncios as $anuncio)
            <tr>
                <td><a href="{{ route('anuncios.show', $anuncio->uuid) }}">{{ $anuncio->titulo }}</a></td>
                <td>{{ $anuncio->descripcion }}</td>
                <td>{{ $anuncio->plazas_ocupadas }} / {{ $anuncio->plazas }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">Este Juego no tiene anuncios.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>

@endsection