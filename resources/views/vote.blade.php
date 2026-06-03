{{-- resources/views/vote/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote | Voting System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">
    
    {{-- Header --}}
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/Aces.png') }}" alt="Voting Logo" class="w-10 h-10" />
                <h1 class="text-lg font-semibold text-gray-800">Class Election Voting System</h1>
            </div>

            <div class="flex items-center gap-6">
                <span class="text-gray-700">{{ auth()->user()->name }}</span>
                <a href="{{ route('welcome') }}" class="text-gray-700 hover:text-blue-600">Home</a>
                <form method="POST" action="{{ route('logout-auth') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-700 hover:text-blue-600">Logout</button>
                </form>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="py-8">
        @livewire('voting')
    </main>

    {{-- Footer --}}
    <footer class="text-center text-sm text-gray-500 py-6">
        © 2026 Class Election Committee
    </footer>
@livewireScripts
</body>
</html>


{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-5xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Student Election 2025</h1>
                    <p class="text-sm text-gray-600">John Doe - CSC/2020/001</p>
                </div>
                <form method="POST" action="{{ route('logout-auth') }}" class="inline">
                    @csrf
                    <button type="submit" 
                            class="text-gray-700 font-medium hover:text-blue-600 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 py-8">
        <!-- President -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900">President</h2>
                <p class="text-sm text-gray-600">Select one candidate</p>
            </div>
            <div class="p-6 space-y-4">
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                    <input type="radio" name="president" class="w-4 h-4 text-blue-600">
                    <div class="ml-4 flex-1">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Alice Johnson</p>
                                <p class="text-sm text-gray-600">CSC/2020/001 - Computer Science</p>
                                <p class="text-sm text-gray-500 italic">"Together We Rise"</p>
                            </div>
                        </div>
                    </div>
                </label>

                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                    <input type="radio" name="president" class="w-4 h-4 text-blue-600">
                    <div class="ml-4 flex-1">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Bob Smith</p>
                                <p class="text-sm text-gray-600">ENG/2020/045 - Engineering</p>
                                <p class="text-sm text-gray-500 italic">"Innovation for All"</p>
                            </div>
                        </div>
                    </div>
                </label>

                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                    <input type="radio" name="president" class="w-4 h-4 text-blue-600">
                    <div class="ml-4 flex-1">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Carol Davis</p>
                                <p class="text-sm text-gray-600">BUS/2020/089 - Business Admin</p>
                                <p class="text-sm text-gray-500 italic">"Your Voice, Our Future"</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <div class="border-t border-gray-200 px-6 py-4">
                <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-medium">
                    Cast Vote
                </button>
            </div>
        </div>

        <!-- Vice President -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900">Vice President</h2>
                <p class="text-sm text-gray-600">Select one candidate</p>
            </div>
            <div class="p-6 space-y-4">
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                    <input type="radio" name="vice-president" class="w-4 h-4 text-blue-600">
                    <div class="ml-4 flex-1">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                            <div>
                                <p class="font-semibold text-gray-900">David Wilson</p>
                                <p class="text-sm text-gray-600">LAW/2020/023 - Law</p>
                                <p class="text-sm text-gray-500 italic">"Justice and Unity"</p>
                            </div>
                        </div>
                    </div>
                </label>

                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                    <input type="radio" name="vice-president" class="w-4 h-4 text-blue-600">
                    <div class="ml-4 flex-1">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Emma Brown</p>
                                <p class="text-sm text-gray-600">MED/2020/067 - Medicine</p>
                                <p class="text-sm text-gray-500 italic">"Caring Leadership"</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <div class="border-t border-gray-200 px-6 py-4">
                <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-medium">
                    Cast Vote
                </button>
            </div>
        </div>

        <!-- Secretary General -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-900">Secretary General</h2>
                <p class="text-sm text-gray-600">Select one candidate</p>
            </div>
            <div class="p-6 space-y-4">
                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                    <input type="radio" name="secretary" class="w-4 h-4 text-blue-600">
                    <div class="ml-4 flex-1">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Frank Miller</p>
                                <p class="text-sm text-gray-600">CSC/2020/034 - Computer Science</p>
                                <p class="text-sm text-gray-500 italic">"Organized Excellence"</p>
                            </div>
                        </div>
                    </div>
                </label>

                <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50">
                    <input type="radio" name="secretary" class="w-4 h-4 text-blue-600">
                    <div class="ml-4 flex-1">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-300 rounded-full"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Grace Lee</p>
                                <p class="text-sm text-gray-600">ART/2020/012 - Arts</p>
                                <p class="text-sm text-gray-500 italic">"Creative Solutions"</p>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
            <div class="border-t border-gray-200 px-6 py-4">
                <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-medium">
                    Cast Vote
                </button>
            </div>
        </div>

        <!-- Submit All -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
            <p class="text-green-800 font-semibold mb-4">Ready to submit your votes?</p>
            <button class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold">
                Submit All Votes
            </button>
        </div>
    </main>
</body>
</html> --}}
