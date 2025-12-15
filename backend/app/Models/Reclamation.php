<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    public $timestamps = false; // Désactiver created_at et updated_at

    protected $table = 'reclamation';
    protected $primaryKey = 'idReclamation';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idEtudiant',
        'idAdmin',
        'description',
        'statut',
        'datesoumission',
        'dateReponse',
        'reponse',
        'sujet',
    ];

    protected $casts = [
        'datesoumission' => 'datetime',
        'dateReponse' => 'datetime',
        'idEtudiant' => 'integer',
        'idAdmin' => 'integer',
    ];

    /**
     * Relation avec l'étudiant
     */
    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'idEtudiant', 'idEtudiant');
    }

    /**
     * Relation avec l'administrateur
     */
    public function administrateur()
    {
        return $this->belongsTo(Administrateur::class, 'idAdmin', 'idAdmin');
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    /**
     * Scope pour les réclamations nouvelles
     */
    public function scopeNouvelles($query)
    {
        return $query->where('statut', 'Nouvelle');
    }

    /**
     * Scope pour les réclamations en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'En cours');
    }

    /**
     * Scope pour les réclamations résolues
     */
    public function scopeResolues($query)
    {
        return $query->where('statut', 'Résolue');
    }
}