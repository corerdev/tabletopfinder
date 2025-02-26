@extends('layouts.basico')

@section('title', 'Editar Datos de Tienda')

@section('contenido')
<div class="edit-container">
  <h1 class="edit-title">Editar Datos de la Tienda</h1>

  @if ($errors->any())
    <div class="alert">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('usuarios.updateTienda', $usuario->uuid) }}" method="POST" class="edit-form">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="descTienda">Descripción de la Tienda</label>
      <textarea name="descTienda" id="descTienda" rows="5" class="form-control" required>{{ old('descTienda', $usuario->descTienda) }}</textarea>
    </div>
    <div class="form-group">
      <label for="telfTienda">Teléfono de la Tienda</label>
      <input type="text" name="telfTienda" id="telfTienda" class="form-control" value="{{ old('telfTienda', $usuario->telfTienda) }}" required>
    </div>
    <div class="form-group">
      <label for="dirTienda">Dirección de la Tienda</label>
      <input type="text" name="dirTienda" id="dirTienda" class="form-control" value="{{ old('dirTienda', $usuario->dirTienda) }}" required>
    </div>
    <div class="form-group">
      <label for="emailTienda">Email de la Tienda</label>
      <input type="email" name="emailTienda" id="emailTienda" class="form-control" value="{{ old('emailTienda', $usuario->emailTienda) }}" required>
    </div>
    <button type="submit" class="btn">Actualizar Tienda</button>
  </form>
</div>
@endsection