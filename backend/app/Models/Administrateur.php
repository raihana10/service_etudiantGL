<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Administrateur extends Model
{
    use HasFactory;

    protected $table = 'administrateur';
    protected $primaryKey = 'idAdmin';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'email',
        'motDePasse',
    ];

    protected $hidden = [
        'motDePasse',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function demandes()
    {
        return $this->hasMany(Demande::class, 'idAdmin', 'idAdmin');
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class, 'idAdmin', 'idAdmin');
    }

    /**
     * Mutateur pour hasher le mot de passe automatiquement
     * Ne s'applique que lors de la création/modification via Eloquent
     */
    public function setMotDePasseAttribute($value)
    {
        // Ne hasher que si ce n'est pas déjà un hash
        if (strlen($value) !== 60 || !str_starts_with($value, '$2y$')) {
            $this->attributes['motDePasse'] = Hash::make($value);
        } else {
            $this->attributes['motDePasse'] = $value;
        }
    }
}