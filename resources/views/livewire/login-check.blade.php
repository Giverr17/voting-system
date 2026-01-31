<div class="min-h-screen bg-gradient-to-br from-blue-100 to-black-100 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2 text-center">Student Registration</h1>
            <p class="text-gray-600 mb-8 text-center">Complete your registration to access the voting system</p>

            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Step 1: Matriculation Number Verification -->
            @if (!$isVerified)
                <div class="space-y-6">
                    <div>
                        <label for="mat_no" class="block text-sm font-medium text-gray-700 mb-2">
                            Matriculation Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="mat_no" wire:model.live="mat_no" wire:keydown.enter="checkMatNo"
                            placeholder="Enter your matriculation number"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('mat_no') border-red-500 @else border-gray-300 @enderror"
                            required>
                        @error('mat_no')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($verificationMessage)
                        <div
                            class="p-4 rounded-lg {{ $isVerified ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $verificationMessage }}
                        </div>
                    @endif

                    <button wire:click="checkMatNo"
                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition">                        Verify Matriculation Number
                    </button>
                </div>

                <!-- Step 2: Registration Form (Shown after verification) -->
            @else
                <form wire:submit.prevent="register" class="space-y-6">
                    <!-- Success Message -->
                    <div
                        class="p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between">
                        <span>✓ Mat No: <strong>{{ $mat_no }}</strong> | Name:
                            <strong>{{ $name }}</strong> - Verified</span>
                        <button type="button" wire:click="resetVerification"
                            class="text-sm underline hover:no-underline">
                            Change
                        </button>
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="username" wire:model.live="username"
                            placeholder="Enter your username"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('username') border-red-500 @else border-gray-300 @enderror"
                            required>
                        @error('username')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" wire:model.live="email" placeholder="example@email.com"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @else border-gray-300 @enderror"
                            required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Department -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                            Department <span class="text-red-500">*</span>
                        </label>
                        <select id="department" wire:model.live="department"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('department') border-red-500 @else border-gray-300 @enderror"
                            required>
                            <option value="">Select your department</option>

                            <option value="{{ $departments }}">{{ $departments }}</option>

                        </select>
                        @error('department')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Level -->
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                            Level <span class="text-red-500">*</span>
                        </label>
                        <select id="level" wire:model.live="level"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('level') border-red-500 @else border-gray-300 @enderror"
                            required>
                            <option value="">Select your level</option>
                            @foreach ($levels as $lvl)
                                <option value="{{ $lvl }}">{{ $lvl }} Level</option>
                            @endforeach
                        </select>
                        @error('level')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                    class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition">                        Complete Registration
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
