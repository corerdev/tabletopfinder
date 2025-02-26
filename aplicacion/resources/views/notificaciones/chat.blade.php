@extends('layouts.basico')

@section('title', 'Chat con ' . $recipiente->username)

@section('contenido')
<script>
document.addEventListener("DOMContentLoaded", function(){
  var chatHistory = document.querySelector('.chat-history');
  if (chatHistory) {
    chatHistory.scrollTop = chatHistory.scrollHeight;
  }
});
</script>
<div class="chat-container">
    <h2 class="chat-title">Chat con {{ $recipiente->username }}</h2>

    <div class="chat-history">
        @foreach($mensajes as $mensaje)
            <div class="chat-message {{ $mensaje->solicitante_uuid == auth()->user()->uuid ? 'chat-message-out' : 'chat-message-in' }}">
                <div class="chat-message-content">
                    <strong>{{ $mensaje->solicitante_username }}:</strong> {{ $mensaje->texto }}
                </div>
                <div class="chat-message-time">
                    <small>{{ $mensaje->hora }}</small>
                </div>
            </div>
        @endforeach
    </div>

    <div class="chat-form-container">
        <h2 class="chat-form-title">Enviar mensaje a {{ $recipiente->username }}</h2>
        <form action="{{ route('notificaciones.enviar') }}" method="POST" class="chat-form">
            @csrf
            <input type="hidden" name="para" value="{{ $recipiente->uuid }}">

            <div class="form-group">
                <label for="contenido" class="chat-label">Mensaje</label>
                <textarea name="contenido" id="contenido" class="chat-textarea" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn chat-send-button">Enviar</button>
        </form>
    </div>
</div>
@endsection
