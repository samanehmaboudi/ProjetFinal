<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BouteilleCatalogue;
use App\Models\Pays;
use App\Models\TypeVin;

class CatalogueController extends Controller
{
    public function index()
    {
        // Récupère les 10 dernières bouteilles importées avec leurs relations
        $bouteilles = BouteilleCatalogue::with(['pays', 'typeVin'])
            ->orderBy('date_import', 'desc')
            ->paginate(10);

        $pays = Pays::orderBy('nom')->get();
        $types = TypeVin::orderBy('nom')->get();

        return view('bouteilles.catalogue', compact('bouteilles', 'pays', 'types'));
    }

    public function search(Request $request)
    {
        $query = BouteilleCatalogue::with(['pays', 'typeVin']);

        if ($request->search) {
            $query->where('nom', 'like', '%' . $request->search . '%');
        }

        if ($request->pays) {
            $query->where('id_pays', $request->pays);
        }

        if ($request->type) {
            $query->where('id_type_vin', $request->type);
        }

        $bouteilles = $query->paginate(10);

        return response()->json([
            'html' => view('bouteilles._catalogue_list', compact('bouteilles'))->render()
        ]);
    }
}
