@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;

    $currentRoute = Route::currentRouteName();
@endphp

<aside class="sidebar-container">
    <div class="sidebar-header">
        <h2 class="sidebar-logo">RSHP</h2>
        <p class="sidebar-subtitle">DASHBOARD DOKTER</p>
    </div>
    
    <nav class="sidebar-nav">
        <a href="{{ route('dokter.dashboard') }}" 
           class="nav-item-base {{ $currentRoute == 'dokter.dashboard' ? 'nav-item-active' : 'nav-item-default' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            <span class="nav-text">Dashboard</span>
        </a>

        <a href="{{ route('dokter.riwayat-rekam-medis') }}" 
           class="nav-item-base {{ Str::startsWith($currentRoute, 'dokter.riwayat-rekam-medis') ? 'nav-item-active' : 'nav-item-default' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
            <span class="nav-text">Rekam Medis</span>
        </a>

        <a href="{{ route('dokter.jadwal-pemeriksaan') }}" 
           class="nav-item-base {{ $currentRoute == 'dokter.jadwal-pemeriksaan' ? 'nav-item-active' : 'nav-item-default' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span class="nav-text">Jadwal Pemeriksaan</span>
        </a>
    </nav>

    <div class="logout-container">
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="logout-link">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span class="nav-text">Keluar</span>
            </button>
        </form>
    </div>
</aside>

<style>
@import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap");

.sidebar-container {
    width: 256px;
    background-color: #1f2937;
    color: #ffffff;
    min-height: 100vh;
    padding: 1.5rem;
    box-shadow: 4px 0 10px rgba(0, 0, 0, 0.5);
    position: relative;
}

.sidebar-header {
    margin-bottom: 2.5rem;
    border-bottom: 1px solid #374151;
    padding-bottom: 1rem;
}

.sidebar-logo {
    font-size: 1.875rem;
    font-weight: 800;
    color: #818cf8;
    margin: 0;
}

.sidebar-subtitle {
    font-size: 0.875rem;
    color: #9ca3af;
    font-weight: 500;
    letter-spacing: 0.05em;
    margin: 0;
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.nav-item-base {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.75rem;
    transition: background-color 0.2s, color 0.2s;
    text-decoration: none;
}

.nav-item-default {
    color: #d1d5db;
}

.nav-item-default:hover {
    background-color: #374151;
    color: #ffffff;
}

.nav-item-active {
    background-color: #4f46e5;
    color: #ffffff;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
                0 2px 4px -2px rgba(0, 0, 0, 0.06);
}

.logout-container {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 1.5rem;
    border-top: 1px solid #374151;
}

.logout-form {
    margin: 0;
}

.logout-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    border-radius: 0.75rem;
    transition: background-color 0.2s, color 0.2s;
    color: #f87171;
    text-decoration: none;
    background: none;
    border: none;
    cursor: pointer;
    width: 100%;
    text-align: left;
    font-family: inherit;
    font-size: inherit;
}

.logout-link:hover {
    background-color: #374151;
    color: #fca5a5;
}

.nav-icon {
    height: 1.5rem;
    width: 1.5rem;
}

.nav-text {
    font-weight: 500;
}
</style>