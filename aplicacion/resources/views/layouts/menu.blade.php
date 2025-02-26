<!--
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////Seccion PC desktop////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
-->
<div class='menu'>
    <img class='logo' src="{{ asset('images\Logo.png') }}"/>
    <div class='divMenu'>
        <a href="{{ route('anuncios.landing') }}">Página principal </a>
        <a href="{{ route('juegos.listado') }}">Lista de juegos </a>
    </div>
    <x-Perfil />
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    var dropdownContainers = document.querySelectorAll('.dropdown-menu-container');
    dropdownContainers.forEach(function(container) {
        var toggle = container.querySelector('.dropdown-toggle');
        var menu = container.querySelector('.dropdown-menu');
        if(toggle && menu){
          toggle.addEventListener('click', function(e) {
              e.stopPropagation();
              menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
          });
        }
    });

    document.addEventListener('click', function() {
        dropdownContainers.forEach(function(container) {
            var menu = container.querySelector('.dropdown-menu');
            if(menu){
              menu.style.display = 'none';
            }
        });
    });
});
</script>

<!--
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////Seccion movil/////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
-->
<div class="mobile-menu-container">
  <button class="mobile-menu-toggle"><img class='logo' src="{{ asset('images\Logo.png') }}"/></button>
  <div class="mobile-menu">
    <a href="{{ route('anuncios.landing') }}">Página principal </a>
    <x-Perfil />
    </div>
    
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const mobileToggle = document.querySelector('.mobile-menu-toggle');
  const mobileMenu = document.querySelector('.mobile-menu');
  if(mobileToggle && mobileMenu){
    mobileToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      mobileMenu.style.display = mobileMenu.style.display === 'block' ? 'none' : 'block';
    });
  
    document.addEventListener('click', function() {
      mobileMenu.style.display = 'none';
    });
  }
});
</script>
