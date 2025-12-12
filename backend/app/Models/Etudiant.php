<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    use HasFactory;

    protected $table = 'etudiant';
    protected $primaryKey = 'idEtudiant';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nom',
        'prenom',
        'idF',
        'niveau',
        'CIN',
        'numApogee',
        'emailInstitu',
        'dateNaissance',
        'lieuNaissance',
    ];

    protected $casts = [
        'dateNaissance' => 'date',
        'idF' => 'integer',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'idF', 'idF');
    }

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'idEtudiant', 'idEtudiant');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'idEtudiant', 'idEtudiant');
    }
}
