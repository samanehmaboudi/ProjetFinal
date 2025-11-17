<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouteilleCatalogue extends Model
{
    use HasFactory;

    protected $table = 'bouteille_catalogue';

    protected $fillable = [
        'code_saQ',
        'nom',
        'id_type_vin',
        'id_pays',
        'region',
        'millesime',
        'prix',
        'url_image',
        'volume',
        'date_import',
    ];

    protected function casts(): array
    {
        return [
            'prix' => 'decimal:2',
            'millesime' => 'integer',
            'date_import' => 'datetime',
        ];
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'id_pays');
    }

    public function typeVin()
    {
        return $this->belongsTo(TypeVin::class, 'id_type_vin');
    }
}

