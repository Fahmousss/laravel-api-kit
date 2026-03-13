@props(['label' => 'Submit'])

<x-ui.button type="submit" variant="primary" {{ $attributes->merge(['class' => 'w-full']) }}>
    {{ $label }}
</x-ui.button>
