@extends('layouts.app')

@section('title', 'Catalogue des bouteilles')
<section class="p-4">
<x-page-header title="Catalogue des bouteilles" />
<x-search-filter :pays="$pays" :types="$types" />
 <div id="catalogueContainer">
        @include('bouteilles._catalogue_list', ['bouteilles' => $bouteilles])

</div>
</section>
<x-modal-pick-cellar />
@section('content')

@endsection