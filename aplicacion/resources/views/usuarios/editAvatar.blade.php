@extends('layouts.basico')

@section('title', 'Selecciona tu Avatar')

@section('contenido')
<div class="avatar-grid-container">
    <h1 class="avatar-title">Selecciona tu Avatar</h1>
    <div class="avatar-grid">
        @foreach($avatares as $avatar)
            <div class="avatar-item">
                <img src="{{ asset($avatar->ruta) }}" alt="{{ $avatar->nombre }}" class="avatar-img">
                <form action="{{ route('usuarios.updateAvatar', $usuario->uuid) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button type="submit" name="avatar" value="{{ $avatar->nombre }}" class="avatar-btn">Seleccionar</button>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
