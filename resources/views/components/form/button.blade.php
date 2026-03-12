{{--
    Primary submit button component.

    Props:
    - label (optional) : button text, default 'Submit'
--}}
@props(['label' => 'Submit'])

<button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-lg shadow-emerald-600/25 transition-all outline-none focus:ring-4 focus:ring-emerald-500/20 active:scale-[0.98]" {{ $attributes }}>
    {{ $label }}
</button>
