<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttestationReussite extends Model
{
    use HasFactory;

    protected $table = 'attestationreussite';
    protected $primaryKey = 'idAR';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idDemande',
        'anneeObtention',
        'diplomeConcernee',
    ];

    protected $casts = [
        'idDemande' => 'integer',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'idDemande', 'idDemande');
    }
}
