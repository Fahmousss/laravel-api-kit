{{--
    Form input component with inline error support.

    Props:
    - name   (required) : field name
    - label  (required) : visible label text
    - type   (optional) : input type, default 'text'
    - value  (optional) : pre-filled value (old() by default)
    - attrs  (optional) : any additional HTML attributes (autofocus, autocomplete, etc.)
--}}
@props([
    'name',
    'label',
    'type'  => 'text',
    'value' => null,
])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-500 mb-1.5">{{ $label }}</label>
    <div class="relative">
        <input
            id="{{ $name }}"
            type="{{ $type }}"
            name="{{ $name }}"
            value="{{ $value ?? old($name) }}"
            class="w-full px-3.5 py-2.5 bg-white border {{ $errors->has($name) ? 'border-rose-500/50 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20' }} rounded-lg text-slate-800 text-sm outline-none transition-all placeholder:text-slate-500 focus:ring-4"
            {{ $attributes }}
        >
    </div>
    @error($name)
        <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
    @enderror
</div>
