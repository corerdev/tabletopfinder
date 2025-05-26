@extends("layouts.basico")

@section('title','Crear Anuncio')

@section('contenido')

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Expresiones regulares para validación
  const tituloRegex = /^.{1,35}$/;
  const plazasRegex = /^[1-9][0-9]?$|^99$/;
  const descripcionRegex = /^[\s\S]{1,500}$/;
  const desccortaRegex = /^.{1,30}$/;

  // Obtener elementos del DOM
  const tituloField = document.getElementById('titulo');
  const plazasField = document.getElementById('plazas');
  const descripcionField = document.getElementById('descripcion');
  const desccortaField = document.getElementById('desccorta');
  const fondoField = document.getElementById('fondo');
  const juegoField = document.querySelector('select[name="juegocode"]');
  const submitBtn = document.querySelector('.crearanuncio-btn');

  // Objeto para rastrear si un campo ha sido interactuado
  const interactedFields = {titulo: false, plazas: false, descripcion: false, desccorta: false, fondo: false, juego: false};

  // Función de validación genérica (ahora siempre valida, pero solo muestra errores después de la primera interacción)
  function validateField(field, regex, errorMsgEl, fieldName, customError = '') {
    const value = field.value.trim();
    const isValid = value !== '' && regex.test(value);
    
    if(interactedFields[fieldName]) {
      if(!isValid) {
        field.style.border = '2px solid red';
        if(errorMsgEl) {
          errorMsgEl.textContent = customError || 'Valor inválido.';
        }} else {
        field.style.border = '';
        if(errorMsgEl) {
          errorMsgEl.textContent = '';
        }}}
    return isValid;}

  // Función para validar selects
  function validateSelect(selectField, errorMsgEl, fieldName) {
    const isValid = selectField.value !== '';
    
    if(interactedFields[fieldName]) {
      if(!isValid) {
        selectField.style.border = '2px solid red';
        if(errorMsgEl) {
          errorMsgEl.textContent = 'Debes seleccionar una opción.';
        }} else {
        selectField.style.border = '';
        if(errorMsgEl) {
          errorMsgEl.textContent = '';
        }}}
    return isValid;}

  // Función para verificar todo el formulario
  function checkFormValidity() {
    let isFormValid = true;
    
    // Validar campos de texto (siempre validar, pero solo mostrar errores después de la interacción)
    if(tituloField) {
      const tituloValid = validateField(tituloField, tituloRegex, 
        tituloField.nextElementSibling?.nextElementSibling, 
        'titulo', 'Máximo 35 caracteres permitidos');
      isFormValid = isFormValid && tituloValid;
    }
    
    if(plazasField) {
      const plazasValid = validateField(plazasField, plazasRegex, plazasField.nextElementSibling?.nextElementSibling, 'plazas', 'Solo números entre 1 y 99');
      isFormValid = isFormValid && plazasValid;}
    
    if(descripcionField) {
      const descValid = validateField(descripcionField, descripcionRegex, descripcionField.nextElementSibling?.nextElementSibling, 'descripcion', 'Máximo 500 caracteres permitidos');
      isFormValid = isFormValid && descValid;}
    
    if(desccortaField) {
      const descCortaValid = validateField(desccortaField, desccortaRegex, desccortaField.nextElementSibling?.nextElementSibling, 'desccorta', 'Máximo 30 caracteres permitidos');
      isFormValid = isFormValid && descCortaValid;}
    
    // Validar selects
    if(fondoField) {
      const fondoValid = validateSelect(fondoField, fondoField.nextElementSibling?.nextElementSibling, 'fondo');
      isFormValid = isFormValid && fondoValid;}
    
    if(juegoField) {
      const juegoValid = validateSelect(juegoField, juegoField.nextElementSibling?.nextElementSibling, 'juego');
      isFormValid = isFormValid && juegoValid;}
    
    // Habilitar/deshabilitar botón (siempre basado en la validación actual)
    if(submitBtn) {
      submitBtn.disabled = !isFormValid;
      submitBtn.style.opacity = isFormValid ? '1' : '0.5';
      submitBtn.style.cursor = isFormValid ? 'pointer' : 'not-allowed';}
    
    return isFormValid;
  }

  // Función para manejar la primera interacción
  function handleFirstInteraction(e, fieldName) {
    if(!interactedFields[fieldName]) {
      interactedFields[fieldName] = true;
      // Forzar validación visual al interactuar por primera vez
      checkFormValidity();}
  }

  // Asignar eventos de validación continua
  if(tituloField) {
    tituloField.addEventListener('input', () => {
      handleFirstInteraction(event, 'titulo');
      checkFormValidity();});
    tituloField.addEventListener('blur', () => {
      handleFirstInteraction(event, 'titulo');
      checkFormValidity();});}
  
  if(plazasField) {
    plazasField.addEventListener('input', () => {
      handleFirstInteraction(event, 'plazas');
      checkFormValidity();});
    plazasField.addEventListener('blur', () => {
      handleFirstInteraction(event, 'plazas');
      checkFormValidity();});}
  
  if(descripcionField) {
    descripcionField.addEventListener('input', () => {
      handleFirstInteraction(event, 'descripcion');
      checkFormValidity();});
    descripcionField.addEventListener('blur', () => {
      handleFirstInteraction(event, 'descripcion');
      checkFormValidity();});}
  
  if(desccortaField) {
    desccortaField.addEventListener('input', () => {
      handleFirstInteraction(event, 'desccorta');
      checkFormValidity();});
    desccortaField.addEventListener('blur', () => {
      handleFirstInteraction(event, 'desccorta');
      checkFormValidity();});}
  
  if(fondoField) {
    fondoField.addEventListener('change', () => {
      handleFirstInteraction(event, 'fondo');
      checkFormValidity();});}
  
  if(juegoField) {
    juegoField.addEventListener('change', () => {
      handleFirstInteraction(event, 'juego');
      checkFormValidity();});}

  // Validación inicial (sin mostrar errores)
  checkFormValidity();

  // Validación antes del envío del formulario
  const form = document.querySelector('.crearanuncio-form');
  if(form) {
    form.addEventListener('submit', function(e) {
      // Forzar validación de todos los campos al enviar
      Object.keys(interactedFields).forEach(key => interactedFields[key] = true);
      
      if(!checkFormValidity()) {
        e.preventDefault();
        alert('Por favor, completa correctamente todos los campos antes de enviar.');
      }
    });}
});

document.addEventListener('DOMContentLoaded', function() {
    let select = document.getElementById('fondo');
    if (select) {
        select.addEventListener('change', function() {
            let imagenSeleccionada = this.options[this.selectedIndex].getAttribute('ruta');
            document.getElementById('crearanuncio-imagen-preview').src = imagenSeleccionada;
        });
    }
});
</script>

<h2 class="crearanuncio-titulo">Crear partida</h2>

<div class="crearanuncio-form-container">
    <!-- Formulario -->
    <form action="{{ route('anuncios.store') }}" method="post" class="crearanuncio-form">
        @csrf
        <div class="crearanuncio-form-grid">
            <div class="crearanuncio-form-group">
                <label for="titulo">Título del anuncio</label>
                <small class="crearanuncio-help">Máximo 35 caracteres</small>
                <input type="text" class="crearanuncio-form-control" id="titulo" name="titulo" value="{{ old('titulo') }}">
                @error('titulo') <div class="crearanuncio-alert">{{ $message }}</div> @enderror
            </div>

            <div class="crearanuncio-form-group">
                <label for="plazas">Plazas</label>
                <small class="crearanuncio-help">Máximo 99 plazas</small>
                <input type="text" class="crearanuncio-form-control" id="plazas" name="plazas" value="{{ old('plazas') }}">
                @error('plazas') <div class="crearanuncio-alert">{{ $message }}</div> @enderror
            </div>

            <div class="crearanuncio-form-group">
            <label for="descripcion">Descripción</label>
            <small class="crearanuncio-help">Esta es la descripción que aparecerá cuando los jugadores abran el anuncio. Máximo 500 caracteres</small>
            <textarea class="crearanuncio-form-control" id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
            @error('descripcion') <div class="crearanuncio-alert">{{ $message }}</div> @enderror
            </div>

            <div class="crearanuncio-form-group">
            <label for="desccorta">Descripción corta</label>
            <small class="crearanuncio-help">La descripción inicial para llamar la atención del usuario en el buscador. Máximo 30 caracteres</small>
            <textarea class="crearanuncio-form-control" id="desccorta" name="desccorta">{{ old('desccorta') }}</textarea>
            @error('desccorta') <div class="crearanuncio-alert">{{ $message }}</div> @enderror
            </div>

            <div class="crearanuncio-form-group">
                <x-SelectFondo select-tipo="{{old('tipo')}}" />
            </div>

            <div class="crearanuncio-form-group">
                <x-SelectMedio select-tipo="{{old('tipo')}}" />
            </div>

            <div class="crearanuncio-form-group">
                <x-SelectJuego select-tipo="{{old('tipo')}}" />
            </div>
        </div>

        <button type="submit" class="crearanuncio-btn">Guardar</button>
    </form>

    <div class="crearanuncio-preview-container">
        <h3>Vista Previa del Fondo</h3>
        <img id="crearanuncio-imagen-preview" src="{{ asset('images/fondos/Default.jpg') }}" alt="Vista previa">
    </div>
</div>

@endsection