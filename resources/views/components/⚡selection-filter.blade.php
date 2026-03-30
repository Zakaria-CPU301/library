<?php

use Livewire\Component;

new class extends Component
{
    public $dataFilters = [];
    public $id;
    public $filterId;

    public function updatedFilterId() {
        $this->dispatch('select-filter', $this->filterId);
    }
};
?>

<div wire:ignore class="w-full">
    <select wire:model.live="filterId" id="{{$id}}" class="w-full">
        <option value="">Cari Kategory</option>
        @foreach ($dataFilters as $data)
            <option value="{{$data->id}}">{{$data->category_name}}</option>
        @endforeach
    </select>
</div>