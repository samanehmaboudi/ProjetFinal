<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeVin extends Model
{
    use HasFactory;

    protected $table = 'type_vin';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'date_creation',
    ];

    protected function casts(): array
    {
        return [
            'date_creation' => 'datetime',
        ];
    }

    public function bouteillesCatalogue()
    {
        return $this->hasMany(BouteilleCatalogue::class, 'id_type_vin');
    }
}

