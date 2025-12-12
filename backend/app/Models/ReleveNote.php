<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleveNote extends Model
{
    use HasFactory;

    protected $table = 'relevenote';
    protected $primaryKey = 'idRN';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idDemande',
        'annee',
        'semestre',
    ];

    protected $casts = [
        'idDemande' => 'integer',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'idDemande', 'idDemande');
    }
}
