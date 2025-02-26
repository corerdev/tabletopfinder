@extends("layouts.basico")

@section('title','Registro de Usuario')

@section('contenido')

<script>
document.addEventListener('DOMContentLoaded', function() {
  const usernameRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
  const emailRegex    = /^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/;
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/;

  function validateField(field, regex, errorMsgEl) {
    const value = field.value.trim();
    if(value === '' || !regex.test(value)) {
      field.style.border = '2px solid red';
      if(errorMsgEl) {
        errorMsgEl.textContent = 'Valor inválido.';
      }
      return false;
    } else {
      field.style.border = '';
      if(errorMsgEl) {
        errorMsgEl.textContent = '';
      }
      return true;
    }
  }

  const usernameField = document.getElementById('username');
  const emailField    = document.getElementById('email');
  const passwordField = document.getElementById('password');
  const passwordRepetirField = document.getElementById('passwordRepetir');

  const usernameError = document.getElementById('username-error');
  const emailError    = document.getElementById('email-error');
  const passwordError = document.getElementById('password-error');
  const passwordRepError = document.getElementById('passwordRepetir-error');

  if(usernameField) {
    usernameField.addEventListener('input', function() {
      validateField(usernameField, usernameRegex, usernameError);
    });
  }
  
  if(emailField) {
    emailField.addEventListener('input', function() {
      validateField(emailField, emailRegex, emailError);
    });
  }
  
  if(passwordField) {
    passwordField.addEventListener('input', function() {
      validateField(passwordField, passwordRegex, passwordError);
    });
  }
  
  if(passwordRepetirField) {
    passwordRepetirField.addEventListener('input', function() {
      const valid = validateField(passwordRepetirField, passwordRegex, passwordRepError);
      if(valid && passwordRepetirField.value !== passwordField.value) {
        passwordRepetirField.style.border = '2px solid red';
        if(passwordRepError) {
          passwordRepError.textContent = 'Las contraseñas no coinciden.';
        }
      } else if(valid) {
        passwordRepetirField.style.border = '';
        if(passwordRepError) {
          passwordRepError.textContent = '';
        }
      }
    });
  }
});
</script>

<div class="register-container">
    <h2 class="register-title">Registro</h2>
    <form action="{{ route('usuarios.createUser') }}" method="POST" class="register-form">
        @csrf
        <div class="form-group">
            <label for="username"><strong>Usuario:</strong></label>
            <small class="crearanuncio-help">Máximo 15 caracteres</small>
            <input type="text" name="username" id="username" class="form-control" placeholder="Usuario" value="{{ old('username') }}">
            <small id="username-error" class="error-msg" style="color:red;"></small>
            @error('username')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="email"><strong>Email:</strong></label>
            <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            <small id="email-error" class="error-msg" style="color:red;"></small>
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password"><strong>Contraseña:</strong></label>
            <small class="crearanuncio-help">Debe contener 8 carácteres, una mayúscula, minúscula y número</small>
            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
            <small id="password-error" class="error-msg" style="color:red;"></small>
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="passwordRepetir"><strong>Repita la contraseña:</strong></label>
            <input type="password" name="passwordRepetir" id="passwordRepetir" class="form-control" placeholder="Repetir Contraseña">
            <small id="passwordRepetir-error" class="error-msg" style="color:red;"></small>
            @error('passwordRepetida')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="form-group">
            <button class="btn botonRegister" type="submit">Registrarse</button>
        </div>
    </form>
</div>
<hr>
@endsection