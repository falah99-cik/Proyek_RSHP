<nav class="navbar" role="navigation" aria-label="Main navigation">
    <img 
        src="https://rshp.unair.ac.id/wp-content/uploads/2024/06/UNIVERSITAS-AIRLANGGA-scaled.webp" 
        alt="Logo Universitas Airlangga"
    />

    <ul>
        <li class="{{ request()->is('/') ? 'active' : '' }}">
            <a href="{{ route('home') }}" aria-current="{{ request()->is('/') ? 'page' : '' }}">Home</a>
        </li>

        <li class="{{ request()->is('struktur-organisasi') ? 'active' : '' }}">
            <a href="{{ route('struktur.organisasi') }}" aria-current="{{ request()->is('struktur-organisasi') ? 'page' : '' }}">
                Struktur Organisasi
            </a>
        </li>

        <li class="{{ request()->is('layanan') ? 'active' : '' }}">
            <a href="{{ route('layanan') }}" aria-current="{{ request()->is('layanan') ? 'page' : '' }}">
                Layanan Umum
            </a>
        </li>

        <li class="{{ request()->is('visi-misi') ? 'active' : '' }}">
            <a href="{{ route('visi.misi') }}" aria-current="{{ request()->is('visi-misi') ? 'page' : '' }}">
                Visi, Misi & Tujuan
            </a>
        </li>

        <li>
            <a href="{{ route('login.process') }}" class="login">Login</a>
        </li>
    </ul>
</nav>