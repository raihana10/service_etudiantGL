<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reclamation extends Model
{
    use HasFactory;

    protected $table = 'reclamation';
    protected $primaryKey = 'idReclamation';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idEtudiant',
        'idAdmin',
        'description',
        'statut',
        'priorite',
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

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'idEtudiant', 'idEtudiant');
    }

    public function administrateur()
    {
        return $this->belongsTo(Administrateur::class, 'idAdmin', 'idAdmin');
    }
}
