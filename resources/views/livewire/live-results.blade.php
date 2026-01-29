<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold">Live Vote Results</h2>
    </div>

    <div wire:poll.3s="loadResults">
        @forelse($positions as $position)
            <div class="mb-8 bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-2 capitalize">
                    {{ str_replace('_', ' ', $position) }}
                </h3>
                
                @if(isset($resultsData[$position]))
                    <p class="text-sm text-gray-600 mb-4">
                        <span class="font-bold text-green-600">
                            Leading: {{ $resultsData[$position]['leader'] }}
                        </span>
                        ({{ $resultsData[$position]['leader_votes'] }} votes)
                    </p>
                @endif
                
                <div wire:ignore class="relative" style="height: 250px; max-width: 800px;">
                    <canvas id="chart-{{ $position }}"></canvas>
                </div>
            </div>
        @empty
            <div class="bg-yellow-50 p-4 rounded">
                <p class="text-yellow-800">No positions found in the database.</p>
            </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@script
<script>
    let charts = {};

    $wire.on('chartUpdated', (event) => {
        const data = event.detail ? event.detail[0] : event[0] || event;
        const resultsData = data.resultsData || data;

        if (!resultsData) {
            console.warn('No results data received');
            return;
        }

        Object.keys(resultsData).forEach(position => {
            const { labels, data } = resultsData[position];
            const ctx = document.getElementById(`chart-${position}`);

            if (!ctx) return;

            // ✅ If chart exists, UPDATE it instead of destroying
            if (charts[position]) {
                charts[position].data.labels = labels;
                charts[position].data.datasets[0].data = data;
                charts[position].update('none'); // Update without animation
            } else {
                // ✅ Only create if doesn't exist
                charts[position] = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Votes',
                            data: data,
                            backgroundColor: [
                                '#10b981',
                                '#3b82f6',
                                '#8b5cf6',
                                '#f59e0b',
                                '#ef4444'
                            ],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: false, 
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    });
</script>
@endscript