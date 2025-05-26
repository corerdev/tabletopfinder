@extends('layouts.basico')

@section('title', 'Chat con ' . $recipiente->username)

@section('contenido')
<script>
document.addEventListener("DOMContentLoaded", function() {
  const chatHistory = document.querySelector('.chat-history');
  
  // Función para mantener el scroll al final
  function scrollToBottom() {
    if (chatHistory) {
      chatHistory.scrollTop = chatHistory.scrollHeight;
    }
  }

  // Scroll al final al cargar la página
  scrollToBottom();

  // Observador de mutaciones para detectar nuevos mensajes
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.addedNodes.length) {
        scrollToBottom();
      }
    });
  });

  // Configurar el observador
  if (chatHistory) {
    observer.observe(chatHistory, {
      childList: true,
      subtree: true
    });
  }

  // También scroll al final cuando la ventana cambia de tamaño
  window.addEventListener('resize', scrollToBottom);
});
</script>
<div class="chat-container">
    <h2 class="chat-title">Mensajes con {{ $recipiente->username }}</h2>

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
