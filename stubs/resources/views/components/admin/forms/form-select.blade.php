@props([
    'name',
    'label',
    'options' => [],
    'value' => '',
    'required' => false,
    'placeholder' => '-- Pilih --',
    'hint' => '',
    'hintClass' => 'text-gray-400',
])

<div>
    <label for="{{ $name }}" class="block text-[12px] sm:text-[13px] font-medium text-gray-700 mb-1">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }}
        class="w-full text-[12px] sm:text-[13px] px-3 py-1.5 sm:py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-hijau/20 focus:border-hijau transition-all bg-white">
        <option value="" disabled {{ empty(old($name, $value)) ? 'selected' : '' }}>{{ $placeholder }}</option>
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @error($name)
        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
    @enderror
    @if ($hint)
        <p class="text-[10px] sm:text-[11px] {{ $hintClass }} mt-1">{{ $hint }}</p>
    @endif
</div>
