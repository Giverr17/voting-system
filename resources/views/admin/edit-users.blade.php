@extends('layouts.content')

@section('content')

    <body class="bg-gray-50 p-8">
        <div class="max-w-6xl mx-auto space-y-8">

            <!-- Edit User Form -->
            <div class="bg-white mt-9 rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="bg-white/20 rounded-full p-3 backdrop-blur-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">Edit User Profile</h2>
                                <p class="text-blue-100 text-sm mt-1">Update user registration details</p>
                            </div>
                        </div>
                        <button class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div>
                    @if (session('success'))
                        <div
                            class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm shadow-sm">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif
                </div>
                <!-- Form Body -->
                <form action="{{ route('edit-users-auth', $user->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')
                    <!-- Personal Information Section -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-blue-100 rounded-lg p-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Username <span class="text-red-500">*</span>
                                </label>
                                <input type="text" value="{{ $user->username }}" name="username"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="Enter full name">
                                @error('username')
                                    <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                                @enderror
                            </div>


                            <!-- Matric Number -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Matric Number <span class="text-red-500">*</span>
                                </label>
                                <input type="text" value="{{ $user->mat_no }}" name="mat_no"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                    placeholder="e.g., ENG2001234">
                                @error('mat_no')
                                    <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <input type="email" value="{{ $user->email }}" name="email"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                        placeholder="example@email.com">
                                    @error('email')
                                        <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Information Section -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="bg-purple-100 rounded-lg p-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Academic Information</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Department -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Department <span class="text-red-500">*</span>
                                </label>
                                <select name="department"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="{{ $user->department }}" selected>Computer Engineering</option>
                                </select>
                                @error('department')
                                    <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Level -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Level <span class="text-red-500">*</span>
                                </label>
                                <select name="level"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option value="">Select a Level</option>
                                    @php
                                        $levels = [100, 200, 300, 400, 500];
                                    @endphp
                                    @foreach ($levels as $level)
                                        <option value="{{ $level }}" {{ $level == $user->level ? 'selected' : '' }}>
                                            {{ $level }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('level')
                                    <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Registration Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                    <option value="{{ $user->preRegistration->status }}">{{ $user->preRegistration->status }}</option>
                                    <option value="{{ \App\Enums\PreRegistrationStatus::APPROVED }}">Approved</option>
                                    <option value="{{ \App\Enums\PreRegistrationStatus::REJECTED }}">Rejected</option>
                                </select>
                                @error('status')
                                    <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                        <a href="{{ route('admin-index') }}"> <button type="button"
                                class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                                Go back
                            </button>
                        </a>
                        <div class="flex gap-3">
                            <button type="submit"
                                class="px-8 py-3 text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition font-medium shadow-lg shadow-blue-500/30">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endsection
