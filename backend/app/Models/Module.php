<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $table = 'module';
    protected $primaryKey = 'idM';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nomM',
        'code',
    ];

    public function contients()
    {
        return $this->hasMany(Contient::class, 'idM', 'idM');
    }
}
