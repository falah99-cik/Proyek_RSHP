@extends('layouts.pemilik')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Reservasi</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>No. Antrian</th>
                                    <th>Nama Hewan</th>
                                    <th>Dokter</th>
                                    <th>Tanggal & Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservasi as $index => $reserv)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $reserv->no_urut }}</td>
                                    <td>{{ $reserv->pet->nama }}</td>
                                    <td>{{ $reserv->dokter->user->nama }}</td>
                                    <td>{{ $reserv->waktu_daftar->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if($reserv->status == 0)
                                            <span class="badge badge-warning">Menunggu</span>
                                        @elseif($reserv->status == 1)
                                            <span class="badge badge-success">Selesai</span>
                                        @else
                                            <span class="badge badge-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada reservasi</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection