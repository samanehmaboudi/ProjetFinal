<?php

namespace App\Http\Controllers;

use App\Models\BouteilleCatalogue;
use Illuminate\Http\Request;

/**
 * Contrôleur pour la page d'accueil
 * 
 * Gère l'affichage de la page de landing avec le formulaire d'authentification
 * et les bouteilles du catalogue pour les tests
 */
class AccueilController extends Controller
{
    /**
     * Affiche la page d'accueil avec le formulaire d'authentification
     * et les 10 dernières bouteilles importées
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupère les 10 dernières bouteilles importées pour les tests
        $bouteilles = BouteilleCatalogue::with(['pays', 'typeVin'])
            ->orderBy('date_import', 'desc')
            ->limit(10)
            ->get();

        return view('auth', compact('bouteilles'));
    }
}

