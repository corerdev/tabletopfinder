<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Juegos extends Model
{

    protected $table = "juegos";
    
    protected $primaryKey = 'code';

    protected $fillable = ['tipo', 'nombre', 'descripcion'];

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;

    public function anuncio(): HasMany
    {
        return $this->hasMany(Anuncios::class, 'juegos', 'code');
    }
}
