@extends("layouts.basico")

@section('title','Crear Anuncio')

@section('contenido')

<style>
    
    .crearanuncio-error-message {
        color: #ff4444;
        font-size: 0.8rem;
        margin-top: 5px;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .crearanuncio-form-control:invalid {
        border-color: #ff4444 !important;
    }

    select:invalid {
        border-color: #ff4444 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
   
    const tituloRegex = /^[a-zA-Z0-9 áéíóúÁÉÍÓÚñÑüÜ\s]{1,35}$/;
    const plazasRegex = /^[1-9][0-9]?$|^99$/;
    const descripcionRegex = /^[a-zA-Z0-9 áéíóúÁÉÍÓÚñÑüÜ,.\s]{1,500}$/;
    const desccortaRegex = /^[a-zA-Z0-9 áéíóúÁÉÍÓÚñÑüÜ,.\s]{1,30}$/;

    const errorMessages = {
        titulo: {
            empty: 'El título no puede estar vacío',
            invalid: 'Máximo 35 caracteres permitidos, sin carácteres especiales'
        },
        plazas: {
            empty: 'Debes indicar el número de plazas',
            invalid: 'Solo números entre 1 y 99'
        },
        descripcion: {
            empty: 'La descripción no puede estar vacía',
            invalid: 'Máximo 500 caracteres permitidos, sin carácteres especiales (excepto punto y coma)'
        },
        desccorta: {
            empty: 'La descripción corta no puede estar vacía',
            invalid: 'Máximo 30 caracteres permitidos, sin carácteres especiales (excepto punto y coma)'
        },
        fondo: {
            empty: 'Debes seleccionar un fondo'
        },
        juego: {
            empty: 'Debes seleccionar un juego'
        }
    };

    const tituloField = document.getElementById('titulo');
    const plazasField = document.getElementById('plazas');
    const descripcionField = document.getElementById('descripcion');
    const desccortaField = document.getElementById('desccorta');
    const fondoField = document.getElementById('fondo');
    const juegoField = document.querySelector('select[name="juegocode"]');
    const submitBtn = document.querySelector('.crearanuncio-btn');
    const form = document.querySelector('.crearanuncio-form');

    const interactedFields = {
        titulo: false, 
        plazas: false, 
        descripcion: false, 
        desccorta: false, 
        fondo: false, 
        juego: false
    };

    function showError(field, message) {
        field.style.border = '2px solid #ff4444';
        const errorElement = document.createElement('div');
        errorElement.className = 'crearanuncio-error-message';
        errorElement.textContent = message;
        
        const existingError = field.nextElementSibling;
        if (existingError && existingError.classList.contains('crearanuncio-error-message')) {
            existingError.remove();
        }
        
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }

    function clearError(field) {
        field.style.border = '';
        const errorElement = field.nextElementSibling;
        if (errorElement && errorElement.classList.contains('crearanuncio-error-message')) {
            errorElement.remove();
        }
    }

    function validateField(field, regex, fieldName) {
        const value = field.value.trim();
        
        if (!interactedFields[fieldName]) return true;
        
        if (value === '') {
            showError(field, errorMessages[fieldName].empty);
            return false;
        }
        
        if (!regex.test(value)) {
            showError(field, errorMessages[fieldName].invalid);
            return false;
        }
        
        clearError(field);
        return true;
    }

    function validateSelect(selectField, fieldName) {
        if (!interactedFields[fieldName]) return true;
        
        if (selectField.value === '') {
            showError(selectField, errorMessages[fieldName].empty);
            return false;
        }
        
        clearError(selectField);
        return true;
    }

    function checkFormValidity() {
        let isFormValid = true;
        
        if (tituloField) {
            isFormValid = validateField(tituloField, tituloRegex, 'titulo') && isFormValid;
        }
        
        if (plazasField) {
            isFormValid = validateField(plazasField, plazasRegex, 'plazas') && isFormValid;
        }
        
        if (descripcionField) {
            isFormValid = validateField(descripcionField, descripcionRegex, 'descripcion') && isFormValid;
        }
        
        if (desccortaField) {
            isFormValid = validateField(desccortaField, desccortaRegex, 'desccorta') && isFormValid;
        }
        
        if (fondoField) {
            isFormValid = validateSelect(fondoField, 'fondo') && isFormValid;
        }
        
        if (juegoField) {
            isFormValid = validateSelect(juegoField, 'juego') && isFormValid;
        }
        
        if (submitBtn) {
            submitBtn.disabled = !isFormValid;
            submitBtn.style.opacity = isFormValid ? '1' : '0.5';
            submitBtn.style.cursor = isFormValid ? 'pointer' : 'not-allowed';
        }
        
        return isFormValid;
    }

    function handleFirstInteraction(e, fieldName) {
        if (!interactedFields[fieldName]) {
            interactedFields[fieldName] = true;
            checkFormValidity();
        }
    }

    if (tituloField) {
        tituloField.addEventListener('input', () => handleFirstInteraction(event, 'titulo'));
        tituloField.addEventListener('blur', () => checkFormValidity());
    }
    
    if (plazasField) {
        plazasField.addEventListener('input', () => handleFirstInteraction(event, 'plazas'));
        plazasField.addEventListener('blur', () => checkFormValidity());
    }
    
    if (descripcionField) {
        descripcionField.addEventListener('input', () => handleFirstInteraction(event, 'descripcion'));
        descripcionField.addEventListener('blur', () => checkFormValidity());
    }
    
    if (desccortaField) {
        desccortaField.addEventListener('input', () => handleFirstInteraction(event, 'desccorta'));
        desccortaField.addEventListener('blur', () => checkFormValidity());
    }
    
    if (fondoField) {
        fondoField.addEventListener('change', () => {
            handleFirstInteraction(event, 'fondo');
            checkFormValidity();
        });
    }
    
    if (juegoField) {
        juegoField.addEventListener('change', () => {
            handleFirstInteraction(event, 'juego');
            checkFormValidity();
        });
    }

    if (fondoField) {
        fondoField.addEventListener('change', function() {
            const imagenSeleccionada = this.options[this.selectedIndex].getAttribute('ruta');
            const preview = document.getElementById('crearanuncio-imagen-preview');
            if (preview && imagenSeleccionada) {
                preview.src = imagenSeleccionada;
            }
        });
    }

    checkFormValidity();

    if (form) {
        form.addEventListener('submit', function(e) {
            Object.keys(interactedFields).forEach(key => interactedFields[key] = true);
            
            if (!checkFormValidity()) {
                e.preventDefault();
                alert('Por favor, completa correctamente todos los campos antes de enviar.');
            }
        });
    }
});
</script>

<h2 class="crearanuncio-titulo">Crear partida</h2>

<div class="crearanuncio-form-container">
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
                <small class="crearanuncio-help">Máximo 500 caracteres</small>
                <textarea class="crearanuncio-form-control" id="descripcion" name="descripcion">{{ old('descripcion') }}</textarea>
                @error('descripcion') <div class="crearanuncio-alert">{{ $message }}</div> @enderror
            </div>

            <div class="crearanuncio-form-group">
                <label for="desccorta">Descripción corta</label>
                <small class="crearanuncio-help">Máximo 30 caracteres</small>
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