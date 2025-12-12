<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $table = 'demande';
    protected $primaryKey = 'idDemande';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idEtudiant',
        'idAdmin',
        'datesoumission',
        'typeDoc',
        'statut',
        'motif_refus',
        'date_traitement',
    ];

    protected $casts = [
        'datesoumission' => 'datetime',
        'date_traitement' => 'datetime',
        'idEtudiant' => 'integer',
        'idAdmin' => 'integer',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'idEtudiant', 'idEtudiant');
    }

    public function administrateur()
    {
        return $this->belongsTo(Administrateur::class, 'idAdmin', 'idAdmin');
    }

    public function attestationscolarite()
    {
        return $this->hasOne(AttestationScolarite::class, 'idDemande', 'idDemande');
    }

    public function attestationreussite()
    {
        return $this->hasOne(AttestationReussite::class, 'idDemande', 'idDemande');
    }

    public function conventionstage()
    {
        return $this->hasOne(ConventionStage::class, 'idDemande', 'idDemande');
    }

    public function relevenote()
    {
        return $this->hasOne(ReleveNote::class, 'idDemande', 'idDemande');
    }
}
