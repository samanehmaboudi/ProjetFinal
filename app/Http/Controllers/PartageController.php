<?php

namespace App\Http\Controllers;

use App\Models\Bouteille;
use App\Models\Partage;
use App\Models\BouteilleCatalogue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PartageController extends Controller
{
    /**
     * Génère un lien partageable unique pour une bouteille.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bouteille    $bouteille
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Bouteille $bouteille): JsonResponse
    {
        // Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour partager une bouteille.',
            ], 401);
        }

        // Vérifier que la bouteille appartient à l'utilisateur connecté
        $user = Auth::user();
        if ($bouteille->cellier->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez partager que vos propres bouteilles.',
            ], 403);
        }

        // Vérifier si un partage actif existe déjà pour cette bouteille
        $existingPartage = Partage::where('bouteille_id', $bouteille->id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where('created_at', '>', now()->subDays(30)) // Partages récents (30 jours)
            ->first();

        if ($existingPartage && !$existingPartage->isExpired()) {
            // Retourner le lien existant
            $shareUrl = route('partage.show', $existingPartage->token_unique);
            
            return response()->json([
                'success' => true,
                'message' => 'Lien de partage récupéré avec succès.',
                'url' => $shareUrl,
                'token' => $existingPartage->token_unique,
            ]);
        }

        // Générer un nouveau token unique
        $token = Partage::generateToken();
        
        // S'assurer que le token est unique
        while (Partage::where('token_unique', $token)->exists()) {
            $token = Partage::generateToken();
        }

        // Créer le partage (sans expiration par défaut, ou avec expiration optionnelle)
        $expiresAt = $request->input('expires_at') ? now()->addDays($request->input('expires_at')) : null;

        $partage = Partage::create([
            'bouteille_id' => $bouteille->id,
            'token_unique' => $token,
            'expires_at' => $expiresAt,
        ]);

        $shareUrl = route('partage.show', $partage->token_unique);

        return response()->json([
            'success' => true,
            'message' => 'Lien de partage généré avec succès.',
            'url' => $shareUrl,
            'token' => $partage->token_unique,
        ]);
    }

    /**
     * Affiche la vue publique d'une bouteille partagée.
     *
     * @param  string  $token
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(string $token): View
    {
        // Trouver le partage par token
        $partage = Partage::where('token_unique', $token)->first();

        if (!$partage) {
            abort(404, 'Lien de partage introuvable.');
        }

        // Vérifier si le partage est expiré
        if ($partage->isExpired()) {
            abort(410, 'Ce lien de partage a expiré.');
        }

        // Charger la bouteille avec ses relations
        $bouteille = $partage->bouteille;
        
        // Récupérer les informations du catalogue si disponible
        $bouteilleCatalogue = null;
        if ($bouteille->code_saq) {
            $bouteilleCatalogue = BouteilleCatalogue::where('code_saQ', $bouteille->code_saq)
                ->with(['typeVin', 'pays', 'region'])
                ->first();
        } else {
            // Essayer de trouver par nom
            $bouteilleCatalogue = BouteilleCatalogue::where('nom', $bouteille->nom)
                ->with(['typeVin', 'pays', 'region'])
                ->first();
        }

        // Préparer les données publiques (incluant les notes de dégustation)
        $donnees = [
            'nom' => $bouteille->nom,
            'pays' => $bouteille->pays,
            'prix' => $bouteille->prix,
            'format' => $bouteille->format,
            'type' => null,
            'millesime' => null,
            'image' => null,
            'region' => null,
            'url_saq' => null,
            'note_degustation' => $bouteille->note_degustation,
            'rating' => $bouteille->rating,
        ];

        // Enrichir avec les données du catalogue si disponible
        if ($bouteilleCatalogue) {
            $donnees['type'] = $bouteilleCatalogue->typeVin ? $bouteilleCatalogue->typeVin->nom : null;
            $donnees['millesime'] = $bouteilleCatalogue->millesime;
            $donnees['image'] = $bouteilleCatalogue->image;
            $donnees['url_saq'] = $bouteilleCatalogue->url_saq;

            if (!$donnees['pays'] && $bouteilleCatalogue->pays) {
                $donnees['pays'] = $bouteilleCatalogue->pays->nom;
            }

            if ($bouteilleCatalogue->region) {
                $donnees['region'] = $bouteilleCatalogue->region->nom;
            }
        } else {
            // Essayer de récupérer l'image depuis le catalogue si code_saq existe
            if ($bouteille->code_saq) {
                $imageFromCatalogue = $bouteille->getImageFromCatalogue();
                if ($imageFromCatalogue) {
                    $donnees['image'] = $imageFromCatalogue;
                }
            }
        }

        return view('partage.show', [
            'partage' => $partage,
            'bouteille' => $bouteille,
            'donnees' => $donnees,
        ]);
    }
}
