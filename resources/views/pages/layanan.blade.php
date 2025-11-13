@extends('layouts.app')

@section('title', 'Layanan Umum | RSHP Universitas Airlangga')

@section('content')
<section class="hero" id="home" role="banner" aria-label="Layanan Umum Kami">
    <div>
        <h1>Layanan Umum Kami</h1>
        <p>Memberikan solusi kesehatan komprehensif untuk hewan kesayangan Anda.</p>
    </div>
</section>

<main class="container" role="main">
    <h2 class="section-title" style="grid-column: 1 / -1; margin-bottom: 40px;">Layanan Kesehatan Hewan Komprehensif</h2>

    <section class="card" aria-labelledby="pemeriksaan-title">
        <h2 id="pemeriksaan-title">Pemeriksaan Kesehatan Rutin</h2>
        <p>Pemeriksaan rutin untuk menjaga hewan peliharaan Anda tetap sehat dan mendeteksi masalah kesehatan sejak dini. Termasuk pemeriksaan fisik, gigi, dan mata.</p>
    </section>

    <section class="card" aria-labelledby="vaksinasi-title">
        <h2 id="vaksinasi-title">Program Vaksinasi & Pencegahan</h2>
        <p>Program vaksinasi lengkap untuk melindungi hewan Anda dari penyakit menular yang berbahaya, termasuk kontrol kutu, caplak, dan cacing.</p>
    </section>

    <section class="card" aria-labelledby="bedah-title">
        <h2 id="bedah-title">Bedah Minor dan Major</h2>
        <p>Tim bedah berpengalaman kami siap melakukan prosedur bedah, mulai dari sterilisasi (spay/neuter) hingga operasi ortopedi dan operasi kompleks lainnya.</p>
    </section>

    <section class="card" aria-labelledby="gawat-darurat-title">
        <h2 id="gawat-darurat-title">Pelayanan Gawat Darurat (UGD)</h2>
        <p>Layanan darurat medis untuk kasus-kasus kritis dan mendesak, tersedia 24 jam sehari, 7 hari seminggu, untuk penanganan cepat dan tepat.</p>
    </section>

    <section class="card" aria-labelledby="rawat-inap-title">
        <h2 id="rawat-inap-title">Fasilitas Rawat Inap & ICU</h2>
        <p>Fasilitas rawat inap yang aman, nyaman, dan higienis untuk pemulihan optimal hewan Anda di bawah pengawasan dokter dan perawat 24 jam.</p>
    </section>

    <section class="card" aria-labelledby="laboratorium-title">
        <h2 id="laboratorium-title">Pelayanan Laboratorium & Radiologi</h2>
        <p>Diagnostik cepat dan akurat dengan peralatan laboratorium modern (darah, urin, feses) serta radiologi (X-ray, USG) untuk menentukan diagnosis yang tepat.</p>
    </section>
</main>
@endsection
