<div>
    {{-- Scoped styles for the results report. --}}
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

    {{-- Toolbar --}}
    <div class="rpt-wrap" style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:16px; flex-wrap:wrap;">
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
            <button type="button" id="download-report-btn"
                style="font-size:13px; font-weight:700; color:#ffffff; background:#4f46e5; border:none; cursor:pointer; padding:10px 16px; border-radius:10px;">
                ⬇ Download as Image
            </button>
        </div>
    </div>
    <style>@keyframes rptPulse { 0%,100% { opacity:1; } 50% { opacity:0.35; } }</style>

    {{-- The live report --}}
    <div class="rpt-wrap" wire:poll.3s="loadResults">
        <div class="rpt-report" id="results-report">
            <div class="rpt-header">
                <div style="display:flex; align-items:center; gap:14px; margin-bottom:14px;">
                    <span style="background:#ffffff; border-radius:12px; padding:8px 12px; display:inline-flex; align-items:center;">
                        <img src="{{ asset('images/spe-logo.png') }}" alt="SPE International Logo"
                            style="height:34px; width:auto; display:block;">
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
</div>

{{-- Custom Canvas Report Generator — placed OUTSIDE the Livewire component div
     so that Livewire's @script caching cannot interfere. --}}
<script>
(function () {
    'use strict';
    console.log('[SPE Report v3] Custom canvas report generator loaded.');

    var btn = document.getElementById('download-report-btn');
    if (!btn) return;

    btn.addEventListener('click', async function () {
        console.log('[SPE Report v3] Download button clicked — generating CUSTOM canvas image (NOT a screenshot).');

        var reportEl = document.getElementById('results-report');
        if (!reportEl) { alert('Report element not found.'); return; }

        var origHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '⏳ Drawing custom report…';

        try {
            /* ─── 1. Scrape data from the live DOM ─── */
            var positions = [];
            reportEl.querySelectorAll('.rpt-card').forEach(function (card) {
                var posName = (card.querySelector('.rpt-pos') || {}).textContent || 'Unknown';
                posName = posName.trim();
                var voteCount = (card.querySelector('.rpt-count') || {}).textContent || '0 votes';
                voteCount = voteCount.trim();
                var candidates = [];
                card.querySelectorAll('.rpt-row').forEach(function (row, i) {
                    var nameEl = row.querySelector('.rpt-name');
                    var name = nameEl ? (nameEl.childNodes[0] || {}).textContent || '' : '';
                    name = name.trim();
                    var isLeader = !!row.querySelector('.rpt-crown');
                    var votesText = (row.querySelector('.rpt-votes') || {}).textContent || '0 (0%)';
                    votesText = votesText.trim();
                    var fillEl = row.querySelector('.rpt-fill');
                    var widthPct = fillEl ? parseFloat(fillEl.style.width) || 0 : 0;
                    candidates.push({ rank: i + 1, name: name, votesText: votesText, widthPct: widthPct, isLeader: isLeader });
                });
                var emptyEl = card.querySelector('.rpt-empty');
                var emptyMsg = emptyEl ? emptyEl.textContent.trim() : null;
                positions.push({ posName: posName, voteCount: voteCount, candidates: candidates, emptyMsg: emptyMsg });
            });

            var statEls = reportEl.querySelectorAll('.rpt-stat b');
            var totalVotes = statEls[0] ? statEls[0].textContent.trim() : '0';
            var totalPositions = statEls[1] ? statEls[1].textContent.trim() : '0';

            console.log('[SPE Report v3] Scraped ' + positions.length + ' positions, ' + totalVotes + ' total votes.');

            /* ─── 2. Load logo ─── */
            var logoImg = await new Promise(function (resolve) {
                var img = new Image();
                img.onload = function () { resolve(img); };
                img.onerror = function () { console.warn('[SPE Report v3] Logo failed to load'); resolve(null); };
                img.src = '{{ asset("images/spe-logo.png") }}';
            });

            /* ─── 3. Layout constants ─── */
            var W = 1200;
            var PAGE_MAX = 1700;
            var PAD = 40;
            var HDR_H = 240;
            var FTR_H = 70;
            var CARD_GAP = 20;
            var ROW_H = 52;
            var CARD_TITLE_H = 52;
            var CARD_EMPTY_H = 42;
            var CARD_VPAD = 22;
            var CW = W - PAD * 2;

            function cardH(pos) {
                var body = pos.candidates.length > 0 ? pos.candidates.length * ROW_H : CARD_EMPTY_H;
                return CARD_TITLE_H + body + CARD_VPAD;
            }

            /* ─── 4. Paginate ─── */
            var pages = [];
            var curPage = [];
            var usedH = HDR_H + 30;
            var availH = PAGE_MAX - FTR_H - 20;
            for (var i = 0; i < positions.length; i++) {
                var h = cardH(positions[i]) + CARD_GAP;
                if (usedH + h > availH && curPage.length > 0) {
                    pages.push(curPage);
                    curPage = [];
                    usedH = HDR_H + 30;
                }
                curPage.push(positions[i]);
                usedH += h;
            }
            if (curPage.length > 0) pages.push(curPage);
            if (pages.length === 0) pages.push([]);

            console.log('[SPE Report v3] Paginated into ' + pages.length + ' page(s).');

            /* ─── 5. Drawing helpers ─── */
            function rr(ctx, x, y, w, h, r) {
                ctx.beginPath();
                ctx.moveTo(x + r, y);
                ctx.lineTo(x + w - r, y);
                ctx.quadraticCurveTo(x + w, y, x + w, y + r);
                ctx.lineTo(x + w, y + h - r);
                ctx.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
                ctx.lineTo(x + r, y + h);
                ctx.quadraticCurveTo(x, y + h, x, y + h - r);
                ctx.lineTo(x, y + r);
                ctx.quadraticCurveTo(x, y, x + r, y);
                ctx.closePath();
            }

            function drawHeader(ctx, pNum, pTotal) {
                /* Purple gradient — identical to the website header */
                var grad = ctx.createLinearGradient(0, 0, W, HDR_H);
                grad.addColorStop(0, '#4f46e5');
                grad.addColorStop(0.55, '#7c3aed');
                grad.addColorStop(1, '#9333ea');
                rr(ctx, PAD, 16, CW, HDR_H - 20, 20);
                ctx.fillStyle = grad;
                ctx.fill();

                var y = 42;

                /* Logo in white pill */
                if (logoImg) {
                    rr(ctx, PAD + 24, y, 62, 46, 12);
                    ctx.fillStyle = '#ffffff';
                    ctx.fill();
                    var lh = 32;
                    var lw = logoImg.width * (lh / logoImg.height);
                    ctx.drawImage(logoImg, PAD + 24 + (62 - lw) / 2, y + 7, lw, lh);
                }

                /* Eyebrow */
                ctx.fillStyle = 'rgba(255,255,255,0.85)';
                ctx.font = '700 12px Inter, system-ui, sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText('OFFICIAL TALLY', PAD + 96, y + 32);

                y += 62;

                /* Title */
                ctx.fillStyle = '#ffffff';
                ctx.font = '800 30px Inter, system-ui, sans-serif';
                ctx.fillText('Class Election — Live Results', PAD + 26, y);

                /* Date */
                y += 26;
                ctx.font = '400 14px Inter, system-ui, sans-serif';
                ctx.fillStyle = 'rgba(255,255,255,0.9)';
                var ds = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                    + ' · ' + new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                ctx.fillText('Generated ' + ds, PAD + 26, y);

                /* Page indicator */
                if (pTotal > 1) {
                    ctx.font = '700 13px Inter, system-ui, sans-serif';
                    ctx.fillStyle = 'rgba(255,255,255,0.8)';
                    ctx.textAlign = 'right';
                    ctx.fillText('Page ' + pNum + ' of ' + pTotal, PAD + CW - 26, y);
                    ctx.textAlign = 'left';
                }

                /* Stats pills */
                y += 30;
                var pills = [
                    { v: totalVotes, l: 'TOTAL VOTES' },
                    { v: totalPositions, l: 'POSITIONS' },
                ];
                var px = PAD + 26;
                for (var pi = 0; pi < pills.length; pi++) {
                    rr(ctx, px, y, 140, 50, 12);
                    ctx.fillStyle = 'rgba(255,255,255,0.15)';
                    ctx.fill();
                    ctx.strokeStyle = 'rgba(255,255,255,0.25)';
                    ctx.lineWidth = 1;
                    ctx.stroke();
                    ctx.fillStyle = '#ffffff';
                    ctx.font = '800 22px Inter, system-ui, sans-serif';
                    ctx.fillText(pills[pi].v, px + 16, y + 26);
                    ctx.font = '700 10px Inter, system-ui, sans-serif';
                    ctx.fillStyle = 'rgba(255,255,255,0.9)';
                    ctx.fillText(pills[pi].l, px + 16, y + 42);
                    px += 156;
                }
            }

            function drawFooter(ctx, pageH, pNum, pTotal) {
                var fy = pageH - FTR_H;
                ctx.fillStyle = '#eef2f7';
                ctx.fillRect(PAD, fy, CW, 1);
                ctx.fillStyle = '#64748b';
                ctx.font = '400 12px Inter, system-ui, sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText('Class Election Committee', PAD + 10, fy + 30);
                var snap = 'Snapshot: ' + new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
                    + ' ' + new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                ctx.textAlign = 'right';
                ctx.fillText(snap, PAD + CW - 10, fy + 30);
                if (pTotal > 1) ctx.fillText('Page ' + pNum + '/' + pTotal, PAD + CW - 10, fy + 50);
                ctx.textAlign = 'left';
            }

            function drawCard(ctx, pos, x, y, w) {
                var ch = cardH(pos);

                /* Shadow */
                rr(ctx, x + 2, y + 2, w, ch, 16);
                ctx.fillStyle = 'rgba(0,0,0,0.03)';
                ctx.fill();

                /* Card background */
                rr(ctx, x, y, w, ch, 16);
                ctx.fillStyle = '#ffffff';
                ctx.fill();
                ctx.strokeStyle = '#e5e7eb';
                ctx.lineWidth = 1;
                ctx.stroke();

                /* Position title */
                ctx.fillStyle = '#0f172a';
                ctx.font = '700 17px Inter, system-ui, sans-serif';
                ctx.textAlign = 'left';
                ctx.fillText(pos.posName, x + 20, y + 34);

                /* Vote count pill */
                ctx.font = '700 12px Inter, system-ui, sans-serif';
                var vcW = ctx.measureText(pos.voteCount).width + 24;
                rr(ctx, x + w - vcW - 20, y + 18, vcW, 26, 13);
                ctx.fillStyle = '#eef2ff';
                ctx.fill();
                ctx.fillStyle = '#4338ca';
                ctx.fillText(pos.voteCount, x + w - vcW - 20 + 12, y + 35);

                var ry = y + CARD_TITLE_H;

                if (pos.candidates.length === 0) {
                    ctx.fillStyle = '#94a3b8';
                    ctx.font = '400 13px Inter, system-ui, sans-serif';
                    ctx.fillText(pos.emptyMsg || 'No votes cast yet.', x + 20, ry + 24);
                    return;
                }

                for (var ci = 0; ci < pos.candidates.length; ci++) {
                    var c = pos.candidates[ci];
                    var cy = ry + ci * ROW_H;

                    /* Rank circle */
                    ctx.beginPath();
                    ctx.arc(x + 34, cy + 22, 13, 0, Math.PI * 2);
                    ctx.fillStyle = c.isLeader ? '#eef2ff' : '#f1f5f9';
                    ctx.fill();
                    ctx.fillStyle = c.isLeader ? '#4f46e5' : '#475569';
                    ctx.font = '700 12px Inter, system-ui, sans-serif';
                    var rt = '' + c.rank;
                    ctx.textAlign = 'center';
                    ctx.fillText(rt, x + 34, cy + 26);
                    ctx.textAlign = 'left';

                    /* Name */
                    ctx.fillStyle = '#1e293b';
                    ctx.font = '600 14px Inter, system-ui, sans-serif';
                    ctx.fillText(c.name, x + 58, cy + 22);

                    /* Leader badge */
                    if (c.isLeader) {
                        var nw = ctx.measureText(c.name).width;
                        ctx.fillStyle = '#059669';
                        ctx.font = '700 11px Inter, system-ui, sans-serif';
                        ctx.fillText('★ Leading', x + 58 + nw + 8, cy + 22);
                    }

                    /* Votes (right) */
                    ctx.textAlign = 'right';
                    ctx.fillStyle = '#0f172a';
                    ctx.font = '700 13px Inter, system-ui, sans-serif';
                    ctx.fillText(c.votesText, x + w - 20, cy + 22);
                    ctx.textAlign = 'left';

                    /* Progress bar */
                    var bx = x + 58, bw = w - 78, by = cy + 32, bh = 12;
                    rr(ctx, bx, by, bw, bh, 6);
                    ctx.fillStyle = '#eef2f7';
                    ctx.fill();

                    if (c.widthPct > 0) {
                        var fw = Math.max(4, bw * c.widthPct / 100);
                        rr(ctx, bx, by, fw, bh, 6);
                        var bg = ctx.createLinearGradient(bx, 0, bx + fw, 0);
                        if (c.isLeader) {
                            bg.addColorStop(0, '#059669');
                            bg.addColorStop(1, '#10b981');
                        } else {
                            bg.addColorStop(0, '#6366f1');
                            bg.addColorStop(1, '#8b5cf6');
                        }
                        ctx.fillStyle = bg;
                        ctx.fill();
                    }
                }
            }

            /* ─── 6. Render each page to canvas and download ─── */
            for (var p = 0; p < pages.length; p++) {
                var pp = pages[p];
                var contentH = HDR_H + 30;
                for (var j = 0; j < pp.length; j++) contentH += cardH(pp[j]) + CARD_GAP;
                contentH += FTR_H + 20;
                var actualH = Math.min(contentH, PAGE_MAX);

                var canvas = document.createElement('canvas');
                canvas.width = W * 2;
                canvas.height = actualH * 2;
                var ctx = canvas.getContext('2d');
                ctx.scale(2, 2);

                /* Light background */
                ctx.fillStyle = '#f8fafc';
                ctx.fillRect(0, 0, W, actualH);

                /* Outer card shadow */
                rr(ctx, PAD - 2, 14, CW + 4, actualH - 28, 22);
                ctx.fillStyle = 'rgba(2, 6, 23, 0.04)';
                ctx.fill();

                drawHeader(ctx, p + 1, pages.length);
                drawFooter(ctx, actualH, p + 1, pages.length);

                var cardY = HDR_H + 24;
                for (var k = 0; k < pp.length; k++) {
                    drawCard(ctx, pp[k], PAD + 14, cardY, CW - 28);
                    cardY += cardH(pp[k]) + CARD_GAP;
                }

                /* Trigger download */
                var slug = new Date().toISOString().slice(0, 10);
                var sfx = pages.length > 1 ? '-page' + (p + 1) : '';
                var link = document.createElement('a');
                link.download = 'spe-election-results-' + slug + sfx + '.png';
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                console.log('[SPE Report v3] Downloaded page ' + (p + 1) + ' of ' + pages.length);

                if (pages.length > 1 && p < pages.length - 1) {
                    await new Promise(function (r) { setTimeout(r, 600); });
                }
            }

            console.log('[SPE Report v3] All pages downloaded successfully.');

        } catch (err) {
            console.error('[SPE Report v3] Report generation failed:', err);
            alert('Report generation failed: ' + err.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = origHTML;
        }
    });
})();
</script>
