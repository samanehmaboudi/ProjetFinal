@extends('layouts.app')

@section('title', 'Trop de requêtes')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center text-center px-4">

    <img src="{{ asset('images/429.png') }}"
         alt="Trop de requêtes" class="w-40 opacity-90">

    <h1 class="mt-6 text-4xl font-bold text-gray-800">
        Trop de requêtes
    </h1>

    <p class="mt-2 text-gray-600 max-w-md leading-relaxed">
        Vous avez effectué trop de requêtes en peu de temps.
        Veuillez patienter avant de réessayer.
    </p>

    <x-primary-btn label="Réessayer" type="href" :route="'bouteille.catalogue'" class="mt-6" />
</div>
@endsection
