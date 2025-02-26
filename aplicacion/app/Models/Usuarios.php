<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuarios extends Authenticatable
{
    use HasUuids;

    protected $table = "usuarios";
    protected $primaryKey = 'uuid';
    
    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = ['username', 'password', 'google_id', 'email', 'descripcion', 'avatar', 'isAdmin', 'ultimaUpdate', 'esTienda', 'descTienda', 'telfTienda', 'dirTienda', 'emailTienda'];

    public $timestamps = false; 

    protected $hidden = ['pass'];    

    public function anuncio(): HasMany
    {
        return $this->hasMany(Anuncios::class, 'usuarios', 'uuid');
    }

    public function avisos(): HasMany
    {
        return $this->hasMany(Avisos::class, 'usuarios', 'uuid');
    }

}
