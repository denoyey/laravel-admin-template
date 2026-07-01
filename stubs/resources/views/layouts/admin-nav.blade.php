@extends('layouts.admin')

@section('content')
    <div class="flex flex-col lg:flex-row gap-6 lg:gap-4 items-start relative flex-1">
        <div
            class="w-full lg:w-[220px] shrink-0 bg-white border border-gray-200 rounded-md shadow-sm overflow-hidden sticky top-[65px] lg:top-[75px] z-10">
            <div class="hidden lg:block p-3.5 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-[13px] font-semibold text-gray-800">Menu Navigasi</h2>
            </div>
            <nav id="admin-subnav"
                class="flex flex-row lg:flex-col p-1.5 lg:p-2 gap-1 lg:space-y-1 overflow-x-auto scrollbar-hide">
                @yield('nav-menu')
            </nav>
        </div>

        <div class="flex-1 w-full min-w-0">
            @yield('main-content')
        </div>
    </div>
@endsection
