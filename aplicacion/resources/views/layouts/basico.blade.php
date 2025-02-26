<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.css" rel="stylesheet"/> 
  <script src="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.js"></script>

  @yield('scripts')
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Trade+Winds&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css.css') }}">
  <title>@yield('title')</title>
</head>
<body>

<div id="notificaciones-alert">
  Tienes <span id="notificaciones-count"></span> <span id="notificaciones-word"></span>.
  <a href="{{ route('notificaciones.notificaciones') }}">Ver notificaciones</a>
  <button id="close-notificaciones">X</button>
</div>

  @include('layouts.menu')
  
    @yield('contenido')

    <script>
  $(document).ready(function(){
      function checkNotificaciones() {
          $.ajax({
              url: '{{ route('notificaciones.contarNotificaciones') }}',
              method: 'GET',
              dataType: 'json'
          }).done(function(response){
              var dismissed = sessionStorage.getItem('notificacionesPanelDismissed');
              if(response.count > 0){
                  if(dismissed !== null && parseInt(dismissed) === response.count) {
                      $('#notificaciones-alert').hide();
                  } else {
                      $('#notificaciones-count').text(response.count);
                      if(response.count == 1){
                          $('#notificaciones-word').text('notificación');
                      } else {
                          $('#notificaciones-word').text('notificaciones');
                      }
                      $('#notificaciones-alert').show();
                  }
              }
          }).fail(function(){
              console.error('No se pudo obtener el número de notificaciones.');
              $('#notificaciones-alert').hide();
          });
      }
      
      checkNotificaciones();
      
      $('#close-notificaciones').click(function(){
          var currentCount = $('#notificaciones-count').text();
          if(currentCount !== '') {
              sessionStorage.setItem('notificacionesPanelDismissed', currentCount);
          }
          $('#notificaciones-alert').hide();
      });
      
      $('#notificaciones-alert a').click(function(){
          var currentCount = $('#notificaciones-count').text();
          if(currentCount !== '') {
              sessionStorage.setItem('notificacionesPanelDismissed', currentCount);
          }
      });
  });
</script>



</body>
</html>