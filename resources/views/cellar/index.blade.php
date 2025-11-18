@extends('layouts.app')
@section('title', 'Mes Celliers')

{{-- Ajoute le bouton Ajouter un cellier. Voir app.blade.php --}}
@section('add-cellar-btn', '')

@section('content')
    <section class="p-4 pt-2">
        <x-page-header title="Mes Celliers" />
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
            <x-cellar-box name="Cellier Principal" :amount="13" />
             <x-cellar-box name="Cellier Secondaire" :amount="3" />
              <x-cellar-box name="Cellier Garage" :amount="1" />
        </div>
        
    </section>
@endsection