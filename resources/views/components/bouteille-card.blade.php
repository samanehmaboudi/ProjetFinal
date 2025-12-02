@props([
    'nom' => '',
    'type' => null,
    'millesime' => null,
    'quantite' => null,
    'urlImage' => null,
    'pays' => null,
    'region' => null,
    'volume' => null,
    'prix' => null,
    'codeSaq' => null,
    'url' => null,
    'id' => null
])

{{-- Carte de bouteille avec tous les détails --}}
@if($id)
    <a 
        href="{{ route('catalogue.show', $id) }}" 
        class="bg-card border border-border-base rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 flex max-w-[500px] cursor-pointer"
        aria-label="Voir les détails de {{ $nom }}"
    >
@else
    <div 
        class="bg-card border border-border-base rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-all duration-300 flex max-w-[500px]"
        role="article"
        aria-label="Carte de la bouteille {{ $nom }}"
    >
@endif
    {{-- Image de la bouteille à gauche --}}
    <div class="relative w-32 sm:w-40 flex-shrink-0 bg-gray-50 flex items-center justify-center overflow-hidden">
        @if($urlImage)
            @php
                // Normaliser le chemin : enlever tous les préfixes storage/ et / au début
                $imagePath = ltrim($urlImage, '/');
                // Enlever tous les préfixes "storage/" jusqu'à ce qu'il n'y en ait plus
                while (str_starts_with($imagePath, 'storage/')) {
                    $imagePath = substr($imagePath, 8); // Enlever "storage/" (8 caractères)
                }
                // Ajouter storage/ une seule fois à la fin
                $imagePath = 'storage/' . $imagePath;
            @endphp
            <img src="{{ asset($imagePath) }}" 
                 alt="Bouteille {{ $nom }}" 
                 class="w-full h-full object-contain p-3">
        @else
            <div class="flex items-center justify-center p-3">
                <svg  version="1.0" xmlns="http://www.w3.org/2000/svg"  width="90.000000pt" height="90.000000pt" viewBox="0 0 300.000000 300.000000"  preserveAspectRatio="xMidYMid meet">  <g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="#757575" stroke="none"> <path d="M2771 5765 c-8 -19 -13 -325 -12 -680 3 -785 6 -767 -189 -955 -231 -222 -214 -70 -225 -2018 -10 -1815 -11 -1791 100 -1831 215 -77 1028 -70 1116 10 73 66 77 168 80 1839 4 1928 18 1815 -254 2058 -141 126 -147 164 -147 878 0 321 -6 618 -13 659 l-12 75 -215 0 c-187 0 -218 -5 -229 -35z"/> </g> </svg> 
            </div>
        @endif
    </div>

    {{-- Informations au centre --}}
    <div class="flex-1 p-4 space-y-2 min-w-0">
        {{-- Nom de la bouteille --}}
        <h3 class="font-semibold text-lg text-text-heading line-clamp-2">
            {{ $nom }}
        </h3>

        {{-- Informations principales --}}
        <div class="space-y-1.5" role="list" aria-label="Caractéristiques">
            {{-- Type de vin --}}
            @if($type)
                <div class="flex items-center gap-2" role="listitem">
                    <span class="text-xs font-medium text-muted uppercase tracking-wide">Type</span>
                    <span class="text-sm text-text-body">{{ $type }}</span>
                </div>
            @endif

            {{-- Millésime --}}
            @if($millesime)
                <div class="flex items-center gap-2" role="listitem">
                    <span class="text-xs font-medium text-muted uppercase tracking-wide">Millésime</span>
                    <span class="text-sm text-text-body font-medium">{{ $millesime }}</span>
                </div>
            @endif

            {{-- Pays --}}
            @if($pays)
                <div class="flex items-center gap-2" role="listitem">
                    <span class="text-xs font-medium text-muted uppercase tracking-wide">Pays</span>
                    <span class="text-sm text-text-body">{{ $pays }}</span>
                </div>
            @endif

            {{-- Région --}}
            @if($region)
                <div class="flex items-center gap-2" role="listitem">
                    <span class="text-xs font-medium text-muted uppercase tracking-wide">Région</span>
                    <span class="text-sm text-text-body">{{ $region }}</span>
                </div>
            @endif

            {{-- Volume --}}
            @if($volume)
                <div class="flex items-center gap-2" role="listitem">
                    <span class="text-xs font-medium text-muted uppercase tracking-wide">Volume</span>
                    <span class="text-sm text-text-body">{{ $volume }}</span>
                </div>
            @endif
        </div>

        {{-- Prix et code SAQ --}}
        <div class="pt-2 border-t border-border-base flex items-center justify-between flex-wrap gap-2">
            @if($prix !== null)
                <span class="text-lg font-bold text-primary" aria-label="Prix : {{ number_format($prix, 2) }} dollars">
                    {{ number_format($prix, 2) }} $
                </span>
            @endif
            @if($codeSaq)
                <span class="text-xs text-muted" aria-label="Code SAQ : {{ $codeSaq }}">Code SAQ: {{ $codeSaq }}</span>
            @endif
        </div>
    </div>
@if($id)
    </a>
@else
</div>
@endif