@extends('layouts.app') 

@section('title', 'Mon cellier – ' . $cellier->nom)

{{-- On masque éventuellement le bouton "Ajouter un vin" par défaut du layout --}}
@section('add-wine-btn', '')

@section('content')
<section class="p-4 pt-2">
    <x-page-header
        :title="$cellier->nom"
        :undertitle="$cellier->bouteilles->count() . ' bouteille' . ($cellier->bouteilles->count() > 1 ? 's' : '')"
    />

    @php
        // Valeurs actuelles pour la recherche (PV-55 / PV-56)
        $currentNom       = $nom ?? '';
        $currentType      = $type ?? '';
        $currentPays      = $pays ?? '';
        $currentMillesime = $millesime ?? '';
    @endphp

    {{-- PV-56 : barre de recherche + filtres --}}
    <div
        id="cellar-search-bar"
        class="mt-3 mb-4 flex flex-col gap-4 md:flex-row md:items-end md:justify-between"
        data-search-url="{{ route('celliers.search', $cellier) }}"
        data-sort="{{ $sort }}"
        data-direction="{{ $direction }}"
    >
        {{-- Champ de recherche principal --}}
        <div class="flex flex-col gap-2 w-full md:w-1/3">
            <label for="cellarSearchInput" class="text-sm font-medium text-text-body">
                Recherche
            </label>
            <input
                id="cellarSearchInput"
                type="text"
                class="border border-border-base rounded-md px-3 py-2 text-sm bg-background"
                placeholder="Rechercher une bouteille..."
                value="{{ $currentNom }}"
            >
        </div>

        {{-- Filtres supplémentaires --}}
        <div class="flex flex-wrap gap-4 w-full md:w-2/3">
            <div class="flex flex-col gap-1 min-w-[140px]">
                <label for="cellarPaysFilter" class="text-sm font-medium text-text-body">Pays</label>
                <input
                    id="cellarPaysFilter"
                    type="text"
                    class="border border-border-base rounded-md px-3 py-2 text-sm bg-background"
                    placeholder="Ex. France"
                    value="{{ $currentPays }}"
                >
            </div>

            <div class="flex flex-col gap-1 min-w-[140px]">
                <label for="cellarTypeFilter" class="text-sm font-medium text-text-body">Type</label>
                <input
                    id="cellarTypeFilter"
                    type="text"
                    class="border border-border-base rounded-md px-3 py-2 text-sm bg-background"
                    placeholder="Ex. Rouge"
                    value="{{ $currentType }}"
                >
            </div>

            <div class="flex flex-col gap-1 min-w-[140px]">
                <label for="cellarMillesimeFilter" class="text-sm font-medium text-text-body">Millésime</label>
                <input
                    id="cellarMillesimeFilter"
                    type="text"
                    class="border border-border-base rounded-md px-3 py-2 text-sm bg-background"
                    placeholder="Ex. 2018"
                    value="{{ $currentMillesime }}"
                >
            </div>
        </div>
    </div>

    {{-- PV-54 : formulaire de tri --}}
    <form
        method="GET"
        action="{{ route('cellar.show', $cellier->id) }}"
        class="mt-1 mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
    >
        <div class="flex flex-wrap items-center gap-6">
            {{-- Groupe "Trier par" --}}
            <div class="flex items-center gap-3">
                <label for="sort" class="text-sm font-medium text-text-body">
                    Trier par :
                </label>
                <select
                    id="sort"
                    name="sort"
                    class="border border-border-base rounded-md px-2 py-1 text-sm bg-background"
                >
                    <option value="nom"        {{ $sort === 'nom' ? 'selected' : '' }}>Nom</option>
                    <option value="pays"       {{ $sort === 'pays' ? 'selected' : '' }}>Pays</option>
                    <option value="type"       {{ $sort === 'type' ? 'selected' : '' }}>Type</option>
                    <option value="quantite"   {{ $sort === 'quantite' ? 'selected' : '' }}>Quantité</option>
                    <option value="format"     {{ $sort === 'format' ? 'selected' : '' }}>Format</option>
                    <option value="prix"       {{ $sort === 'prix' ? 'selected' : '' }}>Prix</option>
                    {{-- IMPORTANT : la valeur doit être "date_ajout" (clé du tableau allowedBottleSorts) --}}
                    <option value="date_ajout" {{ $sort === 'date_ajout' ? 'selected' : '' }}>
                        Date d'ajout
                    </option>
                </select>
            </div>

            {{-- Groupe "Ordre" --}}
            <div class="flex items-center gap-3">
                <label for="direction" class="text-sm font-medium text-text-body">
                    Ordre :
                </label>
                <select
                    id="direction"
                    name="direction"
                    class="border border-border-base rounded-md px-2 py-1 text-sm bg-background"
                >
                    <option value="asc"  {{ $direction === 'asc' ? 'selected' : '' }}>Croissant</option>
                    <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>Décroissant</option>
                </select>
            </div>
        </div>

        <button
            type="submit"
            class="inline-flex items-center justify-center px-3 py-1.5 rounded-md bg-primary text-white text-sm hover:bg-primary/90"
        >
            Appliquer le tri
        </button>
    </form>

    {{-- PV-56 : conteneur que le JS met à jour (AJAX) --}}
    <div id="cellarBottlesContainer">
        {{-- Liste des bouteilles --}}
        <div class="bg-card border border-border-base rounded-xl shadow-md p-6 mt-4">
            {{-- Si vide, affiche un message --}}
            @if ($cellier->bouteilles->isEmpty())
                <p class="text-text-muted">
                    Ce cellier est encore vide. Utilisez le bouton « Ajouter une bouteille » pour commencer.
                </p>
            {{-- Sinon affiche la liste des bouteilles --}}
            @else
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    @foreach ($cellier->bouteilles as $bouteille)
                        <x-bouteille-card-block 
                            :id="$bouteille->id" 
                            :nom="$bouteille->nom" 
                            :image="$bouteille->getImageFromCatalogue()"
                            :prix="$bouteille->prix ?? ''" 
                            mode="cellier"
                            :cellierId="$cellier->id"
                            :bouteilleId="$bouteille->id"
                            :quantite="$bouteille->quantite ?? 1"
                            :pays="$bouteille->pays ?? null"
                            :format="$bouteille->format ?? null"
                            :codeSaq="$bouteille->code_saq ?? null"
                        />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

{{-- Fenêtre flottante "Ajouter un vin" --}}
<div
    id="addWineBtnContainer"
    class="fixed z-10 bottom-0 left-0 w-full p-4 pt-10 bg-card border border-border-base shadow-lg rounded-t-lg transform translate-y-full transition-transform duration-300"
>
    <span class="flex items-center justify-between mb-4">
        <h1 class="text-3xl text-heading font-heading">Ajouter un vin</h1>
        <x-dynamic-component :component="'lucide-x'" id="closeAddWine" class="w-6 h-6" />
    </span>

    <div class="flex flex-col gap-4">
        <x-icon-text-btn
            :href="route('bouteille.catalogue')"
            icon="wine"
            title="Explorer le catalogue SAQ"
            subtitle="Recherchez des vins répertoriés à la SAQ."
        />
        <x-icon-text-btn
            :href="route('bouteilles.manuelles.create', $cellier->id)"
            icon="notebook-pen"
            title="Ajouter manuellement"
            subtitle="Pour les vins non répertoriés à la SAQ."
        />
    </div>
</div>
@endsection
