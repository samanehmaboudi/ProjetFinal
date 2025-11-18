@extends('layouts.app')
@section('title', 'Mes Celliers')

@section('add-cellar-btn', '')

@section('content')
    <section class="p-4 pt-2 min-h-100">
        <x-page-header title="Mes Celliers" />
        <div class="mt-6">
            <x-cellar-box name="Cellier Principal" :amount="13" />
        </div>
        
    </section>
    
@endsection