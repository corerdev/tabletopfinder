@extends("layouts.basico")

@section('title','Bienvenido')

@section('contenido')

@guest
<div class="landingDiv">
  <h2>¡Bienvenido a Tabletop Finder!</h2>
  <img class="imgelefante" src="{{ asset('images/Logo.png') }}" alt="Logo"/>
  <p>Organiza y reserva tus partidas con facilidad. Explora nuestras funcionalidades y disfruta de una experiencia única.</p>
  
  <div class="landing-cta">
    <a class="btn-ingresar" href="{{ route('anuncios.buscarPartidas') }}">¡Quiero participar!</a>
    <a class="btn-secondary" href="{{ route('usuarios.create') }}">Registrarse</a>
  </div>
  
  <div class="collaborators-section">
    <h3>Con la colaboración de:</h3>
     <div class="collaborators-grid">
    <a href="https://thedragonchambergames.com" target="_blank" rel="noopener noreferrer">
      <img src="{{ asset('images/tiendas/tienda1.png') }}" alt="The Dragon Chamber Games">
    </a>
    <a href="https://www.orionalmeria.com" target="_blank" rel="noopener noreferrer">
      <img src="{{ asset('images/tiendas/tienda2.png') }}" alt="Ludere Aude">
    </a>
    <a href="https://www.instagram.com/ludereaude/" target="_blank" rel="noopener noreferrer"> 
      <img src="{{ asset('images/tiendas/tienda3.png') }}" alt="Colaborador 3">
    </a>
  </div>
  </div>
</div>
@else

<div class="dashboard-container">
  <div class="welcome-back">
    <h2>¡Bienvenido de vuelta, {{ Auth::user()->username }}!</h2>
    <p>¿Qué te apetece hacer hoy?</p>
  </div>
  
  <div class="dashboard-grid">
  
    <div class="main-content">
 
      <div class="dashboard-card featured-card">
        <h3>📢 Publicaciones de nuestras tiendas</h3>
        <div class="featured-content">
          <div class="store-post">
            <div>
              <h4>Tienda Central</h4>
              <p>Nuevo torneo de Catan este viernes - ¡Inscríbete ya!</p>
              <span class="post-date">Hace 2 horas</span>
            </div>
          </div>
      
        </div>
      </div>
      

      <div class="dashboard-card featured-card">
        <h3>👥 Últimas publicaciones de nuestras asociaciones</h3>
        <div class="featured-content">
          <div class="association-post">
            <div>
              <h4>Club de Rol</h4>
              <p>Sesiones de D&D cada sábado - Nuevos jugadores bienvenidos</p>
              <span class="post-date">Ayer</span>
            </div>
          </div>
    
        </div>
      </div>
    </div>
    
 
    <div class="sidebar">
    
      <div class="dashboard-card compact-calendar">
        <h3>📅 Tu calendario</h3>
        <div class="calendar-header">
          <h4>Junio 2023</h4>
        </div>
        <div class="compact-calendar-grid">
          @for($i = 1; $i <= 30; $i++)
            <div class="compact-calendar-day @if($i == 19) today @endif">
              {{ $i }}
              @if($i == 5 || $i == 12 || $i == 19)
                <div class="event-dot"></div>
              @endif
            </div>
          @endfor
        </div>
        <a href="{{ route('usuarios.miPerfil') }}" class="btn-small">Ver todas</a>
      </div>
      
   
      <div class="quick-actions">
        <a href="{{ route('anuncios.create') }}" class="btn-action">➕ Crear partida</a>
        <a href="{{ route('anuncios.buscarPartidas') }}" class="btn-action">🔍 Buscar partidas</a>
      </div>
    </div>
  </div>
</div>
@endguest

@endsection