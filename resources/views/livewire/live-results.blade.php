<div>
    {{-- Scoped styles: the captured report uses hex colors only (Tailwind v4 emits
         oklch, which html2canvas cannot parse and would render as black). --}}
    <style>
        .rpt-wrap { max-width: 960px; margin: 0 auto; }
        .rpt-report { background: #ffffff; border-radius: 20px; overflow: hidden;
            box-shadow: 0 10px 30px rgba(2, 6, 23, 0.08); border: 1px solid #e2e8f0; }
        .rpt-header { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 55%, #9333ea 100%);
            color: #ffffff; padding: 32px 32px 26px; }
        .rpt-eyebrow { font-size: 12px; letter-spacing: 2px; text-transform: uppercase;
            font-weight: 700; opacity: 0.85; margin: 0 0 6px; }
        .rpt-title { font-size: 28px; font-weight: 800; margin: 0; line-height: 1.15; }
        .rpt-sub { margin: 6px 0 0; font-size: 14px; opacity: 0.9; }
        .rpt-stats { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px; }
        .rpt-stat { background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 12px; padding: 10px 16px; }
        .rpt-stat b { display: block; font-size: 20px; font-weight: 800; }
        .rpt-stat span { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.9; }

        .rpt-body { padding: 24px 26px 8px; }
        .rpt-card { border: 1px solid #e5e7eb; border-radius: 16px; padding: 18px 20px; margin-bottom: 18px;
            background: #ffffff; }
        .rpt-card-top { display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 14px; gap: 12px; }
        .rpt-pos { font-size: 17px; font-weight: 700; color: #0f172a; text-transform: capitalize; margin: 0; }
        .rpt-count { font-size: 12px; font-weight: 700; color: #4338ca; background: #eef2ff;
            border-radius: 999px; padding: 4px 12px; white-space: nowrap; }

        .rpt-row { display: flex; align-items: center; gap: 12px; padding: 7px 0; }
        .rpt-rank { width: 26px; height: 26px; flex: 0 0 26px; border-radius: 50%; background: #f1f5f9;
            color: #475569; font-size: 12px; font-weight: 700; display: flex; align-items: center;
            justify-content: center; }
        .rpt-main { flex: 1 1 auto; min-width: 0; }
        .rpt-name-line { display: flex; align-items: center; justify-content: space-between; gap: 10px;
            margin-bottom: 5px; }
        .rpt-name { font-size: 14px; font-weight: 600; color: #1e293b; white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis; }
        .rpt-crown { color: #b45309; font-size: 12px; font-weight: 700; }
        .rpt-votes { font-size: 13px; font-weight: 700; color: #0f172a; white-space: nowrap; }
        .rpt-track { height: 12px; background: #eef2f7; border-radius: 999px; overflow: hidden; }
        .rpt-fill { height: 100%; border-radius: 999px; min-width: 2px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6); }
        .rpt-fill.is-leader { background: linear-gradient(90deg, #059669, #10b981); }
        .rpt-empty { color: #94a3b8; font-size: 13px; padding: 6px 0 10px; }

        .rpt-footer { padding: 16px 26px 24px; border-top: 1px solid #eef2f7; color: #64748b;
            font-size: 12px; display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
    </style>

    {{-- Toolbar — excluded from the downloaded image --}}
    <div class="rpt-wrap" style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; flex-wrap:wrap;"
        data-html2canvas-ignore="true">
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="width:9px; height:9px; border-radius:50%; background:#10b981; display:inline-block; animation: rptPulse 1.4s infinite;"></span>
            <span style="font-weight:700; color:#0f172a;">Live Results</span>
            <span style="color:#64748b; font-size:13px;">· updates every 3s</span>
        </div>
        <div style="display:flex; align-items:center; gap:10px;">
            <a href="{{ route('admin-index') }}"
                style="font-size:13px; font-weight:600; color:#475569; text-decoration:none; padding:9px 14px; border:1px solid #cbd5e1; border-radius:10px;">
                ← Dashboard
            </a>
            <button type="button" onclick="window.downloadResults(this)"
                style="font-size:13px; font-weight:700; color:#ffffff; background:#4f46e5; border:none; cursor:pointer; padding:10px 16px; border-radius:10px;">
                ⬇ Download as Image
            </button>
        </div>
    </div>
    <style>@keyframes rptPulse { 0%,100% { opacity:1; } 50% { opacity:0.35; } }</style>

    {{-- The report that gets captured --}}
    <div class="rpt-wrap" wire:poll.3s="loadResults">
        <div class="rpt-report" id="results-report">
            <div class="rpt-header">
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:14px;">
                    <span style="background:#ffffff; border-radius:12px; padding:8px 12px; display:inline-flex; align-items:center;">
                        <img src="{{ asset('images/spe-logo.png') }}" alt="SPE International Logo"
                            style="height:34px; width:auto; display:block;" crossorigin="anonymous">
                    </span>
                    <p class="rpt-eyebrow" style="margin:0;">Official Tally</p>
                </div>
                <h1 class="rpt-title">Class Election — Live Results</h1>
                <p class="rpt-sub">Generated {{ now()->format('M j, Y · g:i A') }}</p>
                <div class="rpt-stats">
                    <div class="rpt-stat"><b>{{ $totalVotes }}</b><span>Total Votes</span></div>
                    <div class="rpt-stat"><b>{{ count($positions) }}</b><span>Positions</span></div>
                </div>
            </div>

            <div class="rpt-body">
                @forelse ($positions as $position)
                    @php $pd = $resultsData[$position] ?? ['candidates' => [], 'total' => 0]; @endphp
                    <div class="rpt-card">
                        <div class="rpt-card-top">
                            <h3 class="rpt-pos">{{ str_replace('_', ' ', $position) }}</h3>
                            <span class="rpt-count">{{ $pd['total'] }} {{ \Illuminate\Support\Str::plural('vote', $pd['total']) }}</span>
                        </div>

                        @forelse ($pd['candidates'] as $i => $c)
                            <div class="rpt-row">
                                <div class="rpt-rank">{{ $i + 1 }}</div>
                                <div class="rpt-main">
                                    <div class="rpt-name-line">
                                        <span class="rpt-name">
                                            {{ $c['name'] }}
                                            @if ($i === 0 && $c['votes'] > 0)
                                                <span class="rpt-crown">👑 Leading</span>
                                            @endif
                                        </span>
                                        <span class="rpt-votes">{{ $c['votes'] }} ({{ $c['share'] }}%)</span>
                                    </div>
                                    <div class="rpt-track">
                                        <div class="rpt-fill {{ $i === 0 && $c['votes'] > 0 ? 'is-leader' : '' }}"
                                            style="width: {{ $c['width'] }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rpt-empty">No votes cast for this position yet.</div>
                        @endforelse
                    </div>
                @empty
                    <div class="rpt-empty" style="text-align:center; padding:30px 0;">
                        No positions with candidates found.
                    </div>
                @endforelse
            </div>

            <div class="rpt-footer">
                <span>Class Election Committee</span>
                <span>Snapshot: {{ now()->format('M j, Y g:i A') }}</span>
            </div>
        </div>
    </div>

    @assets
        {{-- html2canvas-pro supports oklch/lab/lch colors (Tailwind v4 emits oklch);
             the original html2canvas 1.4.1 throws on them. Exposes the same global. --}}
        <script src="https://cdn.jsdelivr.net/npm/html2canvas-pro@1.5.8/dist/html2canvas-pro.min.js"></script>
    @endassets

    @script
        <script>
            window.downloadResults = function (btn) {
                const el = document.getElementById('results-report');
                if (!el || typeof html2canvas === 'undefined') return;

                const original = btn ? btn.innerHTML : null;
                if (btn) { btn.disabled = true; btn.innerHTML = 'Preparing…'; }

                html2canvas(el, { scale: 2, backgroundColor: '#ffffff', useCORS: true })
                    .then(canvas => {
                        const link = document.createElement('a');
                        link.download = 'election-results-' + new Date().toISOString().slice(0, 10) + '.png';
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                    })
                    .finally(() => {
                        if (btn) { btn.disabled = false; btn.innerHTML = original; }
                    });
            };
        </script>
    @endscript
</div>
