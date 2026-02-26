<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome | Voting System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">

            <!-- Left: Logo + Title -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Aces.png') }}" alt="Voting Logo" class="w-10 h-10" />
                <h1 class="text-lg font-semibold text-gray-800">
                     Election Voting System
                </h1>
            </div>

            <!-- Right: Auth Links -->
            <div class="flex items-center gap-6">
                @auth
                    <!-- Logged in users -->
                    <span class="text-gray-700">Welcome, {{ auth()->user()->username }}</span>

                    @if (auth()->user()->role == \App\Enums\Role::ADMIN)
                        <a href="{{ route('admin-index') }}"
                            class="text-gray-700 font-medium hover:text-blue-600 transition">
                            Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('vote-index') }}"
                            class="text-gray-700 font-medium hover:text-blue-600 transition">
                            Vote
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout-auth') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 font-medium hover:text-blue-600 transition">
                            Logout
                        </button>
                    </form>
                @else
                    <!-- Guest users -->
                    {{-- <a href="{{ route('login') }}" class="text-gray-700 font-medium hover:text-blue-600 transition">
                        Login
                    </a> --}}

                    {{-- <a href="{{ route('register-index') }}"
                        class="text-gray-700 font-medium hover:text-blue-600 transition">
                        Register
                    </a> --}}
                @endauth
            </div>

        </div>
    </header>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4">
        <div class="bg-white rounded-2xl shadow-lg p-10 max-w-md w-full text-center">

            <!-- Center Logo -->
            <img src="{{ asset('images/Aces.png') }}" alt="Voting Logo" class="w-32 h-32 mx-auto mb-6" />

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                Welcome to the Voting Portal
            </h2>

            <p class="text-gray-600 mb-8">
                Cast your vote responsibly. Your voice matters in shaping the future of
                your class leadership.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                {{-- @auth
                    @if (auth()->user()->role == \App\Enums\Role::ADMIN)
                        <a href="{{ route('admin-index') }}"
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('vote-index') }}"
                            class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition">
                            Proceed to Vote
                        </a>
                    @endif

                    <a href="{{ route('vote-audit') }}"
                        class="block w-full border border-gray-300 hover:bg-gray-100 text-gray-700 py-3 rounded-lg font-medium transition">
                       Check Result
                    </a> --}}
                    {{-- <a href="{{ route('check-candidate') }}"
                        class="block w-full border border-gray-300 hover:bg-gray-100 text-gray-700 py-3 rounded-lg font-medium transition">
                        View Candidates
                    </a> --}}
                {{-- @else
                    <a href="{{ route('login') }}"
                        class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition">
                        Login to Vote
                    </a> --}}

                    {{-- <a href="{{ route('check-candidate') }}"
                        class="block w-full border border-gray-300 hover:bg-gray-100 text-gray-700 py-3 rounded-lg font-medium transition">
                        View Candidates
                    </a> --}}

                    <a href="{{ route('vote-audit') }}"
                        class="block w-full border border-gray-300 hover:bg-gray-100 text-gray-700 py-3 rounded-lg font-medium transition">
                       Check Result
                    </a>
                {{-- @endauth --}}
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-sm text-gray-500 py-6">
        © 2026 Class Election Committee
    </footer>

</body>

</html>
