<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-gray-50 scroll-smooth min-h-screen">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Portal Admin PT Kalpataru Surya Abadi">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="form-cooldown" content="{{ \App\Http\Middleware\PreventSpamSubmit::COOLDOWN_SECONDS * 1000 }}">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="referrer" content="same-origin">
    <meta name="idle-timeout-enabled" content="{{ config('session.idle_timeout_enabled', false) }}">
    <meta name="idle-timeout-minutes" content="{{ config('session.idle_timeout_minutes', 1) }}">

    <title>@yield('title', 'Dashboard') - Portal KSA</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('src/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('src/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('src/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('src/favicon/site.webmanifest') }}">

    @vite(['resources/css/app.css', 'resources/js/admin.js'])
</head>

<body class="min-h-screen bg-gray-50 antialiased flex flex-col">

    <x-public.layout.loading />

    <div id="admin-overlay" class="hidden fixed inset-0 bg-black/50 z-105 lg:hidden">
    </div>

    <x-admin.layout.sidebar />

    <x-admin.layout.topbar />

    <main id="admin-content"
        class="flex-1 pt-[60px] lg:ml-[240px] lg:in-[.sidebar-collapsed]:ml-[64px] transition-all duration-300 ease-in-out flex flex-col">
        <div class="p-4 px-4 py-6 flex-1 flex flex-col">
            <div class="mb-3 sm:mb-6 flex justify-between items-center gap-4">
                <div>
                    <h1 class="text-base sm:text-xl font-medium text-gray-800">
                        @yield('page-title', 'Dashboard')
                    </h1>
                    <p class="text-[11px] sm:text-[13px] text-gray-500 mt-0.5 sm:mt-1">
                        @yield('page-subtitle', 'Selamat datang di Portal KSA')
                    </p>
                </div>
                @hasSection('page-actions')
                    <div class="flex items-center gap-2">
                        @yield('page-actions')
                    </div>
                @endif
            </div>

            @yield('content')
        </div>

        <footer class="px-4 md:px-5 lg:px-6 pb-4 pt-2 mt-auto">
            <p class="text-[10px] text-gray-500 text-center">
                &copy; {{ date('Y') }} PT Kalpataru Surya Abadi. All rights reserved.
            </p>
            <p class="text-[10px] text-gray-500 text-center mt-0.5">
                Developed by
                <a href="https://iexxass.com/" target="_blank" rel="noopener noreferrer"
                    class="text-[#4860BE] font-semibold hover:underline">
                    I'Exxass
                </a>
            </p>
        </footer>
    </main>

    @include('components.admin.ui.alerts')

    <x-admin.modals.logout-modal />
    <x-admin.modals.delete-modal />
    <x-admin.modals.cropper-modal />
    <x-admin.modals.global-search />
    <x-admin.modals.idle-modal />

    @if (session('login_success'))
        <div id="login-success-flag" class="hidden" data-login-success="true"></div>
    @endif

    @stack('scripts')

</body>

</html>
