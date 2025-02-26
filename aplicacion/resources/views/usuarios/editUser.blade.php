@extends('layouts.basico')

@section('title', 'Editar Usuario')

@section('contenido')
<div class="edit-container">
  <h1 class="edit-title">Editar Usuario</h1>

  @if ($errors->any())
    <div class="alert">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('usuarios.updateUser', $usuario->uuid) }}" method="POST" class="edit-form">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="username">Nombre</label>
      <input type="text" name="username" id="username" class="form-control" value="{{ old('username', $usuario->username) }}" required>
    </div>
    <button type="submit" class="btn">Actualizar Usuario</button>
  </form>
</div>
@endsection