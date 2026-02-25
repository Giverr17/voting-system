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
        
       <div class="max-w-7xl mx-auto px-4 py-6">

    <h1 class="text-2xl font-bold mb-4">Voting Audit Table</h1>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

        <div class="bg-blue-600 text-white p-4 rounded-xl shadow">
            <p class="text-sm">Total Votes in System</p>
            <p class="text-2xl font-bold">{{ $totalVotes }}</p>
        </div>

        <div class="bg-green-600 text-white p-4 rounded-xl shadow">
            <p class="text-sm">Calculated From Users</p>
            <p class="text-2xl font-bold">{{ $calculatedTotal }}</p>
        </div>

        <div class="p-4 rounded-xl shadow 
            {{ $calculatedTotal == $totalVotes ? 'bg-green-500' : 'bg-red-500' }} text-white">
            <p class="text-sm">Verification</p>
            <p class="text-xl font-bold">
                {{ $calculatedTotal == $totalVotes ? '✔ MATCHED' : '⚠ MISMATCH' }}
            </p>
        </div>

    </div>

    <!-- Scrollable Table -->
    <div class="overflow-x-auto bg-white shadow rounded-xl">
        <div class="max-h-[500px] overflow-y-auto">

            <table class="min-w-full text-sm text-left text-gray-700">

                <thead class="bg-gray-100 sticky top-0">
                    <tr>
                        <th class="px-4 py-3">Mat No</th>
                        <th class="px-4 py-3">Total Votes</th>
                        <th class="px-4 py-3">Positions Voted</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @foreach($votes as $vote)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">
                                {{ $vote->mat_no }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{ $vote->total_votes_cast }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $vote->positions_voted }}
                            </td>

                            <!-- Scrollable Cell for Candidates -->
                            {{-- <td class="px-4 py-3">
                                <div class="max-w-md overflow-x-auto whitespace-nowrap">
                                    {{ $vote->candidates_voted }}
                                </div>
                            </td> --}}
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>

</div>

    </div>
</body>
</html>