@extends("layouts.basico")

@section('title','Bienvenido')

@section('contenido')

<div class="landingDiv">
  <h2>¡Bienvenido a Tabletop Finder!</h2>
  <img class="imgelefante" src="{{ asset('images/Logo.png') }}" alt="Logo"/>
  <p>Organiza y reserva tus partidas con facilidad. Explora nuestras funcionalidades y disfruta de una experiencia única.</p>
  <a class="btn-ingresar" href="{{ route('anuncios.buscarPartidas') }}">¡Quiero participar!</a>
</div>


@endsection