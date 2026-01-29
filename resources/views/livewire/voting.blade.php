{{-- resources/views/livewire/voting-system.blade.php --}}
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Cast Your Vote</h2>
        <p class="text-gray-600">
            Select your preferred candidate for each position and submit your vote.
        </p>

        {{-- Progress Indicator --}}
        <div class="mt-4">
            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>Progress</span>
                <span>{{ count($votedPositions) }} / {{ count($positions) }} positions</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                    style="width: {{ count($positions) > 0 ? (count($votedPositions) / count($positions)) * 100 : 0 }}%">
                </div>
            </div>
        </div>
    </div>

    {{-- Success/Error Message --}}
    @if ($message)
        <div
            class="mb-6 p-4 rounded-lg {{ $messageType === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
            {{ $message }}
        </div>
    @endif

    @if (session()->has('email_success'))
        <div class="mb-6 p-4 rounded-lg bg-green-300 text-black">
            {{ session('email_success') }}
        </div>
    @endif

    {{-- All Positions Voted Message --}}
    @if ($this->hasVotedAll())
        <div class="bg-green-50 border-2 border-green-500 rounded-lg p-8 text-center mb-6">
            <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-2xl font-bold text-green-800 mb-2">Voting Complete!</h3>
            <p class="text-green-700">You have successfully voted for all positions.</p>
        </div>
    @endif

    {{-- Voting Sections by Position --}}
    <div class="space-y-6">
        @foreach ($positions as $position)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">

                {{-- Position Header --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-800">{{ $position->label() }}</h3>

                    @if ($this->hasVotedForPosition($position->value))
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                            ✓ Voted
                        </span>
                    @else
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                            Pending
                        </span>
                    @endif
                </div>

                <div class="p-6">
                    @if ($this->hasVotedForPosition($position->value))
                        <div class="text-center py-12 bg-gray-50 rounded-lg">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-600 text-lg font-medium">You have already voted for this position.</p>
                            <p class="text-gray-500 text-sm mt-2">Thank you for participating! 🎉</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            @foreach ($candidatesByPosition[$position->value] as $candidate)
                                <div wire:click="selectCandidate('{{ $position }}', {{ $candidate->id }})"
                                    class="relative border-2 rounded-lg p-4 cursor-pointer transition-all duration-200
                                        {{ isset($selectedCandidates[$position->value]) && $selectedCandidates[$position->value] == $candidate->id
                                            ? 'border-blue-500 bg-blue-50 shadow-lg'
                                            : 'border-gray-200 hover:border-blue-300 hover:shadow-md' }}">

                                    {{-- Selection Indicator --}}
                                    @if (isset($selectedCandidates[$position->value]) && $selectedCandidates[$position->value] == $candidate->id)
                                        <div class="absolute top-2 right-2 bg-blue-500 text-white rounded-full p-1.5">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Candidate Image (Rounded) --}}
                                    <div class="mb-4">
                                        @if ($candidate->image)
                                            <img src="{{ Storage::url($candidate->image) }}"
                                                alt="{{ $candidate->full_name }}"
                                                class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-gray-200">
                                        @else
                                            <div
                                                class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 mx-auto flex items-center justify-center border-4 border-gray-200">
                                                <svg class="w-12 h-12 text-gray-400" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Candidate Info --}}
                                    <div class="text-center">
                                        {{-- Radio Button --}}
                                        <div class="flex justify-center mb-3">
                                            <div
                                                class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                {{ isset($selectedCandidates[$position->value]) && $selectedCandidates[$position->value] == $candidate->id
                                                    ? 'border-blue-500 bg-blue-500'
                                                    : 'border-gray-300' }}">
                                                @if (isset($selectedCandidates[$position->value]) && $selectedCandidates[$position->value] == $candidate->id)
                                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Name --}}
                                        <h4 class="font-bold text-gray-900 text-base mb-1">
                                            {{ $candidate->full_name }}
                                        </h4>

                                        {{-- Mat No --}}
                                        @if ($candidate->mat_no)
                                            <p class="text-sm text-gray-600 mb-2">
                                                {{ $candidate->mat_no }}
                                            </p>
                                        @endif

                                        {{-- Slogan --}}
                                        @if ($candidate->slogan)
                                            <p class="text-xs text-gray-500 italic line-clamp-2">
                                                "{{ $candidate->slogan }}"
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Submit Button --}}
                        <button wire:click="submitVote('{{ $position->value }}')"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition-colors
                                {{ !isset($selectedCandidates[$position->value]) ? 'opacity-50 cursor-not-allowed' : '' }}"
                            {{ !isset($selectedCandidates[$position->value]) ? 'disabled' : '' }}>
                            Submit Vote for {{ $position->label() }}
                        </button>
                    @endif
                </div>

            </div>
            @endforeach
            <div class="mt-10 flex flex-col items-center justify-center text-center space-y-4">

                @if ($this->votingCompleted)
                    <div
                        class="w-full max-w-md bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-xl font-medium">
                        ✅ Voting completed successfully  
                        <p class="text-sm mt-1">
                            Please check your email to view the live results.
                        </p>
                    </div>
            
                @elseif ($this->hasVotedAll() && $positions)
                    <button
                        wire:click="finalizeVoting"
                        wire:loading.attr="disabled"
                        wire:target="finalizeVoting"
                        class="w-full max-w-md bg-green-600 hover:bg-green-700 disabled:bg-green-400
                               text-white py-3 rounded-xl font-bold transition
                               flex items-center justify-center gap-2"
                    >
                        <span wire:loading.remove wire:target="finalizeVoting">
                            Submit All Votes
                        </span>
            
                        <span wire:loading wire:target="finalizeVoting">
                            Submitting...
                        </span>
                    </button>
            
                @else
                    <div class="w-full max-w-md bg-gray-100 border border-gray-300 text-gray-600 px-6 py-4 rounded-xl">
                        Please vote for all positions to finish.
                    </div>
                @endif
            
            </div>
            
    </div>

</div>
