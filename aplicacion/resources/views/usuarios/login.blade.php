@extends("layouts.basico")

@section('title','Login')

@section('contenido')
<div class="login-container">
    <h2 class="login-title">Login</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('usuarios.authenticate') }}" method="POST" class="login-form">
        @csrf
        <div class="form-group">
            <label for="username"><strong>Usuario:</strong></label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Usuario" value="{{ old('username') }}">
            @error('username')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="password"><strong>Contraseña:</strong></label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña">
            @error('password')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <button class="btn botonLogin" type="submit">Login</button>
        </div>
    </form>

    <div class="login-options">
        <a id="nuevoUser" href="{{ route('usuarios.create') }}" class="btn btn-register">¿Nuevo usuario?</a>
    </div>
</div>
<hr>
@endsection
