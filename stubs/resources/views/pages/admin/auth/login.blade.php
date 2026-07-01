<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth overscroll-none">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Login Portal Admin Denoyey">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="referrer" content="same-origin">

    <title>Login - Admin Portal</title>

    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>

<body
    class="bg-white flex flex-col items-center h-dvh sm:min-h-screen p-4 sm:overflow-auto overflow-hidden overscroll-none">

    <x-public.layout.loading />

    @if (session('idle_timeout') || request('idle') == '1')
        <div id="toast-container"
            class="fixed top-6 left-1/2 -translate-x-1/2 z-100 flex flex-col gap-3 pointer-events-none items-center">
            <div
                class="toast-alert flex items-start gap-3 p-3.5 bg-white border border-red-100 rounded-md shadow-[0_4px_12px_rgba(0,0,0,0.05)] mb-3 w-max min-w-[280px] sm:min-w-[320px] max-w-[90vw] sm:max-w-md pointer-events-auto transform transition-all duration-300 opacity-0 scale-95 relative overflow-hidden group">
                <div
                    class="shrink-0 w-7 h-7 rounded-full bg-red-50 border border-red-100 flex items-center justify-center mt-0.5 shadow-sm">
                    <svg class="w-4 h-4 text-red-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="flex-1 min-w-0 pr-2">
                    <h4 class="text-[12.5px] font-semibold text-gray-800 leading-snug">Sesi Berakhir</h4>
                    <p class="text-[11px] text-gray-500 mt-0.5 leading-snug">
                        {{ session('idle_timeout') ?? 'Anda sudah tidak aktif. Silahkan login kembali.' }}</p>
                </div>
                <button type="button"
                    class="toast-close shrink-0 p-1 -m-1 text-gray-400 hover:text-gray-600 focus:outline-none rounded-md hover:bg-gray-100 transition-colors">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <div class="grow flex items-center justify-center w-full">
        <div class="w-full max-w-sm bg-white rounded-md border border-gray-200 p-6 md:p-8 shadow-xs">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('src/img/logo-admin.svg') }}" alt="Logo Admin" class="h-12 object-contain"
                    loading="lazy">
            </div>

            <h2 class="text-xs md:text-sm font-semibold text-center text-gray-800 mb-6">
                Login to your account
            </h2>

            @if ($errors->any())
                <div
                    class="mb-4 p-2.5 bg-red-50 text-red-600 text-[10.5px] md:text-[11px] leading-snug font-medium rounded-md border border-red-100 flex items-start gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('admin.login.post') }}" method="POST" class="no-protector">
                @csrf

                <div class="hidden" aria-hidden="true">
                    <label for="__ks">Leave this blank</label>
                    <input type="text" name="__ks" id="__ks" tabindex="-1" autocomplete="off">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-xs font-medium text-gray-700 mb-1">Email address</label>
                    <input type="email" id="email" name="email" placeholder="Email" required
                        autocomplete="email"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-4 focus:ring-hijau/30 focus:border-hijau/80 outline-none transition-all duration-300 text-gray-700 placeholder:text-xs text-xs">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" placeholder="Password" required
                            autocomplete="off"
                            class="w-full px-3 py-2 pr-10 border border-gray-300 rounded-md focus:ring-4 focus:ring-hijau/30 focus:border-hijau/80 outline-none transition-all duration-300 text-gray-700 placeholder:text-xs text-xs">

                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-hijau focus:outline-none transition-colors">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg id="eyeSlashIcon" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 hidden">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-[11px] h-[11px] text-hijau border-gray-300 rounded focus:ring-hijau cursor-pointer accent-hijau">
                    <label for="remember" class="ml-1.5 text-[11px] text-gray-600 cursor-pointer select-none">
                        Remember me
                    </label>
                </div>

                <x-admin.forms.recaptcha />

                <div>
                    <button type="submit"
                        class="w-full bg-hijau hover:bg-hijau-dark text-white font-medium py-2 rounded-md transition-colors duration-300 text-xs shadow-sm">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>

    <footer class="w-full text-center mt-auto">
        <p class="text-[9px] md:text-[10px] text-gray-500 font-medium">
            &copy; {{ date('Y') }} Denoyey. All rights reserved.
        </p>
        <p class="text-[9px] md:text-[10px] text-gray-500 font-medium mt-0.5">
            Developed by
            <a href="https://github.com/denoyey" target="_blank" rel="noopener noreferrer"
                class="text-[#4860BE] font-semibold hover:underline">
                Denoyey
            </a>
        </p>
    </footer>

</body>

</html>
