<?php
    use Livewire\Component;
    use Livewire\Attributes\Modelable;

    new class extends Component {
        public $placeholder = '';
        public $id = '';
        public $editValue = '';
        public $category_id;

        #[Modelable]
        public $value = '';
    };
?>

<div wire:ignore class="w-full">
    <select wire:model.live="value" id="{{$id}}" class="w-full">
        <option value="{{$value ?? ''}}">{{$placeholder}}</option>
        {{$slot}}
    </select>
</div>
