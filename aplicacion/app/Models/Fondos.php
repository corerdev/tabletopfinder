<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fondos extends Model
{

    protected $table = "fondos";
    
    protected $primaryKey = 'code';

    protected $fillable = ['nombre', 'ruta'];

    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false;
}
