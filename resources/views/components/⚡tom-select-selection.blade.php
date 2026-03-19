<?php
    use Livewire\Component;
    use Livewire\Attributes\Modelable;

    new class extends Component {
        public $placeholder = '';
        public $id = '';

        #[Modelable]
        public $value = '';
    };
?>

<div wire:ignore>
    <select wire:model.live="value" id="{{$id}}">
        <option value="">{{$placeholder}}</option>
        {{$slot}}
    </select>
</div>
