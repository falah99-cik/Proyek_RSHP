@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Route;

    $user = Auth::user();

    // Nama admin dari database
    $nama_admin = $user->nama ?? 'Administrator';

    // Ambil role utama dari relasi role_user
    $role = $user->roles->first()->nama_role ?? 'Administrator';

    $currentRoute = Route::currentRouteName();
@endphp

<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
                </svg>
            </div>
            <div class="logo-text">
                <h2>RSHP</h2>
                <span>Admin Panel</span>
            </div>
        </div>
    </div>

    <div class="user-profile">
        <div class="avatar-container">
            <div class="avatar">
                <span>{{ strtoupper(substr($nama_admin, 0, 1)) }}</span>
            </div>
            <div class="user-info">
                <p class="user-name">{{ $nama_admin }}</p>

                {{-- Jika role Administrator, tampilkan premium badge --}}
                <p class="user-role">
                    @if($role === 'Administrator')
                        <span class="role-badge admin">Administrator</span>
                    @else
                        <span class="role-badge">{{ $role }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <ul class="sidebar-menu">
        {{-- DASHBOARD --}}
        <li>
            <a href="{{ route('admin.dashboard') }}"
               class="{{ $currentRoute == 'admin.dashboard' ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- MANAJEMEN USER --}}
        <li>
            <a href="{{ route('admin.users.index') }}"
               class="{{ Str::startsWith($currentRoute, 'admin.users') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 21v-8m0 0a4 4 0 110-8m0 8H5a2 2 0 01-2-2V7a2 2 0 012-2h7" />
                </svg>
                <span>Manajemen User</span>
            </a>
        </li>

        {{-- JENIS HEWAN --}}
        <li>
            <a href="{{ route('admin.jenis-hewan.index') }}"
               class="{{ Str::startsWith($currentRoute, 'admin.jenis-hewan') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 21v-8m0 0a4 4 0 110-8m0 8H5a2 2 0 01-2-2V7a2 2 0 012-2h7" />
                </svg>
                <span>Jenis Hewan</span>
            </a>
        </li>

        {{-- RAS HEWAN --}}
        <li>
            <a href="{{ route('admin.ras-hewan.index') }}"
               class="{{ Str::startsWith($currentRoute, 'admin.ras-hewan') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                </svg>
                <span>Ras Hewan</span>
            </a>
        </li>

        {{-- DATA PEMILIK --}}
        <li>
            <a href="{{ route('admin.pemilik.index') }}"
               class="{{ Str::startsWith($currentRoute, 'admin.pemilik') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 21v-8m0 0a4 4 0 110-8m0 8H5a2 2 0 01-2-2V7a2 2 0 012-2h7" />
                </svg>
                <span>Data Pemilik</span>
            </a>
        </li>

        {{-- DATA PET --}}
        <li>
            <a href="{{ route('admin.pet.index') }}"
               class="{{ Str::startsWith($currentRoute, 'admin.pet') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 21v-8m0 0a4 4 0 110-8m0 8H5a2 2 0 01-2-2V7a2 2 0 012-2h7" />
                </svg>
                <span>Data Pet</span>
            </a>
        </li>

        {{-- DATA KATEGORI --}}
        <li>
            <a href="{{ route('admin.kategori.index') }}"
               class="{{ Str::startsWith($currentRoute, 'admin.kategori') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                </svg>
                <span>Data Kategori</span>
            </a>
        </li>

        {{-- KATEGORI KLINIS --}}
        <li>
            <a href="{{ route('admin.kategori-klinis.index') }}"
               class="{{ Str::startsWith($currentRoute, 'admin.kategori-klinis') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                </svg>
                <span>Data Kategori Klinis</span>
            </a>
        </li>

        {{-- KODE TINDAKAN TERAPI --}}
        <li>
            <a href="{{ route('admin.kode-tindakan-terapi.index') }}"
               class="{{ Str::startsWith($currentRoute, 'admin.kode-tindakan-terapi') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                     stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
                </svg>
                <span>Data Kode Tindakan Terapi</span>
            </a>
        </li>
    </ul>

    <div class="logout-btn-container">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn flex items-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            <span>Logout</span>
        </button>
    </form>
</div>
</div>