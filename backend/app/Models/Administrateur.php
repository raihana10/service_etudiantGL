<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{
    use HasFactory;

    protected $table = 'administrateur';
    protected $primaryKey = 'idAdmin';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'email',
        'motDePasse',
        'created_at',
    ];

    protected $hidden = [
        'motDePasse',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function demandes()
    {
        return $this->hasMany(Demande::class, 'idAdmin', 'idAdmin');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'idAdmin', 'idAdmin');
    }
}
