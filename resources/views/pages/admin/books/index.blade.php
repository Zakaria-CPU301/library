<?php
    use Livewire\Component;

    new class extends Component{};
?>
<div class="">
    <x-session-success />
    <a href="/" wire:navigate>dashboard</a>

    <div class="flex flex-col gap-5">
        @for ($i = 0; $i <= 10; $i++)
            <marquee behavior="" direction="">
                <h1 class="uppercase text-2xl">sedang dalam perbaikan</h1>
            </marquee>
        @endfor
    </div>
</div>