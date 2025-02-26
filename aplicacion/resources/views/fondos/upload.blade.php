@extends('layouts.basico')

@section('title', 'Subir Imagen')

@section('contenido')
<div class="admin-container">
  <h2 class="admin-title">Subir Imagen</h2>

  @if($errors->any())
    <div class="admin-alert admin-alert-error">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(session('success'))
    <div class="admin-alert admin-alert-success">
      {{ session('success') }}
    </div>
  @endif

  <form action="{{ route('fondos.upload') }}" method="POST" enctype="multipart/form-data" class="admin-form">
    @csrf
    <div class="form-group">
      <label for="tipo">Tipo de imagen:</label>
      <select name="tipo" id="tipo" class="admin-select" required>
        <option value="">Seleccione un tipo</option>
        <option value="avatar">Avatar</option>
        <option value="fondo">Fondo</option>
      </select>
    </div>
    <div class="form-group">
      <label for="nombre">Nombre de la imagen (solo letras y números):</label>
      <input type="text" name="nombre" id="nombre" class="admin-input" required>
    </div>
    <div class="form-group">
      <label for="imagen">Selecciona la imagen (jpg o webp):</label>
      <input type="file" name="imagen" id="imagen" class="admin-input" accept=".jpg,.jpeg,.webp" required>
    </div>
    <button type="submit" class="admin-btn">Subir Imagen</button>
  </form>
</div>
@endsection
