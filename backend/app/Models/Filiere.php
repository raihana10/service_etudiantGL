<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    protected $table = 'filiere';
    protected $primaryKey = 'idF';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nomF',
        'niveau',
    ];

    public function etudiants()
    {
        return $this->hasMany(Etudiant::class, 'idF', 'idF');
    }

    public function contients()
    {
        return $this->hasMany(Contient::class, 'idF', 'idF');
    }
}
