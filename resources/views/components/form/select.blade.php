@props([
    'name',
    'label',
    'options' => [],
    'selected' => null,
])

<div class="form-control w-full mb-4">
    <label class="label">
        <span class="label-text font-semibold text-base-content/70">{{ $label }}</span>
    </label>
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        @class([
            'select select-bordered w-full focus:select-primary',
            'select-error' => $errors->has($name),
        ])
        {{ $attributes }}
    >
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ ($selected ?? old($name)) == $value ? 'selected' : '' }}>
                {{ $text }}
            </option>
        @endforeach
    </select>
    @error($name)
        <label class="label">
            <span class="label-text-alt text-error font-medium">{{ $message }}</span>
        </label>
    @enderror
</div>
