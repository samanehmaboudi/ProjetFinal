@extends('layouts.app')

@section('title', 'Catalogue des bouteilles')
<section class="p-4">
<x-page-header title="Catalogue des bouteilles" />
<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 mt-6">
    @foreach ($bouteilles as $bouteille)
        @php
            // Normaliser le chemin de l'image comme dans bouteille-card
            $imagePath = null;
            if ($bouteille->url_image) {
                $imagePath = ltrim($bouteille->url_image, '/');
                while (str_starts_with($imagePath, 'storage/')) {
                    $imagePath = substr($imagePath, 8);
                }
                $imagePath = asset('storage/' . $imagePath);
            }
        @endphp
        <x-bouteille-card-block 
            :id="$bouteille->id" 
            :nom="$bouteille->nom" 
            :image="$imagePath" 
            :prix="$bouteille->prix" 
        />
    @endforeach
</div>
</section>
<x-modal-pick-cellar />
@section('content')

@endsection