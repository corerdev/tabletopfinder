@extends('layouts.basico')

@section('title', 'Registrar Juego')

@section('contenido')
<div class="admin-container">
  <h2 class="admin-title">Registrar Juego</h2>
  
  @if($errors->any())
    <div class="alert alert-error">
      <ul>
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  
  @if(session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif
  
  <form action="{{ route('juegos.upload') }}" method="POST" enctype="multipart/form-data" class="form">
    @csrf
    
    <div class="form-group">
      <label for="nombre">Nombre del Juego:</label>
      <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ingrese el nombre del juego" required>
    </div>
    <div class="form-group">
      <label for="descripcion">Descripcion del Juego:</label>
      <input type="text" name="descripcion" id="descripcion" class="form-control" placeholder="Ingrese la descripcion del juego" required>
    </div>
    
    <div class="form-group">
      <label for="tipo">Tipo de Juego:</label>
      <select name="tipo" id="tipo" class="form-control" required>
        <option value="">Seleccione un tipo</option>
        <option value="coop">Coop</option>
        <option value="versus">Versus</option>
        <option value="versus/coop">Versus / Coop</option>
      </select>
    </div>
    
    <div class="form-group">
      <label for="imagen">Subir Imagen:</label>
      <input type="file" name="imagen" id="imagen" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
    </div>
    
    <button type="submit" class="admin-btn">Registrar Juego</button>
  </form>
</div>
@endsection
