<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificaciones extends Model
{

    use HasUuids;

    protected $table = "notificaciones";
    
    protected $primaryKey = 'id';

    protected $fillable = ['notificado','solicitante','anuncio','tipo','hora', 'texto'];

    public $incrementing = true;
    protected $keyType = 'string';

    public $timestamps = false;

    public function notificado(): BelongsTo
    {
        return $this->belongsTo(Usuarios::class, 'notificado', 'uuid');
    }

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(Usuarios::class, 'solicitante', 'uuid');
    }

    public function anuncio(): BelongsTo
    {
        return $this->belongsTo(Anuncios::class, 'anuncio', 'uuid');
    }
}
