@props(["id" => null, "route" => null])
<button onclick="location.href='{{ $route }}'" class="p-2 bg-card hover:bg-card-hover rounded-lg border-border-base border shadow-md hover:shadow-sm transition-all duration-300">
    <x-dynamic-component :component="'lucide-pen'" class="w-6 stroke-text-heading "/>
</button>