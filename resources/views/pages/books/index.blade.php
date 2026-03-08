<x-app-layout>
<div class="">
    <a href="/" wire:navigate>dashboard</a>
    <ul>
        @foreach ($books as $book)
            <li>{{$book->title}}</li>
        @endforeach
    </ul>
</div>
</x-app-layout>