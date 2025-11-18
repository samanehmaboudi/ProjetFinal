@props(['name'=>'Cellier', 'amount' => '0'])
<div>
   <h2 class="text-2xl font-semibold mb-2">{{ $name }}</h2>
   @if ($amount > 1)
       <p class="text-gray-600">{{ $amount }} Bouteilles</p>
    @else
       <p class="text-gray-600">{{ $amount }} Bouteille</p>
   @endif
</div>