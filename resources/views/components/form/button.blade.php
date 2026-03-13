@props(['label' => 'Submit'])

<button type="submit" {{ $attributes->merge(['class' => 'btn btn-primary w-full']) }}>
    {{ $label }}
</button>
