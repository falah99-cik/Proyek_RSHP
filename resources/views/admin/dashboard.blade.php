@extends('layouts.admin')

@section('title', 'Dashboard Administrator - RSHP UNAIR')

@push('css')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/dashboard.css') }}">
@endpush

@section('content')
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
                    <span class="card-value">{{ number_format($totalUsers ?? 0) }}</span>
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
                    <span class="card-value">{{ number_format($totalPets ?? 0) }}</span>
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
                    <span class="card-value">{{ number_format($totalMedicalRecords ?? 0) }}</span>
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
                    <span class="card-value">{{ number_format($totalRecentMedicalRecords ?? 0) }}</span>
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
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const rmMonths = @json($monthlyLabels ?? []);
        const rmCounts = @json($monthlyData ?? []);
        const speciesLabels = @json($petSpeciesLabels ?? []);
        const speciesCounts = @json($petSpeciesData ?? []);

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