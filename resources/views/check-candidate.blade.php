<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check Candidates</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Election Candidates</h1>
            <p class="text-gray-600">View all candidates by position</p>
        </div>

        {{-- Candidates by Position --}}
        @forelse ($candidatesByPosition as $position => $candidates)
            <div class="mb-8 bg-white rounded-lg shadow-md overflow-hidden">
                
                {{-- Position Header --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white capitalize">
                        {{ str_replace('_', ' ', $position) }}
                    </h2>
                    <p class="text-blue-100 text-sm mt-1">
                        {{ count($candidates) }} {{ Str::plural('candidate', count($candidates)) }}
                    </p>
                </div>

                {{-- Candidates Grid --}}
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($candidates as $candidate)
                            <div class="border border-gray-200 rounded-lg p-5 hover:shadow-lg transition-shadow duration-200">
                                
                                {{-- Candidate Image --}}
                                <div class="mb-4">
                                    @if ($candidate->image)
                                        <img src="{{ Storage::url($candidate->image) }}"
                                             alt="{{ $candidate->full_name }}"
                                             class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-blue-100">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 mx-auto flex items-center justify-center border-4 border-blue-100">
                                            <svg class="w-12 h-12 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Candidate Info --}}
                                <div class="text-center">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">
                                        {{ $candidate->full_name }}
                                    </h3>

                                    @if ($candidate->mat_no)
                                        <p class="text-sm text-gray-600 mb-3">
                                            {{ $candidate->mat_no }}
                                        </p>
                                    @endif

                                    @if ($candidate->slogan)
                                        <div class="bg-blue-50 rounded-lg p-3 mt-3">
                                            <p class="text-sm text-gray-700 italic">
                                                "{{ $candidate->slogan }}"
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No Candidates Found</h3>
                <p class="text-gray-500">There are currently no candidates registered for any position.</p>
            </div>
        @endforelse

    </div>
</body>
</html>