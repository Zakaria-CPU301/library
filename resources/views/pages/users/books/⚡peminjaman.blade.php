<?php

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\User;
use App\Models\Borrow;

new class extends Component
{
    public $userId = 0;
    public $book = [];
    public $user = [];
    public $borrows = [];

    public function mount() {
        $this->userId = Auth::id();
        $this->user = User::findOrFail($this->userId);
        $this->borrows = Borrow::all();
    }

    #[Validate('required')]
    public $dateRange;

    public function save() {
        $this->validate();
        dd($this->dateRange);
    }

};
?>

<div>
    <x-header>
        <x-header-info title="Pinjam Buku" desc="" />
    </x-header>

    <div class="container">
        <form wire:submit="save">
            <div class="library-section-left w-[70%]">
                <div class="">
                    <x-input-label value="Fullname" />
                    <x-text-input :value="$this->user->fullname" disabled="true" />
                </div>
                <div class="mt-4">
                    <x-input-label value="Email address" />
                    <x-text-input :value="$this->user->email" disabled="true" />
                </div>
                <div class="mt-4 flex">
                    <div class="w-[60%] flex flex-col">
                        <x-input-label value="Borrow range date" />
                        <div class="" wire:ignore>
                            <x-text-input wire:ignore wire:model.live="dateRange" id="dateFlatpickr" />
                        </div>
                        <x-input-error :messages="$errors->get('dateRange')" />
                    </div>
                    <script>
                        flatpickr('#dateFlatpickr', {
                            mode: 'range',
                            dateFormat: 'Y-m-d',
                            altInput: true,
                            altFormat: 'd M Y',
                            minDate: 'today',
                        })
                    </script>
                    <div class="w-[40%]">
                        <x-input-label value="Total Quantity" />
                        <x-text-input type="number" min="1" max="" />
                    </div>
                </div>
            </div>
            <div class="library-section-right w-[30%]"></div>

            <x-primary-button>
                Pinjam
            </x-primary-button>
        </form>
    </div>
</div>