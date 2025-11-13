@extends('layouts.app')

@section('title', 'Struktur Organisasi | RSHP Universitas Airlangga')

@section('content')
<section class="hero" id="home" role="banner" aria-label="Struktur Organisasi RSHP">
    <div>
        <h1>Struktur Organisasi</h1>
        <p>Memastikan setiap peran berjalan sesuai fungsinya dan memberikan layanan terbaik.</p>
    </div>
</section>

<main class="container" role="main">
    <h2 class="section-title text-white text-center mb-8">
        Tim Inti Manajemen RSHP
    </h2>

    <div class="staff-container">
        <div class="staff-member-card">
            <div class="person-photo">
                <img src="{{ asset('assets/images/pas foto 3x4 Ahmad Mathlaul Falah.jpg') }}" alt="Foto Direktur" />
            </div>
            <div class="person-name">Drh. Ahmad Mathlaul Falah, S.Tr.TI</div>
            <div class="person-title">Direktur</div>
        </div>

        <div class="staff-member-card">
            <div class="person-photo">
                <img src="{{ asset('assets/images/Gambar WhatsApp 2025-04-22 pukul 22.13.43_7018da19-removebg-preview.jpg') }}" alt="Foto Wakil Direktur" />
            </div>
            <div class="person-name">Drh. Ayuni Syazana, S.Ap</div>
            <div class="person-title">Wakil Direktur</div>
        </div>

        <div class="staff-member-card">
            <div class="person-photo">
                <img src="{{ asset('assets/images/a22725aed8f8ef9a3cdf43720605c6e1.jpg') }}" alt="Foto Kepala Layanan" />
            </div>
            <div class="person-name">Drh. Budi Santoso</div>
            <div class="person-title">Kepala Layanan</div>
        </div>
    </div>
</main>
@endsection
