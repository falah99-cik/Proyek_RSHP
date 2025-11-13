@extends('layouts.app')

@section('title', 'Home | RSHP Universitas Airlangga')

@section('content')
<section class="hero" id="home" role="banner" aria-label="Selamat Datang di RSHP Universitas Airlangga">
    <div>
        <h1>Selamat Datang di RSHP Universitas Airlangga</h1>
        <p>Memberikan layanan kesehatan hewan terbaik dengan tenaga profesional dan fasilitas modern.</p>
    </div>
</section>

<main class="container" role="main">
    <section class="card" aria-labelledby="about-title"
        style="grid-column: 1 / -1; max-width: 800px; margin: 0 auto 40px; text-align: center;">
        <h2 id="about-title">Sekilas Tentang RSHP</h2>
        <p>
            Rumah Sakit Hewan Pendidikan (RSHP) Universitas Airlangga adalah institusi terkemuka yang menyediakan layanan
            medis, bedah, dan perawatan komprehensif untuk hewan kesayangan. Kami berkomitmen untuk mendukung pendidikan
            bagi mahasiswa kedokteran hewan sambil memberikan standar perawatan tertinggi, didukung oleh tim dokter
            hewan profesional dan fasilitas diagnostik mutakhir.
        </p>
    </section>

    <h2 class="section-title">Mengapa Memilih Kami?</h2>

    <section class="card">
        <h2>Tim Medis Berpengalaman</h2>
        <p>Didukung oleh tim dokter hewan ahli dan perawat terlatih yang memiliki pengalaman luas dalam menangani berbagai kasus medis dan bedah.</p>
    </section>

    <section class="card">
        <h2>Fasilitas Modern</h2>
        <p>Kami dilengkapi dengan peralatan canggih, termasuk laboratorium diagnostik, ruang operasi steril, dan fasilitas rawat inap yang nyaman.</p>
    </section>

    <section class="card">
        <h2>Pendekatan Holistik</h2>
        <p>Kami tidak hanya fokus pada pengobatan, tetapi juga pada pencegahan. Kami menyediakan konsultasi nutrisi, vaksinasi, dan program pencegahan penyakit lainnya.</p>
    </section>

    <section class="testimoni">
        <p>"Saya sangat puas dengan pelayanan di RSHP Unair. Anjing saya, Kiko, pulih dengan cepat setelah operasi. Dokter dan stafnya sangat profesional dan peduli!"</p>
        <footer>— Riko, Pemilik Kiko</footer>
    </section>
</main>
@endsection
