<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConventionStage extends Model
{
    use HasFactory;

    public $timestamps = false; // Désactiver created_at et updated_at

    protected $table = 'conventionstage';
    protected $primaryKey = 'idCS';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idDemande',
        'representantEntreprise',
        'villeEntreprise',
        'encadrantAcademique',
        'encadrantEntreprise',
        'fctEncadrant',
        'TLEncadrant',
        'emailEncadrant',
        'emailEntreprise',
        'TLEntreprise',
        'fctRepresentant',
        'raisonSocialeEntreprise',
        'secteurEntreprise',
        'adresseEntreprise',
        'typeStage',
        'dateDebut',
        'dateFin',
        'sujetStage',
    ];

    protected $casts = [
        'idDemande' => 'integer',
        'dateDebut' => 'date',
        'dateFin' => 'date',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'idDemande', 'idDemande');
    }
}
