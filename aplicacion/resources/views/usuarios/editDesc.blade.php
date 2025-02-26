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

  <form action="{{ route('usuarios.updateDesc', $usuario->uuid) }}" method="POST" class="edit-form">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="descripcion">Nombre</label>
      <input type="text" name="descripcion" id="descripcion" class="form-control" value="{{ old('descripcion', $usuario->descripcion) }}" required>
    </div>
    <button type="submit" class="btn">Actualizar Usuario</button>
  </form>
</div>
@endsection