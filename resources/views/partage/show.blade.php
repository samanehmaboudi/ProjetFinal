@extends('layouts.app-noNav')

@section('title', 'Bouteille partagée – ' . $donnees['nom'])

@section('content')
<div class="min-h-screen bg-background pt-8 pb-16" role="main" aria-label="Bouteille partagée">
    <section class="p-4 sm:w-full max-w-4xl mx-auto">
        <div class="bg-card border border-border-base rounded-xl shadow-md p-6">
            
            {{-- En-tête --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-text-heading mb-2">
                    Bouteille partagée
                </h1>
                <p class="text-text-muted text-sm">
                    Cette bouteille a été partagée avec vous
                </p>
            </div>

            {{-- Contenu principal --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Image de la bouteille --}}
                <div class="flex items-center justify-center bg-gray-50 rounded-lg p-8 min-h-[400px]">
                    @if($donnees['image'])
                        @php
                            // Normaliser le chemin de l'image
                            $imagePath = ltrim($donnees['image'], '/');
                            // Si l'image commence déjà par http, c'est une URL complète
                            if (str_starts_with($imagePath, 'http')) {
                                $imageUrl = $imagePath;
                            } else {
                                // Sinon, utiliser asset() pour le chemin local
                                while (str_starts_with($imagePath, 'storage/')) {
                                    $imagePath = substr($imagePath, 8);
                                }
                                $imageUrl = asset('storage/' . $imagePath);
                            }
                        @endphp
                        <img 
                            src="{{ $imageUrl }}" 
                            alt="Bouteille {{ $donnees['nom'] }}" 
                            class="max-w-full max-h-[400px] object-contain"
                        >
                    @else
                        <div class="text-center text-text-muted" role="status">
                            <svg  version="1.0" xmlns="http://www.w3.org/2000/svg"  width="300.000000pt" height="300.000000pt" viewBox="0 0 300.000000 300.000000"  preserveAspectRatio="xMidYMid meet">  <g transform="translate(0.000000,300.000000) scale(0.050000,-0.050000)" fill="#757575" stroke="none"> <path d="M2771 5765 c-8 -19 -13 -325 -12 -680 3 -785 6 -767 -189 -955 -231 -222 -214 -70 -225 -2018 -10 -1815 -11 -1791 100 -1831 215 -77 1028 -70 1116 10 73 66 77 168 80 1839 4 1928 18 1815 -254 2058 -141 126 -147 164 -147 878 0 321 -6 618 -13 659 l-12 75 -215 0 c-187 0 -218 -5 -229 -35z"/> </g> </svg> 
                        </div>
                    @endif
                </div>

                {{-- Informations de la bouteille --}}
                <div class="space-y-6">
                    
                    {{-- Nom --}}
                    <div>
                        <h2 class="text-3xl font-bold text-text-heading mb-2">
                            {{ $donnees['nom'] }}
                        </h2>
                    </div>

                    {{-- Informations détaillées --}}
                    <div class="space-y-4">
                        
                        {{-- Type --}}
                        @if($donnees['type'])
                            <div class="border-b border-border-base pb-3">
                                <span class="text-sm font-medium text-text-muted uppercase tracking-wide">Type</span>
                                <p class="text-lg text-text-body mt-1">{{ $donnees['type'] }}</p>
                            </div>
                        @endif

                        {{-- Pays --}}
                        @if($donnees['pays'])
                            <div class="border-b border-border-base pb-3">
                                <span class="text-sm font-medium text-text-muted uppercase tracking-wide">Pays</span>
                                <p class="text-lg text-text-body mt-1">{{ $donnees['pays'] }}</p>
                            </div>
                        @endif

                        {{-- Région --}}
                        @if(isset($donnees['region']) && $donnees['region'])
                            <div class="border-b border-border-base pb-3">
                                <span class="text-sm font-medium text-text-muted uppercase tracking-wide">Région</span>
                                <p class="text-lg text-text-body mt-1">{{ $donnees['region'] }}</p>
                            </div>
                        @endif

                        {{-- Millésime --}}
                        @if($donnees['millesime'])
                            <div class="border-b border-border-base pb-3">
                                <span class="text-sm font-medium text-text-muted uppercase tracking-wide">Millésime</span>
                                <p class="text-lg text-text-body mt-1 font-semibold">{{ $donnees['millesime'] }}</p>
                            </div>
                        @endif

                        {{-- Format/Volume --}}
                        @if($donnees['format'])
                            <div class="border-b border-border-base pb-3">
                                <span class="text-sm font-medium text-text-muted uppercase tracking-wide">Format</span>
                                <p class="text-lg text-text-body mt-1">{{ $donnees['format'] }}</p>
                            </div>
                        @endif

                        {{-- Prix --}}
                        @if($donnees['prix'])
                            <div class="border-b border-border-base pb-3">
                                <span class="text-sm font-medium text-text-muted uppercase tracking-wide">Prix</span>
                                <p class="text-lg text-text-body mt-1 font-semibold" aria-label="{{ number_format($donnees['prix'], 2, ',', ' ') }} dollars">
                                    {{ number_format($donnees['prix'], 2, ',', ' ') }} $
                                </p>
                            </div>
                        @endif

                        {{-- Lien SAQ.com --}}
                        @if(isset($donnees['url_saq']) && $donnees['url_saq'])
                            <div class="border-b border-border-base pb-3">
                                <span class="text-sm font-medium text-text-muted uppercase tracking-wide mb-2 block">Voir sur SAQ.com</span>
                                <a 
                                    href="{{ $donnees['url_saq'] }}" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-2 text-primary hover:text-primary-hover hover:underline font-semibold transition-colors"
                                    aria-label="Ouvrir la page de la bouteille sur SAQ.com dans un nouvel onglet"
                                >
                                    <span>Visiter la page SAQ</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                </a>
                            </div>
                        @endif

                    </div>

                    {{-- Section notation et notes de dégustation --}}
                    @if((isset($donnees['note_degustation']) && $donnees['note_degustation']) || (isset($donnees['rating']) && $donnees['rating']))
                        <div class="mt-8 pt-6 border-t border-border-base">
                            <h3 class="text-xl font-semibold text-text-heading mb-4">Évaluation du propriétaire</h3>
                            
                            {{-- Affichage de la notation par étoiles --}}
                            @if(isset($donnees['rating']) && $donnees['rating'])
                                <div class="mb-4">
                                    <span class="text-sm font-medium text-text-muted mb-2 block">Note</span>
                                    <x-star-rating 
                                        :rating="$donnees['rating']" 
                                        :editable="false"
                                    />
                                </div>
                            @endif
                            
                            {{-- Affichage des notes de dégustation --}}
                            @if(isset($donnees['note_degustation']) && $donnees['note_degustation'])
                                <div class="mt-4">
                                    <span class="text-sm font-medium text-text-muted mb-2 block">Notes de dégustation</span>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-border-base">
                                        <p class="text-text-body whitespace-pre-wrap">{{ $donnees['note_degustation'] }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Message informatif --}}
                    <div class="mt-8 pt-6 border-t border-border-base">
                        <div class="bg-gray-50 rounded-lg p-4 border border-border-base">
                            <p class="text-sm text-text-muted">
                                💡 Cette bouteille fait partie d'une collection privée. Pour créer votre propre collection et partager vos bouteilles, 
                                <a href="{{ route('register.form') }}" class="text-primary hover:underline font-semibold">inscrivez-vous</a> 
                                ou 
                                <a href="{{ route('login.form') }}" class="text-primary hover:underline font-semibold">connectez-vous</a>.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection

