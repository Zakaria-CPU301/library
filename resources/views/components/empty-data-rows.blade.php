<div class="flex flex-col items-center justify-center gap-3 py-5">
    <div class="text-4xl text-gray-400">
        {{$icon}}
    </div>

    <h2 class="text-base font-semibold text-gray-700">
        {{$info}}
    </h2>

    <a href="{{$route}}" class="{{$class}} text-white py-2 px-4 rounded-xl hover:scale-110 transition" wire:navigate>{{$label}}</a>
</div>