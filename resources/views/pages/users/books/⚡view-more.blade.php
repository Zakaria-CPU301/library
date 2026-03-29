<?php

use Livewire\Component;

new class extends Component
{
    public $idBook = '';
    public function mount($idBook) {
        $this->idBook = $idBook;
    }
};
?>

<div>
    {{-- Nothing worth having comes easy. - Theodore Roosevelt --}}
    <p>hello world</p>
    @dump('belum ada WLeEEEelLellE')
</div>