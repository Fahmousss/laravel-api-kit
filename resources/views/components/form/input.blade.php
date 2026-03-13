@props([
    'name',
    'label',
    'type'  => 'text',
    'value' => null,
])

<div class="form-control w-full mb-4">
    <label class="label">
        <span class="label-text font-semibold text-base-content/70">{{ $label }}</span>
    </label>
    <input
        id="{{ $name }}"
        type="{{ $type }}"
        name="{{ $name }}"
        value="{{ $value ?? old($name) }}"
        @class([
            'input input-bordered w-full focus:input-primary',
            'input-error' => $errors->has($name),
        ])
        {{ $attributes }}
    >
    @error($name)
        <label class="label">
            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
        </label>
    @enderror
</div>
