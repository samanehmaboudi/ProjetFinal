<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cellier extends Model
{
    use HasFactory;

    // La table est correcte
    protected $table = 'celliers';

<<<<<<< HEAD
    // Désactive les colonnes 'created_at' et 'updated_at' (selon le schéma bd_vino.pdf)
    public $timestamps = false; 
=======
>>>>>>> cbb3bb4cc9bd0236d599492308c0b9a0c61ec03c

    // ✔ Garde les timestamps créés par Laravel
    public $timestamps = true;

    // ✔ Colonnes que tu peux remplir
    protected $fillable = [
        'nom',
<<<<<<< HEAD
        'id_utilisateur',
=======
        'user_id',
>>>>>>> cbb3bb4cc9bd0236d599492308c0b9a0c61ec03c
    ];

    /**
     * Le cellier appartient à un utilisateur.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Le cellier contient plusieurs bouteilles.
     */
    public function bouteilles(): HasMany
    {
        return $this->hasMany(BouteilleCellier::class, 'id_cellier');
    }
}
