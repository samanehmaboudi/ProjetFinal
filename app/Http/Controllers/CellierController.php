<?php

namespace App\Http\Controllers;

use App\Models\Cellier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CellierController extends Controller
{
    /**
     * Liste les celliers de l'utilisateur connecté.
     */
    public function index()
    {
        $user = Auth::user();

        $celliers = $user->celliers()
            ->orderByDesc('date_creation')
            ->get();

        return view('cellar.index', compact('celliers'));
    }

    /**
     * Formulaire de création d'un nouveau cellier.
     */
    public function create()
    {
        return view('cellar.create');
    }

    /**
     * Enregistre un nouveau cellier en BD.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string', // si tu ajoutes ce champ plus tard
        ]);

        $request->user()->celliers()->create([
            'nom'           => $validated['nom'],
            // 'description' => $validated['description'] ?? null, // si tu as cette colonne
        ]);

        return redirect()
            ->route('cellar.index')
            ->with('success', 'Le cellier a été créé avec succès.');
    }

    /**
     * (Optionnel) Affiche un cellier spécifique.
     */
    public function show(Cellier $cellier)
    {
        $this->authorizeCellier($cellier);

        return view('celliers.show', compact('cellier'));
    }

    /**
     * Formulaire d’édition d’un cellier.
     */
    public function edit(Cellier $cellier)
    {
        $this->authorizeCellier($cellier);

        return view('cellar.update', compact('cellier'));
    }

    /**
     * Met à jour un cellier existant.
     */
    public function update(Request $request, Cellier $cellier)
    {
        $this->authorizeCellier($cellier);

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            // 'description' => 'nullable|string',
        ]);

        $cellier->update([
            'nom' => $validated['nom'],
            // 'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->route('cellar.index')
            ->with('success', 'Le cellier a été mis à jour.');
    }

    /**
     * Supprime un cellier.
     */
    public function destroy(Cellier $cellier)
    {
        $this->authorizeCellier($cellier);

        $cellier->delete();

        return redirect()
            ->route('cellar.index')
            ->with('success', 'Le cellier a été supprimé.');
    }

    /**
     * Vérifie que le cellier appartient bien à l'utilisateur connecté.
     */
    protected function authorizeCellier(Cellier $cellier): void
    {
        if ($cellier->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
