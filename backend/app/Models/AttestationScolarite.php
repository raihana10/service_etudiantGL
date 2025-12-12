<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttestationScolarite extends Model
{
    use HasFactory;

    protected $table = 'attestationscolarite';
    protected $primaryKey = 'idAS';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idDemande',
        'nbrExemplaire',
    ];

    protected $casts = [
        'idDemande' => 'integer',
        'nbrExemplaire' => 'integer',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'idDemande', 'idDemande');
    }
}
