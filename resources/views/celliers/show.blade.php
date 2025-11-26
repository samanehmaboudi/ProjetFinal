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
        // Valeurs actuelles (si un jour tu veux pré-remplir les champs)
        $currentNom       = $nom ?? '';
        $currentType      = $type ?? '';
        $currentPays      = $pays ?? '';
        $currentMillesime = $millesime ?? '';
    @endphp

    {{-- BARRE RECHERCHE + BOUTON FILTRE (style catalogue) --}}
    <div
        id="cellar-search-bar"
        class="mt-3 mb-4"
        data-search-url="{{ route('celliers.search', $cellier) }}"
        data-sort="{{ $sort }}"
        data-direction="{{ $direction }}"
    >
        <div class="flex items-center gap-3">
            {{-- Champ recherche plein largeur --}}
            <div class="flex-1">
                <label for="cellarSearchInput" class="sr-only">Recherche</label>
                <div class="relative">
                    <x-dynamic-component
                        :component="'lucide-search'"
                        class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-text-muted"
                    />
                    <input
                        id="cellarSearchInput"
                        type="text"
                        class="w-full border border-border-base rounded-md pl-9 pr-3 py-2 text-sm bg-background"
                        placeholder="Rechercher une bouteille..."
                        value="{{ $currentNom }}"
                    >
                </div>
            </div>

            {{-- Bouton qui ouvre le panneau de filtres --}}
            <button
                type="button"
                id="cellarSortOptionsBtn"
                class="inline-flex items-center justify-center w-10 h-10 rounded-md border border-border-base bg-card hover:bg-muted transition"
            >
                <x-dynamic-component :component="'lucide-sliders-horizontal'" class="w-5 h-5" />
                <span class="sr-only">Ouvrir les filtres</span>
            </button>
        </div>
    </div>

    {{-- CONTENEUR MIS À JOUR PAR LE JS (AJAX) --}}
    <div id="cellarBottlesContainer">
        <div class="bg-card border border-border-base rounded-xl shadow-md p-6 mt-4">
            @if ($cellier->bouteilles->isEmpty())
                <p class="text-text-muted">
                    Ce cellier est encore vide. Utilisez le bouton « Ajouter une bouteille » pour commencer.
                </p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($cellier->bouteilles as $bouteille)
                        <a
                            href="{{ route('bouteilles.show', [$cellier, $bouteille]) }}"
                            class="border border-border-base rounded-lg p-4 flex flex-col gap-3 hover:shadow-lg transition-all duration-300 cursor-pointer"
                            data-bottle-id="{{ $bouteille->id }}"
                        >
                            <div class="flex items-center justify-between gap-2">
                                <h2 class="font-semibold text-text-title">
                                    {{ $bouteille->nom }}
                                </h2>

                                {{-- Contrôles quantité + badge --}}
                                <div class="flex items-center gap-2 stop-link-propagation">
                                    {{-- Bouton - --}}
                                    <button
                                        type="button"
                                        class="qty-btn bottle-qty-minus inline-flex items-center justify-center w-7 h-7 rounded-full border border-border-base text-primary hover:bg-primary/10"
                                        data-url="{{ route('bouteilles.quantite.update', [$cellier, $bouteille]) }}"
                                        data-direction="down"
                                        data-bouteille="{{ $bouteille->id }}"
                                        data-qty-btn
                                        data-cellier-id="{{ $cellier->id }}"
                                        data-bottle-id="{{ $bouteille->id }}"
                                    >
                                        –
                                    </button>

                                    {{-- Badge quantité --}}
                                    <div
                                        class="qty-display bottle-qty-value inline-flex items-center justify-center rounded-full bg-primary text-white text-xs px-2 py-0.5 min-w-16 text-center"
                                        data-bouteille="{{ $bouteille->id }}"
                                        data-qty-value="{{ $bouteille->id }}"
                                    >
                                        x {{ $bouteille->quantite ?? 1 }}
                                    </div>

                                    {{-- Bouton + --}}
                                    <button
                                        type="button"
                                        class="qty-btn bottle-qty-plus inline-flex items-center justify-center w-7 h-7 rounded-full border border-border-base text-primary hover:bg-primary/10"
                                        data-url="{{ route('bouteilles.quantite.update', [$cellier, $bouteille]) }}"
                                        data-direction="up"
                                        data-bouteille="{{ $bouteille->id }}"
                                        data-qty-btn
                                        data-cellier-id="{{ $cellier->id }}"
                                        data-bottle-id="{{ $bouteille->id }}"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>

                            {{-- Informations --}}
                            <div class="text-sm text-text-muted space-y-1">
                                @if ($bouteille->pays)
                                    <p>
                                        <span class="font-medium text-text-body">Pays :</span>
                                        {{ $bouteille->pays }}
                                    </p>
                                @endif

                                @if ($bouteille->type)
                                    <p>
                                        <span class="font-medium text-text-body">Type :</span>
                                        {{ $bouteille->type }}
                                    </p>
                                @endif

                                @if ($bouteille->millesime)
                                    <p>
                                        <span class="font-medium text-text-body">Millésime :</span>
                                        {{ $bouteille->millesime }}
                                    </p>
                                @endif

                                @if ($bouteille->format)
                                    <p>
                                        <span class="font-medium text-text-body">Format :</span>
                                        {{ $bouteille->format }}
                                    </p>
                                @endif

                                @if (!is_null($bouteille->prix))
                                    <p>
                                        <span class="font-medium text-text-body">Prix :</span>
                                        {{ number_format($bouteille->prix, 2, ',', ' ') }} $
                                    </p>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-2 mt-auto stop-link-propagation">
                                <x-delete-btn 
                                    :route="route('bouteilles.delete', [
                                        'cellier'   => $cellier->id,
                                        'bouteille' => $bouteille->id,
                                    ])"
                                />

                                @if ($bouteille->code_saq === null)
                                    <x-edit-btn
                                        :route="route('bouteilles.edit', [$cellier->id, $bouteille->id])"
                                        label="Modifier"
                                    />
                                @endif
                            </div>
                        </a>
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

{{-- OVERLAY des filtres (simple, sans animation compliquée) --}}
<div
    id="cellarFiltersOverlay"
    class="fixed inset-0 z-10 bg-black/50 hidden"
></div>

{{-- PANNEAU DE FILTRES / TRI (bottom sheet) --}}
<div
    id="cellarFiltersContainer"
    class="fixed inset-x-0 bottom-0 z-20 bg-card border-t border-border-base shadow-xl rounded-t-2xl hidden"
>
    <div class="flex justify-center pt-3">
        <span
            id="cellarDragHandle"
            class="h-1.5 w-12 rounded-full bg-border-base cursor-pointer"
        ></span>
    </div>

    <div class="px-4 pb-6 pt-4 space-y-4 max-w-4xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-text-title">Options de filtre</h2>
            <button type="button" id="closeCellarFilters" class="p-2">
                <x-dynamic-component :component="'lucide-x'" class="w-5 h-5" />
                <span class="sr-only">Fermer les filtres</span>
            </button>
        </div>

        {{-- Champs filtres --}}
        <div class="grid gap-4 md:grid-cols-3">
            <div class="flex flex-col gap-1">
                <label for="cellarPaysFilter" class="text-sm font-medium text-text-body">Pays</label>
                <input
                    id="cellarPaysFilter"
                    type="text"
                    class="border border-border-base rounded-md px-3 py-2 text-sm bg-background"
                    placeholder="Ex. France"
                    value="{{ $currentPays }}"
                >
            </div>

            <div class="flex flex-col gap-1">
                <label for="cellarTypeFilter" class="text-sm font-medium text-text-body">Type</label>
                <input
                    id="cellarTypeFilter"
                    type="text"
                    class="border border-border-base rounded-md px-3 py-2 text-sm bg-background"
                    placeholder="Ex. Rouge"
                    value="{{ $currentType }}"
                >
            </div>

            <div class="flex flex-col gap-1">
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

        {{-- Tri (PV-54) --}}
        <form
            method="GET"
            action="{{ route('cellar.show', $cellier->id) }}"
            class="grid gap-4 md:grid-cols-3 items-end"
        >
            <div class="flex flex-col gap-1">
                <label for="sort" class="text-sm font-medium text-text-body">
                    Trier par
                </label>
                <select
                    id="sort"
                    name="sort"
                    class="border border-border-base rounded-md px-2 py-2 text-sm bg-background"
                >
                    <option value="nom"        {{ $sort === 'nom' ? 'selected' : '' }}>Nom</option>
                    <option value="pays"       {{ $sort === 'pays' ? 'selected' : '' }}>Pays</option>
                    <option value="type"       {{ $sort === 'type' ? 'selected' : '' }}>Type</option>
                    <option value="quantite"   {{ $sort === 'quantite' ? 'selected' : '' }}>Quantité</option>
                    <option value="format"     {{ $sort === 'format' ? 'selected' : '' }}>Format</option>
                    <option value="prix"       {{ $sort === 'prix' ? 'selected' : '' }}>Prix</option>
                    <option value="millesime"  {{ $sort === 'millesime' ? 'selected' : '' }}>Millésime</option>
                    <option value="date_ajout" {{ $sort === 'date_ajout' ? 'selected' : '' }}>
                        Date d'ajout
                    </option>
                </select>
            </div>

            <div class="flex flex-col gap-1">
                <label for="direction" class="text-sm font-medium text-text-body">
                    Ordre
                </label>
                <select
                    id="direction"
                    name="direction"
                    class="border border-border-base rounded-md px-2 py-2 text-sm bg-background"
                >
                    <option value="asc"  {{ $direction === 'asc' ? 'selected' : '' }}>Croissant</option>
                    <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>Décroissant</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    id="resetCellarFilters"
                    class="px-3 py-2 text-sm rounded-md border border-border-base text-text-body hover:bg-muted"
                >
                    Réinitialiser
                </button>

                <button
                    type="submit"
                    class="px-3 py-2 text-sm rounded-md bg-primary text-white hover:bg-primary/90"
                >
                    Appliquer le tri
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
