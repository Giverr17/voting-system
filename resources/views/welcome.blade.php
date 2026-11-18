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
    <header class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">

            <!-- Left: Logo + Title -->
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Aces.png') }}" alt="Voting Logo" class="w-10 h-10 object-contain" />
                <h1 class="text-lg font-semibold text-gray-800">
                    Class Election Voting System
                </h1>
            </div>

            <!-- Right: Auth Links -->
            <div class="flex items-center gap-4 sm:gap-6">
                @auth
                    <span class="text-gray-700 text-sm hidden sm:inline">Welcome, <strong>{{ auth()->user()->username }}</strong></span>

                    @if (auth()->user()->role == \App\Enums\Role::ADMIN)
                        <a href="{{ route('admin-index') }}"
                            class="text-gray-700 text-sm font-medium hover:text-blue-600 transition">
                            Admin Dashboard
                        </a>
                    @else
                        <a href="{{ route('vote-index') }}"
                            class="text-gray-700 text-sm font-medium hover:text-blue-600 transition">
                            Vote
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout-auth') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 text-sm font-medium hover:text-red-600 transition">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-700 text-sm font-medium hover:text-blue-600 transition">
                        Login
                    </a>

                    @if (\App\Models\Setting::isRegistrationOpen())
                        <a href="{{ route('register-index') }}"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                            Register
                        </a>
                    @endif
                @endauth
            </div>

        </div>
    </header>

    @if (session()->has('success'))
        <div class="max-w-md mx-auto w-full mt-6 px-4">
            <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium text-center shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-10">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200/80 p-8 sm:p-10 max-w-md w-full text-center">

            <!-- Center Logo -->
            <div class="flex justify-center mb-5">
                <img src="{{ asset('images/Aces.png') }}" alt="Voting Logo" class="w-24 h-24 object-contain drop-shadow-sm" />
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-2 tracking-tight">
                Welcome to the Voting Portal
            </h2>

            <p class="text-gray-500 text-sm leading-relaxed mb-8">
                Cast your vote responsibly. Your voice matters in shaping the future of your class leadership.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                @auth
                    @if (auth()->user()->role == \App\Enums\Role::ADMIN)
                        <a href="{{ route('admin-index') }}"
                            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-xl font-medium transition shadow-sm">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('vote-index') }}"
                            class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-xl font-medium transition shadow-sm">
                            Proceed to Vote
                        </a>
                    @endif

                    <a href="{{ route('check-candidate') }}"
                        class="w-full flex items-center justify-center gap-2 bg-gray-50 hover:bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-medium border border-gray-200 transition">
                        View Candidates
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-xl font-medium transition shadow-sm">
                        Login to Vote
                    </a>

                    <a href="{{ route('check-candidate') }}"
                        class="w-full flex items-center justify-center gap-2 bg-gray-50 hover:bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-medium border border-gray-200 transition">
                        View Candidates
                    </a>

                    @if (\App\Models\Setting::isRegistrationOpen())
                        <div class="pt-4 mt-2 border-t border-gray-100 text-center">
                            <p class="text-sm text-gray-500">
                                Need to register?
                                <a href="{{ route('register-index') }}"
                                    class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition">
                                    Register here
                                </a>
                            </p>
                        </div>
                    @endif
                @endauth
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-sm text-gray-400 py-6">
        © {{ date('Y') }} Class Election Committee
    </footer>

</body>

</html>
