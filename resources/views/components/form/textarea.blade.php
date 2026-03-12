@props([
    'name',
    'label',
    'value' => null,
    'rows' => 3
])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-500 mb-1.5">{{ $label }}</label>
    <div class="relative">
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            rows="{{ $rows }}"
            class="w-full px-3.5 py-2.5 bg-white border {{ $errors->has($name) ? 'border-rose-500/50 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20' }} rounded-lg text-slate-800 text-sm outline-none transition-all placeholder:text-slate-500 focus:ring-4 resize-y"
            {{ $attributes }}
        >{{ $value ?? old($name) }}</textarea>
    </div>
    @error($name)
        <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
    @enderror
</div>
