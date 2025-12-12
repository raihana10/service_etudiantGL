<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concerne extends Model
{
    use HasFactory;

    protected $table = 'concerne';
    protected $primaryKey = 'idConcerne';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idContient',
        'idEtudiant',
        'annee',
        'note',
    ];

    protected $casts = [
        'note' => 'decimal:5',
        'idContient' => 'integer',
        'idEtudiant' => 'integer',
    ];

    public function contient()
    {
        return $this->belongsTo(Contient::class, 'idContient', 'idContient');
    }

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class, 'idEtudiant', 'idEtudiant');
    }
}
