@props([
    'label' => null,
    'name' => null,
])

<div class="space-y-1.5">
    @if($label)
        <x-ui.label :for="$attributes->get('id') ?? $name">{{ $label }}</x-ui.label>
    @endif

    <x-ui.textarea :name="$name" {{ $attributes }} />

    @if($name)
        <x-ui.error :name="$name" />
    @endif
</div>
