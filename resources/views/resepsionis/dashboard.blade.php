@extends('layouts.admin')

@section('content')
<div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Resepsionis</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <form method="GET" action="{{ route('resepsionis.dashboard') }}">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Pilih Tanggal</label>
                        <input type="date" name="tanggal" value="{{ request('tanggal', date('Y-m-d')) }}" class="form-control">
                    </div>
                    <div class="col-md-2 mt-4">
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <div class="row">

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalReservasiHariIni }}</h3>
                            <p>Reservasi Hari Ini</p>
                        </div>
                        <div class="icon"><i class="ion ion-calendar"></i></div>
                        <a href="{{ route('resepsionis.temu-dokter') }}" class="small-box-footer">
                            Kelola Reservasi <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalPemilik }}</h3>
                            <p>Total Pemilik</p>
                        </div>
                        <div class="icon"><i class="ion ion-person"></i></div>
                        <a href="{{ route('resepsionis.registrasi-pemilik') }}" class="small-box-footer">
                            Registrasi Pemilik <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $totalPet }}</h3>
                            <p>Total Hewan</p>
                        </div>
                        <div class="icon"><i class="ion ion-paw"></i></div>
                        <a href="{{ route('resepsionis.registrasi-pet') }}" class="small-box-footer">
                            Registrasi Pet <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $pasienBaru }}</h3>
                            <p>Pasien Baru Hari Ini</p>
                        </div>
                        <div class="icon"><i class="ion ion-plus"></i></div>
                        <a href="#" data-toggle="modal" data-target="#modalPasienBaru" class="small-box-footer">
                            Lihat Daftar <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $pemilikBaru }}</h3>
                            <p>Pemilik Baru Hari Ini</p>
                        </div>
                        <div class="icon"><i class="ion ion-person-add"></i></div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $reservasiSelesai }}</h3>
                            <p>Reservasi Selesai</p>
                        </div>
                        <div class="icon"><i class="ion ion-checkmark"></i></div>
                    </div>
                </div>

            </div>

            <div class="card mt-3">
                <div class="card-header bg-dark text-white">
                    <h3 class="card-title mb-0">Reservasi Hari Ini</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No Antrian</th>
                                <th>Waktu</th>
                                <th>Nama Pet</th>
                                <th>Nama Pemilik</th>
                                <th>Dokter</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($reservasiHariIni as $r)
                            <tr>
                                <td>{{ $r->no_urut }}</td>
                                <td>{{ $r->waktu_daftar }}</td>
                                <td>{{ $r->nama_pet }}</td>
                                <td>{{ $r->nama_pemilik }}</td>
                                <td>{{ $r->nama_dokter ?? '-' }}</td>
                                <td>
                                    @if($r->status == 0)
                                        <span class="badge badge-warning">Menunggu</span>
                                    @else
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('resepsionis.temu-dokter') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada reservasi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Grafik Reservasi (7 Hari)</h3>
                </div>
                <div class="card-body">
                    <canvas id="chartDashboard" height="100"></canvas>
                </div>
            </div>

        </div>
    </section>

    <div class="modal fade" id="modalPasienBaru">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header bg-primary text-white">
              <h5 class="modal-title">Daftar Pasien Baru</h5>
              <button class="close text-white" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body">
              <table class="table table-bordered table-striped">
                  <thead>
                      <tr>
                          <th>Nama Pet</th>
                          <th>Pemilik</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($listPasienBaru as $pb)
                      <tr>
                          <td>{{ $pb->nama_pet }}</td>
                          <td>{{ $pb->nama_pemilik }}</td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
          </div>

        </div>
      </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const grafikData = @json($grafik7Hari);

    const labels = grafikData.map(item => item.tanggal);
    const totalReservasi = grafikData.map(item => item.total);

    const warnaBiru = 'rgba(52, 152, 219, 0.8)';
    const warnaHijau = 'rgba(46, 204, 113, 0.8)';
    const borderBiru = 'rgba(41, 128, 185, 1)';
    const borderHijau = 'rgba(39, 174, 96, 1)';

    const ctx = document.getElementById('chartDashboard').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: "Reservasi",
                    data: totalReservasi,
                    backgroundColor: warnaBiru,
                    borderColor: borderBiru,
                    borderWidth: 3,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: "#fff",
                    pointBorderColor: borderBiru,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            },
            plugins: {
                legend: { display: true },
                tooltip: {
                    backgroundColor: "#2c3e50"
                }
            }
        }
    });
});
</script>
@endsection
