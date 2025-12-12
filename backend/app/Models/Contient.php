<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contient extends Model
{
    use HasFactory;

    protected $table = 'contient';
    protected $primaryKey = 'idContient';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idF',
        'idM',
    ];

    protected $casts = [
        'idF' => 'integer',
        'idM' => 'integer',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class, 'idF', 'idF');
    }

    public function module()
    {
        return $this->belongsTo(Module::class, 'idM', 'idM');
    }
}
