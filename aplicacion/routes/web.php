<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FondosController;
use App\Http\Controllers\AnunciosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\TipoLavadoController;
use App\Http\Controllers\JuegosController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\NotificacionesController;

// Sección de administrador

Route::get('/usuarios/admin', [UsuariosController::class, 'admin'])->name('usuarios.admin');
Route::post('/usuarios/makeTienda', [UsuariosController::class, 'makeTienda'])->name('usuarios.makeTienda');
Route::post('/usuarios/banHammer', [UsuariosController::class, 'banHammer'])->name('usuarios.banHammer');


// Sección de anuncios

Route::get('/',[AnunciosController::class, 'landing'])->name('anuncios.landing');
Route::get('/anuncios/index',[AnunciosController::class, 'index'])->name('anuncios.index');
Route::get('/anuncios/landing',[AnunciosController::class, 'landing'])->name('anuncios.landing');
Route::get('/anuncios/create',[AnunciosController::class, 'create'])->name('anuncios.create');
Route::post('/anuncios/store',[AnunciosController::class, 'store'])->name('anuncios.store');
Route::post('/anuncios/getListado',[AnunciosController::class, 'getListado'])->name('anuncios.getListado');
Route::get('/anuncios/buscarPartidas', [AnunciosController::class, 'buscarPartidas'])->name('anuncios.buscarPartidas');
Route::get('/anuncios/{id}/', [AnunciosController::class, 'show'])->name('anuncios.show');
Route::delete('/anuncios/{uuid}', [AnunciosController::class, 'destroy'])->name('anuncios.destroy');

// Sección usuarios

Route::get('/usuarios/create',[UsuariosController::class, 'create'])->name('usuarios.create');
Route::get('/usuarios/logout',[UsuariosController::class, 'logout'])->name('usuarios.logout');
Route::get('/usuarios/miPerfil',[UsuariosController::class, 'miPerfil'])->name('usuarios.miPerfil');
Route::get('/usuarios/login',[UsuariosController::class, 'login'])->name('usuarios.login');
Route::post('/usuarios/authenticate',[UsuariosController::class, 'authenticate'])->name('usuarios.authenticate');
Route::post('/usuarios/createUser',[UsuariosController::class, 'createUser'])->name('usuarios.createUser');
Route::get('/usuarios/{nombre}', [UsuariosController::class, 'show'])->name('usuarios.show');

// Edición de campos en perfil

Route::put('/usuarios/{id}/updateEmail', [UsuariosController::class, 'updateEmail'])->name('usuarios.updateEmail');
Route::get('/usuarios/{id}/editDesc', [UsuariosController::class, 'editDesc'])->name('usuarios.editDesc');
Route::put('/usuarios/{id}/updateDesc', [UsuariosController::class, 'updateDesc'])->name('usuarios.updateDesc');
Route::get('/usuarios/{id}/editEmail', [UsuariosController::class, 'editEmail'])->name('usuarios.editEmail');
Route::put('/usuarios/{id}/updateUser', [UsuariosController::class, 'updateUser'])->name('usuarios.updateUser');
Route::get('/usuarios/{id}/editUser', [UsuariosController::class, 'editUser'])->name('usuarios.editUser');
Route::put('/usuarios/{id}/updateAvatar', [UsuariosController::class, 'updateAvatar'])->name('usuarios.updateAvatar');
Route::get('/usuarios/{id}/editAvatar', [UsuariosController::class, 'editAvatar'])->name('usuarios.editAvatar');
Route::get('/usuarios/{id}/editTienda', [UsuariosController::class, 'editTienda'])->name('usuarios.editTienda');
Route::put('/usuarios/{id}/updateTienda', [UsuariosController::class, 'updateTienda'])->name('usuarios.updateTienda');

// Sección fondos

Route::get('/fondos/create', [FondosController::class, 'create'])->name('fondos.create');
Route::post('/fondos/upload', [FondosController::class, 'upload'])->name('fondos.upload');

// Sección juegos

Route::get('/juegos/create',[JuegosController::class, 'create'])->name('juegos.create');
Route::get('/juegos/listado',[JuegosController::class, 'listado'])->name('juegos.listado');
Route::get('/juegos/{nombre}', [JuegosController::class, 'show'])->name('juegos.show');
Route::post('/juegos/upload', [JuegosController::class, 'upload'])->name('juegos.upload');

// Sección notificaciones

Route::get('/notificaciones', [NotificacionesController::class, 'notificaciones'])->name('notificaciones.notificaciones');
Route::post('/notificaciones/store', [NotificacionesController::class, 'store'])->name('notificaciones.store');
Route::post('/notificaciones/abandonar', [NotificacionesController::class, 'abandonar'])->name('notificaciones.abandonar');
Route::post('/notificaciones/juego/delete', [NotificacionesController::class, 'deleteUserFromGame'])->name('notificaciones.deleteUserFromGame');
Route::post('/notificaciones/{id}/accept', [NotificacionesController::class, 'accept'])->name('notificaciones.accept');
Route::post('/notificaciones/{id}/reject', [NotificacionesController::class, 'reject'])->name('notificaciones.reject');
Route::post('/notificaciones/{id}/delete', [NotificacionesController::class, 'delete'])->name('notificaciones.delete');
Route::post('/notificaciones/enviar', [NotificacionesController::class, 'enviar'])->name('notificaciones.enviar');
Route::get('/notificaciones/contarNotificaciones', [NotificacionesController::class, 'contarNotificaciones'])->name('notificaciones.contarNotificaciones');
Route::get('/notificaciones/{contactoUuid}', [NotificacionesController::class, 'mostrarChat'])->name('notificaciones.mostrarChat');



// Sección de login con Google

Route::get('/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
