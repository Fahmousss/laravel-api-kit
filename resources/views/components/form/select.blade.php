@props([
    'label' => null,
    'name' => null,
    'options' => [],
])

<div class="space-y-1.5">
    @if($label)
        <x-ui.label :for="$attributes->get('id') ?? $name">{{ $label }}</x-ui.label>
    @endif

    <x-ui.select :name="$name" {{ $attributes }}>
        @foreach($options as $value => $optionLabel)
            <x-ui.select.option :value="$value" :label="$optionLabel" />
        @endforeach
        {{ $slot }}
    </x-ui.select>

    @if($name)
        <x-ui.error :name="$name" />
    @endif
</div>
