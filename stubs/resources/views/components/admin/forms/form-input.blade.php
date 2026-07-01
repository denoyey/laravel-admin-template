@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'readonly' => false,
    'hint' => '',
    'hintClass' => 'text-gray-500',
    'autocomplete' => 'off',
    'rows' => 4,
])

<div>
    <label for="{{ $name }}" class="block text-[12px] sm:text-[13px] font-medium text-gray-700 mb-1">
        {{ $label }}
        @if ($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <div class="relative">
        @if ($type === 'textarea')
            <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}" {{ $required ? 'required' : '' }}
                {{ $readonly ? 'readonly' : '' }}
                class="w-full text-[12px] sm:text-[13px] px-3 py-1.5 sm:py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-hijau/20 focus:border-hijau transition-all placeholder:text-gray-400"
                placeholder="{{ $placeholder }}">{{ old($name, $value) }}</textarea>
        @elseif($type === 'password')
            <input type="password" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}"
                {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }}
                autocomplete="{{ $autocomplete }}"
                class="w-full text-[12px] sm:text-[13px] px-3 py-1.5 sm:py-2 pr-10 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-hijau/20 focus:border-hijau transition-all placeholder:text-gray-400"
                placeholder="{{ $placeholder }}">
            <button type="button"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                onclick="
                    const input = document.getElementById('{{ $name }}');
                    const eyeOpen = this.querySelector('.eye-icon');
                    const eyeClosed = this.querySelector('.eye-slash-icon');
                    if (input.type === 'password') {
                        input.type = 'text';
                        eyeOpen.classList.add('hidden');
                        eyeClosed.classList.remove('hidden');
                    } else {
                        input.type = 'password';
                        eyeOpen.classList.remove('hidden');
                        eyeClosed.classList.add('hidden');
                    }
                ">

                <svg class="h-5 w-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                </svg>

                <svg class="h-5 w-5 eye-slash-icon hidden" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>
            </button>
        @else
            <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
                value="{{ old($name, $value) }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }}
                autocomplete="{{ $autocomplete }}"
                class="w-full text-[12px] sm:text-[13px] px-3 py-1.5 sm:py-2 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-hijau/20 focus:border-hijau transition-all placeholder:text-gray-400"
                placeholder="{{ $placeholder }}">
        @endif
    </div>
    @error($name)
        <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>
    @enderror
    @if ($hint)
        <p class="text-[10px] sm:text-[11px] {{ $hintClass }} mt-1">{{ $hint }}</p>
    @endif
</div>
