<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlazasJuegos extends Model
{

    use HasUuids;

    protected $table = "plazasjuegos";
    
    protected $primaryKey = 'uuidpeticion';

    protected $fillable = ['uuidanuncio', 'uuiduser'];

    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = true;

    public function usuarios(): BelongsTo
    {
        return $this->belongsTo(Usuarios::class, 'usuarios', 'uuid');
    }
}
