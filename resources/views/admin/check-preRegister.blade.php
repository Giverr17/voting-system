@extends('layouts.content')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-100 to-blue-100 py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2 text-center">Student Pre-Registration</h1>
                <p class="text-gray-600 mb-8 text-center">Edit your registration </p>

                @if (session()->has('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif


                <form action="{{ route('edit-pre-users', $user->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" value="{{ $user->full_name }}" id="username" name="full_name"
                            placeholder="Enter your fullname"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('username') border-red-500 @else border-gray-300 @enderror">
                        @error('full_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="mat_no" class="block text-sm font-medium text-gray-700 mb-2">
                            Mat No <span class="text-red-500">*</span>
                        </label>
                        <input type="text" value="{{ $user->mat_no }}" id="mat_no" name="mat_no"
                            placeholder="Enter Matriculation Number"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        @error('mat_no')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-4 rounded-lg hover:bg-blue-700 transition font-medium text-lg shadow-lg">
                        Confirm </button>
                </form>
            </div>
        </div>
    </div>
@endsection
