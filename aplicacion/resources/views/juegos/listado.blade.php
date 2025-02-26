@extends('layouts.basico')

@section('title', 'Lista de Juegos')

@section('contenido')
<div class="juegos-container">
  <h2 class="juegos-title">Juegos Registrados</h2>
  
  @if($juegos->count())
    <div class="juegos-grid">
      @foreach($juegos as $juego)
        <div class="juego-item">
          <a href="{{ route('juegos.show', $juego->nombre) }}">
            <img src="{{ asset('images/juegos/' . $juego->rutaimagen) }}" alt="{{ $juego->nombre }}" class="juego-img">
            <h3 class="juego-name">{{ $juego->nombre }}</h3>
          </a>
        </div>
      @endforeach
    </div>
  @else
    <p>No hay juegos registrados.</p>
  @endif
</div>
@endsection
