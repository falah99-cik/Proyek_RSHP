@extends('layouts.admin')

@section('title', 'Dashboard Administrator - RSHP UNAIR')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/dashboard.css') }}">
    <style>
        .card-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .card-shadow:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .chart-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .chart-body-centered {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 300px;
        }
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        @media (max-width: 768px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
<main class="main-content">
    <header class="dashboard-header">
        <h1 class="main-title">Dashboard Administrator</h1>
        <div class="user-greeting">
            Selamat datang, <b>{{ Auth::user()->name ?? 'Administrator' }}</b>!
        </div>
    </header>

    <section class="summary-cards-grid">
        <div class="summary-card card-users card-shadow">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <path d="M20 8v6M23 11h-6"></path>
                </svg>
            </div>
            <div class="card-content">
                <span class="card-value">{{ number_format($total_users) }}</span>
                <p class="card-label">Total Pengguna</p>
            </div>
        </div>

        <div class="summary-card card-pets card-shadow">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 21.5c-4.97 0-9-4.03-9-9s4.03-9 9-9 9 4.03 9 9-4.03 9-9 9z"></path>
                    <path d="M15 9l-6 6M9 9l6 6"></path>
                </svg>
            </div>
            <div class="card-content">
                <span class="card-value">{{ number_format($total_pets) }}</span>
                <p class="card-label">Total Hewan</p>
            </div>
        </div>

        <div class="summary-card card-rm card-shadow">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9"></polyline>
                </svg>
            </div>
            <div class="card-content">
                <span class="card-value">{{ number_format($total_rm) }}</span>
                <p class="card-label">Total Rekam Medis</p>
            </div>
        </div>

        <div class="summary-card card-recent-rm card-shadow">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="card-content">
                <span class="card-value">{{ number_format($total_recent_rm) }}</span>
                <p class="card-label">RM 7 Hari Terakhir</p>
            </div>
        </div>
    </section>

    <div class="main-grid">
        <div class="card chart-card">
            <h3 class="card-title">Rekam Medis 6 Bulan Terakhir</h3>
            <div class="card-body">
                <canvas id="rmChart"></canvas>
            </div>
        </div>

        <div class="card chart-card">
            <h3 class="card-title">Proporsi Hewan Peliharaan</h3>
            <div class="card-body chart-body-centered">
                <canvas id="petSpeciesChart"></canvas>
            </div>
        </div>
    </div>

    @if(count($recent_activities) > 0)
    <div class="mt-8">
        <div class="bg-white rounded-xl shadow border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($recent_activities as $activity)
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $activity->nama_hewan ?? 'Hewan' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $activity->diagnosa ?? 'Rekam medis dibuat' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</main>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const rmMonths = @json($rm_monthly_data->pluck('bulan')->map(fn($b) => \Carbon\Carbon::parse($b.'-01')->format('M Y')));
    const rmCounts = @json($rm_monthly_data->pluck('total'));
    const speciesLabels = @json($pet_species_data->pluck('nama_jenis_hewan'));
    const speciesCounts = @json($pet_species_data->pluck('total_hewan'));

    const rmCtx = document.getElementById('rmChart').getContext('2d');
    const rmChart = new Chart(rmCtx, {
        type: 'bar',
        data: {
            labels: rmMonths,
            datasets: [{
                label: 'Jumlah Rekam Medis',
                data: rmCounts,
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    const petCtx = document.getElementById('petSpeciesChart').getContext('2d');
    const petSpeciesChart = new Chart(petCtx, {
        type: 'doughnut',
        data: {
            labels: speciesLabels,
            datasets: [{
                label: 'Proporsi Hewan',
                data: speciesCounts,
                backgroundColor: ['#818cf8', '#38bdf8', '#10b981', '#f59e0b', '#ef4444', '#9333ea'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush