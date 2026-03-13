@props([
    'name',
    'label',
    'value' => null,
])

<div class="form-control w-full mb-4">
    <label class="label">
        <span class="label-text font-semibold text-base-content/70">{{ $label }}</span>
    </label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        @class([
            'textarea textarea-bordered w-full focus:textarea-primary',
            'textarea-error' => $errors->has($name),
        ])
        {{ $attributes }}
    >{{ $value ?? old($name) }}</textarea>
    @error($name)
        <label class="label">
            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
        </label>
    @enderror
</div>
