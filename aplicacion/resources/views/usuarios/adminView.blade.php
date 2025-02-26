@extends('layouts.basico')

@section('title', 'Convertir Usuario en Tienda')

@section('contenido')
<div class="admin-container">
  <h2 class="admin-title">Convertir Usuario en Tienda</h2>

  @if(session('success'))
      <div class="admin-alert admin-alert-success">
          {{ session('success') }}
      </div>
  @endif

  @if($errors->any())
      <div class="admin-alert admin-alert-error">
          <ul>
              @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
  @endif

  <form action="{{ route('usuarios.makeTienda') }}" method="POST" class="admin-form">
      @csrf
      <div class="form-group">
          <label for="user_id">Selecciona usuario:</label>
          <select name="user_id" id="user_id" class="admin-select" required>
              <option value="">-- Selecciona un usuario --</option>
              @foreach($usuariosRegulares as $user)
                  <option value="{{ $user->uuid }}">{{ $user->username }}</option>
              @endforeach
          </select>
      </div>
      <button type="submit" class="admin-btn">Hacer tienda</button>
  </form>

  <form action="{{ route('usuarios.banHammer') }}" method="POST" class="admin-form">
      @csrf
      <div class="form-group">
          <label for="user_id_ban">Selecciona usuario:</label>
          <select name="user_id" id="user_id_ban" class="admin-select" required>
              <option value="">-- Selecciona un usuario --</option>
              @foreach($usuarios as $user)
                  <option value="{{ $user->uuid }}">{{ $user->username }}</option>
              @endforeach
          </select>
      </div>
      <button type="submit" class="admin-btn admin-btn-danger">Banhammer</button>
  </form>
</div>
@endsection
