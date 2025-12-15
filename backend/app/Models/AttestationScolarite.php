<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttestationScolarite extends Model
{
    use HasFactory;

    public $timestamps = false; // Désactiver created_at et updated_at

    protected $table = 'attestationscolarite';
    protected $primaryKey = 'idAS';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idDemande',
    ];

    protected $casts = [
        'idDemande' => 'integer',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'idDemande', 'idDemande');
    }
}
