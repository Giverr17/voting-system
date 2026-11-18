<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Dashboard - Voting System</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 sm:py-4">
            <div class="flex items-center justify-between gap-3">

                <!-- Left: Logo + Title -->
                <div class="flex items-center gap-2.5 sm:gap-3 shrink-0 min-w-0">
                    <img src="{{ asset('images/Aces.png') }}" alt="Voting Logo" class="h-8 sm:h-10 w-auto object-contain shrink-0">
                    <div class="min-w-0">
                        <h1 class="text-base sm:text-xl font-bold text-gray-900 tracking-tight truncate leading-tight">
                            Admin Dashboard
                        </h1>
                        <p class="text-xs text-gray-500 hidden sm:block truncate mt-0.5">
                            Manage voting system and voter records
                        </p>
                    </div>
                </div>

                <!-- Right: Nav actions -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Home / Public portal link -->
                    <a href="{{ route('welcome') }}"
                        class="p-1.5 sm:p-2 text-gray-500 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition"
                        title="View Public Welcome Page">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </a>

                    <!-- Live Results link -->
                    <a href="{{ route('admin-results') }}"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Live Results</span>
                    </a>

                    <!-- Admin greeting (desktop only) -->
                    <span class="text-xs sm:text-sm text-gray-700 hidden md:inline">
                        Welcome, <strong>{{ auth()->user()->username ?? 'Admin' }}</strong>
                    </span>

                    <!-- Logout -->
                    <form action="{{ route('logout-auth') }}" method="post" class="inline">
                        @csrf
                        <button type="submit"
                            class="px-3 py-1.5 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition shadow-sm whitespace-nowrap">
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </header>

    @yield('content')


    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-sm text-gray-600">&copy; 2026 Voting System. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
