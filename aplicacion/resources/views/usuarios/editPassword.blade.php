@extends('layouts.basico')

@section('title', 'Cambiar Contraseña')

@section('contenido')
<div class="edit-container">
  <h1 class="edit-title">Cambiar Contraseña</h1>

  @if (session('error'))
    <div class="alert alert-danger">
      {{ session('error') }}
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('usuarios.updatePassword', $usuario->uuid) }}" method="POST" class="edit-form">
    @csrf
    @method('PUT')
    
    <div class="form-group">
      <label for="viejaPassword">Contraseña Actual</label>
      <input type="password" name="viejaPassword" id="viejaPassword" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="password">Nueva Contraseña</label>
      <small class="crearanuncio-help">
        Mínimo 8 caracteres, debe contener al menos una mayúscula, una minúscula y un número.
      </small>
      <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="password_confirmation">Confirmar Nueva Contraseña</label>
      <input type="password" name="passwordRepetir" id="passwordRepetir" class="form-control" required>
    </div>

    <button type="submit" class="btn">Actualizar Contraseña</button>
  </form>
</div>
@endsection