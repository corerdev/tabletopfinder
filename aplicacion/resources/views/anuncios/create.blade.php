@extends("layouts.basico")

@section('title','Crear Anuncio')

@section('contenido')

<script>
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