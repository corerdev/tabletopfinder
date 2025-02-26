<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Anuncios extends Model
{
    use HasUuids;

    protected $table = "anuncio";
    protected $primaryKey = 'uuid';

    public $incrementing = false; 
    protected $keyType = 'string';

    protected $fillable = ['useruuid', 'titulo', 'fondo', 'juegocode', 'plazas', 'plazasocupadas','descripcion', 'desccorta', 'medio'];
    
    public $timestamps = false; 

    public function usuarios(): BelongsTo
    {
        return $this->belongsTo(Usuarios::class, 'usuarios', 'uuid');
    }

    public function juegos(): BelongsTo
    {
        return $this->belongsTo(Juegos::class, 'juegos', 'code');
    }
}
