<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cellier extends Model
{
    use HasFactory;

    // Table associée
    protected $table = 'celliers';

    // On garde les timestamps Laravel (created_at / updated_at)
    public $timestamps = true;

    // Colonnes remplissables
    protected $fillable = [
        'user_id',
        'nom',
    ];

    /**
     * Le cellier appartient à un utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias en français si jamais on l’utilise dans des vues.
     */
    public function utilisateur(): BelongsTo
    {
        return $this->user();
    }

    /**
     * Le cellier contient plusieurs bouteilles.
     */
    public function bouteilles(): HasMany
    {
        return $this->hasMany(Bouteille::class, 'cellier_id');
    }
}
