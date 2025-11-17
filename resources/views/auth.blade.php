@extends('layouts.app-noNav')

@section('title', 'Welcome')

@section('content')
    <section class="bg-card p-5 flex flex-col justify-start items-center max-w-sm mx-auto min-h-screen border-border-base">
        <header class="flex flex-col justify-center items-center py-10 gap-4">
            <img src="{{ asset('images/logo_vino.png') }}" class="w-32" alt="Logo Vino">
            <h1 class="text-text-body">Gérer vos celliers, simplement</h1>
        </header>
        <div id='authForm' class="w-full flex flex-col">
         <div class="flex w-full mb-5">
            <button id="loginBtn" class="flex-1 font-bold text-lg text-primary border-b border-primary text-center py-2 hover:bg-neutral-300 transition-colors duration-300">
                Connexion
            </button>
            <button id="registerBtn" class="flex-1 font-bold text-lg text-neutral-600 border-b border-neutral-600 text-center py-2 hover:bg-neutral-300 transition-colors duration-300">
                S'inscrire
            </button>
        </div>
        <x-form-login id="loginForm" />
        <x-form-register id="registerForm" class="hidden"/>
        <div>
    </section>    
@endsection