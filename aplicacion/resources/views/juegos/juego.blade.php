@extends('layouts.basico')

@section('title', $juego->nombre)

@section('contenido')

<div class="juego-detalle-container">
   
    <div class="juego-header-container">
        <div class="juego-header-content">
            <h1 class="juego-titulo">{{ $juego->nombre }}</h1>
            <img src="{{ asset('images/juegos/' . $juego->rutaimagen) }}" class="juego-imagen"/>
        </div>
    </div>

    <div class="juego-info-wrapper">
    
        <div class="juego-texto-container">
            <p class="juego-descripcion">{{ $juego->descripcion }}</p>
            
            @if($juego->tipo == 'versus')
                <p class="juego-tipo">
                    <strong>Juego de jugador contra jugador</strong> 
                </p>
            @endif
            @if($juego->tipo == 'coop')
                <p class="juego-tipo">
                    <strong>Juego cooperativo</strong> 
                </p>
            @endif  
            @if($juego->tipo == 'versus/coop')
                <p class="juego-tipo">
                    <strong>Juego cooperativo y jugador contra jugador</strong> 
                </p>
            @endif
        </div>

        <div class="juego-anuncios-flotante">
            <button class="anuncios-toggle-btn">
                <span class="toggle-icon">►</span>
                <span class="toggle-text">Anuncios de {{ $juego->nombre }}</span>
            </button>
            
            <div class="tabla-anuncios-container">
                <table class="anuncios-table">
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
                                <td colspan="3">Este juego no tiene anuncios.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('.anuncios-toggle-btn');
    const tablaContainer = document.querySelector('.tabla-anuncios-container');
    
    if(toggleBtn && tablaContainer) {
        toggleBtn.addEventListener('click', function() {
            tablaContainer.classList.toggle('visible');
            const icon = this.querySelector('.toggle-icon');
            icon.textContent = tablaContainer.classList.contains('visible') ? '▼' : '►';
        });
    }
});
</script>
@endsection

@endsection