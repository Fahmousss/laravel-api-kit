@props([
    'name',
    'label',
    'options' => [], // ['value' => 'Label']
    'selected' => null,
])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-500 mb-1.5">{{ $label }}</label>
    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            class="w-full px-3.5 py-2.5 bg-white border {{ $errors->has($name) ? 'border-rose-500/50 focus:border-rose-500 focus:ring-rose-500/20' : 'border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/20' }} rounded-lg text-slate-800 text-sm outline-none transition-all focus:ring-4 appearance-none"
            {{ $attributes }}
        >
            <option value="" class="bg-white text-slate-500">Select an option...</option>
            @foreach($options as $value => $text)
                <option value="{{ $value }}" class="bg-white text-slate-800" @selected(old($name, $selected) == $value)>
                    {{ $text }}
                </option>
            @endforeach
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </div>
    </div>
    @error($name)
        <p class="mt-1.5 text-sm text-rose-400">{{ $message }}</p>
    @enderror
</div>
