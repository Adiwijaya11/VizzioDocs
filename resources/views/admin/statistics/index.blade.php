@extends('admin.layouts.master')

@section('title', 'Statistik')

@section('page_title', 'Statistik')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1>Statistik <span>Platform</span></h1>
        <p>Data real-time dari pengguna dan penggunaan alat VizzioDocs.</p>
    </div>
    <div class="page-header-right">
        <span class="btn btn-outline" onclick="window.location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh
        </span>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-label">Total Pengguna</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon green"><i class="fas fa-user-check"></i></div>
        </div>
        <div class="stat-value">{{ $totalAdmins }}</div>
        <div class="stat-label">Admin</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon orange"><i class="fas fa-crown"></i></div>
        </div>
        <div class="stat-value">{{ $totalPremium }}</div>
        <div class="stat-label">Premium</div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon blue"><i class="fas fa-globe-asia"></i></div>
        </div>
        <div class="stat-value">{{ count($countryStats) }}</div>
        <div class="stat-label">Negara Terdata</div>
    </div>
</div>

{{-- Charts Grid --}}
<div class="chart-grid">
    {{-- User Growth Chart --}}
    <div class="chart-card">
        <h3><i class="fas fa-wave-square"></i> Pertumbuhan Pengguna (30 Hari)</h3>
        <div class="chart-wrapper">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>

    {{-- Role Distribution --}}
    <div class="chart-card">
        <h3><i class="fas fa-pie-chart"></i> Distribusi Role</h3>
        <div class="chart-wrapper">
            <canvas id="roleChart"></canvas>
        </div>
    </div>
</div>

<div class="chart-grid">
    {{-- Plan Distribution --}}
    <div class="chart-card">
        <h3><i class="fas fa-chart-pie"></i> Distribusi Plan</h3>
        <div class="chart-wrapper">
            <canvas id="planChart"></canvas>
        </div>
    </div>

    {{-- Tool Usage --}}
    <div class="chart-card">
        <h3><i class="fas fa-tools"></i> Penggunaan Tools (Terpopuler)</h3>
        <div class="chart-wrapper">
            <canvas id="toolChart"></canvas>
        </div>
    </div>
</div>

{{-- Country Stats --}}
<div class="card" style="margin-bottom: 28px;">
    <div class="card-header">
        <h3><i class="fas fa-map-marker-alt" style="color: var(--accent-light); margin-right: 8px;"></i> Negara Pengguna (Top 10)</h3>
    </div>
    <div class="card-body">
        @if(count($countryStats) > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;">
                @foreach($countryStats as $country => $count)
                    <div class="stat-mini-item">
                        <div class="left">
                            <div class="mini-icon" style="background: rgba(108, 92, 231, 0.15); color: var(--accent-light);">
                                <i class="fas fa-flag"></i>
                            </div>
                            <span class="mini-label">{{ $country ?: 'Unknown' }}</span>
                        </div>
                        <div class="right">{{ $count }}</div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align: center; padding: 30px; color: var(--text-muted);">
                <i class="fas fa-globe" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                Belum ada data negara.
            </div>
        @endif
    </div>
</div>

{{-- Origin Stats --}}
@if(count($originStats) > 0)
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-sign-in-alt" style="color: var(--accent-light); margin-right: 8px;"></i> Asal Pendaftaran (Top 5)</h3>
    </div>
    <div class="card-body">
        <div class="stat-mini-list">
            @foreach($originStats as $origin => $count)
                <div class="stat-mini-item">
                    <div class="left">
                        <div class="mini-icon" style="background: rgba(0, 184, 148, 0.15); color: var(--success);">
                            <i class="fas fa-{{ $origin === 'google' ? 'google' : ($origin === 'github' ? 'github' : 'envelope') }}"></i>
                        </div>
                        <span class="mini-label">{{ ucfirst($origin) }}</span>
                    </div>
                    <div class="right">{{ $count }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = true;
    const textColor = '#a0a3c2';
    const gridColor = 'rgba(42, 45, 82, 0.5)';

    Chart.defaults.color = textColor;
    Chart.defaults.borderColor = gridColor;
    Chart.defaults.font.family = "'Inter', sans-serif";

    // 1. User Growth Chart (Line)
    const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userGrowthCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dates) !!},
            datasets: [{
                label: 'Pendaftaran Baru',
                data: {!! json_encode($userCounts) !!},
                borderColor: '#6c5ce7',
                backgroundColor: 'rgba(108, 92, 231, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#6c5ce7',
                pointBorderColor: '#6c5ce7',
                pointHoverRadius: 6,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: textColor },
                    grid: { color: gridColor }
                },
                x: {
                    ticks: { color: textColor, maxTicksLimit: 10 },
                    grid: { display: false }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // 2. Role Distribution (Doughnut)
    const roleCtx = document.getElementById('roleChart').getContext('2d');
    const roleLabels = {!! json_encode(array_keys($roleStats)) !!};
    const roleValues = {!! json_encode(array_values($roleStats)) !!};
    new Chart(roleCtx, {
        type: 'doughnut',
        data: {
            labels: roleLabels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
            datasets: [{
                data: roleValues,
                backgroundColor: ['#6c5ce7', '#00b894'],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 16, color: textColor }
                }
            },
            cutout: '65%'
        }
    });

    // 3. Plan Distribution (Doughnut)
    const planCtx = document.getElementById('planChart').getContext('2d');
    const planLabels = {!! json_encode(array_keys($planStats)) !!};
    const planValues = {!! json_encode(array_values($planStats)) !!};
    new Chart(planCtx, {
        type: 'doughnut',
        data: {
            labels: planLabels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
            datasets: [{
                data: planValues,
                backgroundColor: ['#fdcb6e', '#636e72'],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 16, color: textColor }
                }
            },
            cutout: '65%'
        }
    });

    // 4. Tool Usage (Bar)
    const toolCtx = document.getElementById('toolChart').getContext('2d');
    const toolLabels = {!! json_encode(array_keys($toolUsage)) !!};
    const toolValues = {!! json_encode(array_values($toolUsage)) !!};
    if (toolLabels.length > 0) {
        new Chart(toolCtx, {
            type: 'bar',
            data: {
                labels: toolLabels,
                datasets: [{
                    label: 'Penggunaan',
                    data: toolValues,
                    backgroundColor: [
                        'rgba(108, 92, 231, 0.7)',
                        'rgba(0, 184, 148, 0.7)',
                        'rgba(253, 203, 110, 0.7)',
                        'rgba(116, 185, 255, 0.7)',
                        'rgba(225, 112, 85, 0.7)',
                        'rgba(253, 121, 168, 0.7)',
                        'rgba(162, 155, 254, 0.7)',
                        'rgba(85, 239, 196, 0.7)',
                        'rgba(255, 234, 167, 0.7)',
                        'rgba(129, 236, 236, 0.7)',
                    ],
                    borderWidth: 0,
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: textColor },
                        grid: { color: gridColor }
                    },
                    y: {
                        ticks: { color: textColor },
                        grid: { display: false }
                    }
                }
            }
        });
    } else {
        document.getElementById('toolChart').parentElement.innerHTML = `
            <div style="text-align: center; padding: 40px; color: var(--text-muted);">
                <i class="fas fa-chart-bar" style="font-size: 28px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                Belum ada data penggunaan tool.
            </div>
        `;
    }
});
</script>
@endpush
