@extends('layouts.basico')

@section('title', 'Mis Notificaciones')

@section('contenido')

<div class="notificaciones-container">
    <h2 class="notificaciones-title">Mis Notificaciones</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @if($notificaciones->isEmpty())
        <p class="no-notificaciones">No tienes notificaciones.</p>
    @else
        <ul class="notification-list">
            @foreach($notificaciones as $notificacion)
                <li class="notification-item">
                    @if($notificacion->tipo == 'solicitud_unirse')
                        <p>
                            <strong>Solicitud de unión:</strong> El usuario 
                            <a href="{{ '/tabletopFinder/aplicacion/public/usuarios/' . $notificacion->solicitante_username }}" target="_blank">
                                {{ $notificacion->solicitante_username }}
                            </a>
                            desea unirse a tu anuncio 
                            <a href="{{ '/tabletopFinder/aplicacion/public/anuncios/' . $notificacion->anuncio_uuid }}" target="_blank">
                                {{ $notificacion->anuncio_titulo }}
                            </a>.
                        </p>
                        <div class="notification-actions">
                            <form action="{{ route('notificaciones.accept', $notificacion->id) }}" method="POST" class="notification-form">
                                @csrf
                                <button type="submit" class="btn btn-accept">Aceptar</button>
                            </form>
                            <form action="{{ route('notificaciones.reject', $notificacion->id) }}" method="POST" class="notification-form">
                                @csrf
                                <button type="submit" class="btn btn-reject">Rechazar</button>
                            </form>
                        </div>
                    @endif
                    @if($notificacion->tipo == 'confirmación_unirse')
                        <p>
                            <strong>Solicitud de unión:</strong> El usuario 
                            <a href="{{ '/tabletopFinder/aplicacion/public/usuarios/' . $notificacion->solicitante_username }}" target="_blank">
                                {{ $notificacion->solicitante_username }}
                            </a>
                            te ha aceptado en 
                            <a href="{{ '/tabletopFinder/aplicacion/public/anuncios/' . $notificacion->anuncio_uuid }}" target="_blank">
                                {{ $notificacion->anuncio_titulo }}
                            </a>.
                        </p>
                        
                        <div class="notification-actions">
                            <form action="{{ route('notificaciones.delete', $notificacion->id) }}" method="POST" class="notification-form">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-delete">Eliminar notificación</button>
                            </form>
                        </div>
                    @endif
                    @if($notificacion->tipo == 'declinación_unirse')
                        <p>
                            <strong>Solicitud de unión:</strong> 
                            <a href="{{ url('/tabletopFinder/aplicacion/public/usuarios/' . $notificacion->solicitante_uuid) }}" target="_blank">
                                {{ $notificacion->solicitante_username }}
                            </a> ha rechazado tu solicitud de unirte a 
                            <a href="{{ '/tabletopFinder/aplicacion/public/anuncios/' . $notificacion->anuncio_uuid }}" target="_blank">
                                {{ $notificacion->anuncio_titulo }}
                            </a>.
                        </p>
                        
                        <div class="notification-actions">
                            <form action="{{ route('notificaciones.delete', $notificacion->id) }}" method="POST" class="notification-form">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-delete">Eliminar notificación</button>
                            </form>
                        </div>
                    @endif
                    @if($notificacion->tipo == 'expulsion')
                        <p>
                            <strong>Notificación de expulsion:</strong> Has sido expulsado de
                            <a href="{{ '/tabletopFinder/aplicacion/public/anuncios/' . $notificacion->anuncio_uuid }}" target="_blank">
                                {{ $notificacion->anuncio_titulo }}
                            </a>.
                        </p>
                        
                        <div class="notification-actions">
                            <form action="{{ route('notificaciones.delete', $notificacion->id) }}" method="POST" class="notification-form">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-delete">Eliminar notificación</button>
                            </form>
                        </div>
                    @endif
                    @if($notificacion->tipo == 'abandono')
                        <p>
                            <strong>Notificación de abandono:</strong> 
                            <a href="{{ url('/tabletopFinder/aplicacion/public/usuarios/' . $notificacion->solicitante_uuid) }}" target="_blank">
                                {{ $notificacion->solicitante_username }}
                            </a> ha abandonado tu partida
                            <a href="{{ '/tabletopFinder/aplicacion/public/anuncios/' . $notificacion->anuncio_uuid }}" target="_blank">
                                {{ $notificacion->anuncio_titulo }}
                            </a>.
                        </p>
                        
                        <div class="notification-actions">
                            <form action="{{ route('notificaciones.delete', $notificacion->id) }}" method="POST" class="notification-form">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-delete">Eliminar notificación</button>
                            </form>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    @if($mensajes->isEmpty())
        <p class="no-mensajes">No tienes mensajes.</p>
    @else
        <ul class="notification-list">
            @foreach($mensajes as $mensaje)
                <li class="notification-item">
                    @if($mensaje->tipo == 'chat_aviso')
                        <p>
                            <strong>Notificación de mensaje:</strong> 
                            <a href="{{ url('/tabletopFinder/aplicacion/public/usuarios/' . $mensaje->solicitante_uuid) }}" target="_blank">
                                {{ $mensaje->solicitante_username }}
                            </a> te ha enviado un mensaje.
                        </p>
                        
                        <div class="notification-actions">
                            <form action="{{ route('notificaciones.delete', $mensaje->id) }}" method="POST" class="notification-form">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-delete">Eliminar notificación</button>
                            </form>
                            <form action="{{ route('notificaciones.mostrarChat', $mensaje->solicitante_uuid) }}" method="GET" class="notification-form">
                                @csrf
                                @method('POST')
                                <button type="submit" class="btn btn-chat">Ver el mensaje</button>
                            </form>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>

@endsection

