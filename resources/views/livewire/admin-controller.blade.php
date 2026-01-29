<div wire:loading.class="opacity-50" wire:target="userPage,preRegPage">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Pending Approvals -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                        <p class="text-3xl font-bold text-amber-600 mt-2">{{ $countPending }}</p>
                    </div>
                    <div class="bg-amber-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Requires immediate attention</p>
            </div>

            <!-- Approved Voters -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Approved Voters</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $countApproved }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Active registered voters</p>
            </div>

            <!-- Candidates -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Candidates</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ count($candidates) }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Running for positions</p>
            </div>

            <!-- Votes Cast -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Votes Cast</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $countVote }}</p>
                    </div>
                    <div class="bg-indigo-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Total votes recorded</p>
            </div>
        </div>

        @if (session('add-pre-users'))
            <div
                class="flex items-center gap-3 max-w-lg mx-auto mt-6 p-4 
                bg-green-50 border border-green-200 rounded-lg shadow-sm">

                <!-- Plus Icon -->
                <div
                    class="flex items-center justify-center w-8 h-8 
                    bg-green-600 text-white rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                </div>

                <!-- Message -->
                <p class="text-green-800 font-medium">
                    {{ session('add-pre-users') }}
                </p>
            </div>
        @endif


        <!-- Pre-Registered Users Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden" id="pre-users"
            wire:key="prereg-container" wire:loading.class="opacity-50" wire:target="preRegPage,searchReg">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Pre-Registered Users</h2>
                        <p class="text-sm text-gray-600 mt-1">Review and approve pending registrations</p>
                    </div>
                    <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            {{-- Actions --}}
                            <div class="flex items-center gap-3">

                                {{-- Search --}}
                                <input type="text" wire:model.live.debounce.300ms="searchReg"
                                    placeholder="Search by Mat No..."
                                    class="px-4 py-2 border border-gray-300 rounded-lg
                                           focus:ring-2 focus:ring-purple-500 w-56">

                                {{-- Upload CSV --}}
                                <form method="POST" action="{{ route('add-preUsers') }}" enctype="multipart/form-data"
                                    x-data class="relative">
                                    @csrf

                                    {{-- Hidden File Input --}}
                                    <input type="file" name="csv_file" required class="hidden" x-ref="file"
                                        @change="$el.form.submit()">

                                    {{-- Plus Button --}}
                                    <button type="button" @click="$refs.file.click()"
                                        class="inline-flex items-center justify-center
                                                   w-10 h-10 rounded-lg
                                                   bg-blue-600 hover:bg-blue-700
                                                   text-white transition shadow-sm"
                                        title="Upload Pre-Registration CSV">

                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="overflow-x-auto"x>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Matric No</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Name</th>

                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status</th>

                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody wire:key="preReg-tbody" class="bg-white divide-y divide-gray-200">
                        @forelse ($preUsers as $reg)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $reg->mat_no }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"> {{ $reg->full_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $reg->status->color() }}">
                                        {{ $reg->status->label() }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex gap-2">
                                        <a href="{{ route('check-pre-users', $reg->id) }}">
                                            <button
                                                class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Check</button>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <p class="text-gray-500 text-center py-8">No pre-registrations found.</p>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($preUsers->hasPages())
                <div class="px-6 py-4 border-t" wire:key="prereg-links">
                    {{ $preUsers->onEachSide(1)->links(data:['scrollTo'=>'#pre-users']) }}
                </div>
            @endif
        </div>

        {{-- Users Registered --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden"
            wire:loading.class="opacity-50" wire:target="userPage,userSearch">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Registered Users</h2>
                        <p class="text-sm text-gray-600 mt-1">Review and approve registrations</p>
                    </div>
                    <div class="flex gap-3">
                        <input type="text" wire:model.live="userSearch" placeholder="Search by Mat No..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <button
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Matric No</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Name</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Level</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Check</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody wire:key="users-tbody" class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $user->mat_no }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->username }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $user->level }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->preRegistration?->status?->color() }}">
                                        {{ $user->preRegistration?->status?->label() }}
                                    </span>
                                </td>
                                @php
                                    $status = $user->preRegistration?->status;
                                @endphp
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($status === \App\Enums\PreRegistrationStatus::APPROVED)
                                        <button disabled class="bg-green-100 text-green-800 cursor-not-allowed">
                                            ✓ Verified
                                        </button>
                                    @elseif ($status === \App\Enums\PreRegistrationStatus::REJECTED)
                                        <button disabled class="bg-red-100 text-red-800 cursor-not-allowed">
                                            ❌ Rejected
                                        </button>
                                    @elseif($status)
                                        <button wire:click="verifyUsers({{ $user->id }})"
                                            class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                                            Verify User
                                        </button>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if ($status)
                                        <div class="flex gap-2">
                                            <a href="{{ route('edit-users', $user->id) }}"> <button
                                                    class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Check</button>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <p class="text-gray-500 text-center py-8">No User found.</p>
                        @endforelse

                    </tbody>
                </table>
            </div>
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t" wire:key="users-links">
                    {{ $users->onEachSide(1)->links(data:['scrollTo'=>false]) }}
                </div>
            @endif


            <!-- Candidates Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Candidates</h2>
                            <p class="text-sm text-gray-600 mt-1">Manage election candidates and positions</p>
                        </div>
                        <a href="{{ route('add-candidate') }}"> <button
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4">
                                    </path>
                                </svg>
                                Add Candidate
                            </button>
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Position</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Votes</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($candidates as $candidate)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-semibold"> <img
                                                        src="{{ asset('storage/' . $candidate->image) }}"
                                                        alt="{{ $candidate->name }}"
                                                        class="w-15 h-15 rounded-s= object-cover" /></span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $candidate->full_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $candidate->mat_no }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            {{ $candidate->position_applied->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $candidate->vote_count ?? 0 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex gap-2">
                                            <a href="{{ route('edit-candidate', $candidate->id) }}"> <button
                                                    class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Edit</button></a>

                                            <button wire:click="deleteUser({{ $candidate->id }})"
                                                class="px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">Delete</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
    </main>
</div>
