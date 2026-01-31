@extends('layouts.content')

@section('content')
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Form Header -->
        <div class="bg-gradient-to-r from-blue-600 to-sky-600 px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 rounded-full p-3 backdrop-blur-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Add Candidate</h2>
                        <p class="text-purple-100 text-sm mt-1">Add candidate information and campaign details
                        </p>
                    </div>
                </div>
                <button class="text-white hover:bg-white/20 rounded-lg p-2 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
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
        <form action="{{ route('candidate-auth') }}" method="POST" class="p-8" enctype="multipart/form-data">
            @csrf
            <!-- Profile Photo Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-pink-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Profile Photo</h3>
                </div>

                <div class="flex items-center gap-6">
                    {{-- <div class="relative">
                        <div
                            class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-pink-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                            AB
                        </div>
                        <button type="button"
                            class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow-lg border-2 border-gray-200 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </button>
                    </div> --}}
                    <div>
                        <label
                            class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            Upload New Photo
                            <input type="file" name="image" class="hidden"
                                accept="image/*">
                        </label>
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG or GIF (Max 2MB)</p>
                        @error('image')
                            <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
            </div>

            <!-- Candidate Information Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-purple-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Candidate Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" value="{{ @old('full_name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Enter candidate name">
                        @error('full_name')
                            <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Matric Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Matric Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="mat_no" value="{{ @old('mat_no') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="e.g., ENG2003456">
                        @error('mat_no')
                            <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select name="department"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option selected>Computer Engineering</option>
                        </select>
                    </div>
                    @error('department')
                        <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                    @enderror
                    <!-- Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Level <span class="text-red-500">*</span>
                        </label>
                       
                          <select name="level"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">Select a Level</option>
                            @php
                                $levels = [
                                    200,
                                    300,
                                    400,
                                    500,
                                ];
                            @endphp
                            @foreach ($levels as $level)
                                <option value="{{ $level }}"
                                    {{ old('level') == $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('level')
                        <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <!-- Campaign Details Section -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-indigo-100 rounded-lg p-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Campaign Details</h3>
                </div>

                <div class="space-y-6">
                    <!-- Position -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Position Running For <span class="text-red-500">*</span>
                        </label>
                        <select name="position_applied"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <option value="">Select a position</option>
                          
                            @foreach ($positions as $position)
                                <option value="{{ $position->value }}"
                                    {{ old('position_applied') == $position->value ? 'selected' : '' }}>
                                    {{ $position->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('position_applied')
                        <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                    @enderror
                    <!-- Campaign Slogan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Campaign Slogan
                        </label>
                        <input type="text" name="slogan" value="{{ @old('slogan') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="Enter campaign slogan">
                        @error('slogan')
                            <small class="text-danger text-sm text-red-500">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin-index') }}">
                    <button type="button"
                        class="px-6 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium">
                        Cancel
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
@endsection
