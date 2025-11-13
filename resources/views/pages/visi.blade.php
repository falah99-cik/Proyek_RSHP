@extends('layouts.app')

@section('title', 'Visi Misi & Tujuan | RSHP Universitas Airlangga')

@section('content')
    <section class="hero" id="home" role="banner" aria-label="Visi Misi dan Tujuan RSHP">
        <div>
            <h1>Visi Misi dan Tujuan</h1>
            <p>Pedoman kami dalam memberikan pelayanan dan kontribusi terbaik bagi kesehatan hewan.</p>
        </div>
    </section>

    <main class="container" role="main">

        <section class="card" style="grid-column: 1 / -1; margin-bottom: 40px;">
            <h2>Visi RSHP Universitas Airlangga</h2>
            <p style="font-size: 1.2rem; font-style: italic; color: #1a1a1a;">
                "Menjadi Rumah Sakit Hewan Pendidikan terdepan dan rujukan nasional yang berbasis keunggulan riset,
                bermoral agama, dan berorientasi pada kesejahteraan hewan (Animal Welfare)."
            </p>
        </section>

        <section class="card" style="grid-column: 1 / -1; margin-bottom: 40px;">
            <h2>Misi Kami</h2>
            <ul>
                <li>Menyelenggarakan pelayanan kesehatan hewan yang berkualitas tinggi dan beretika profesional.</li>
                <li>Menjadi pusat pendidikan klinik unggulan bagi mahasiswa kedokteran hewan.</li>
                <li>Mengembangkan riset terapan di bidang kedokteran hewan klinik yang inovatif dan relevan.</li>
                <li>Menerapkan prinsip-prinsip kesejahteraan hewan dalam setiap aspek pelayanan.</li>
            </ul>
        </section>

        <section class="card" style="grid-column: 1 / -1;">
            <h2>Tujuan RSHP</h2>
            <p>RSHP memiliki tujuan utama untuk mendukung pelaksanaan Tri Dharma Perguruan Tinggi, yaitu:</p>
            <ol>
                <li>Meningkatkan kualitas pembelajaran klinik bagi calon dokter hewan.</li>
                <li>Menghasilkan publikasi ilmiah dan paten dari hasil penelitian kasus klinik.</li>
                <li>Menyediakan pelayanan kesehatan hewan yang terjangkau dan profesional bagi masyarakat.</li>
            </ol>
        </section>
    </main>
@endsection

