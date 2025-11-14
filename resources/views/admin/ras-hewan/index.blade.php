@extends('layouts.admin')

@section('title', 'Manajemen Ras Hewan - RSHP UNAIR')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
<style>
    .premium-table {
        margin-top: 20px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 20px;
    }
    .ras-title {
        font-weight: 600;
        font-size: 14px;
        color: #111827;
        margin-bottom: 6px;
    }
    .ras-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px dashed #e5e7eb;
    }
    .ras-item:last-child {
        border-bottom: none;
    }
    .btn-action {
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        cursor: pointer;
    }
    .edit { background: #e0f2fe; color: #0284c7; }
    .delete { background: #fee2e2; color: #dc2626; }
    .btn-add-ras {
        margin-top: 10px;
        padding: 8px 14px;
        font-size: 13px;
        background: #4f46e5;
        color: white;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- PAGE HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Ras Hewan</h1>
            <p class="page-subtitle">Kelola ras hewan berdasarkan jenisnya</p>
        </div>

        <a href="{{ route('admin.ras-hewan.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Ras
        </a>
    </div>

    <!-- ALERT -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <!-- TABEL PREMIUM -->
    <div class="premium-table">
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th style="width: 25%">Jenis Hewan</th>
                        <th style="width: 55%">Ras Hewan</th>
                        <th style="width: 20%; text-align:center;">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($jenisHewan as $jenis)
                    <tr>
                        <td>
                            <div class="user-flex">
                                <span class="font-medium">{{ $jenis->nama_jenis_hewan }}</span>
                            </div>
                        </td>

                        <td>
                            @php
                                $rasList = $rasHewan->where('idjenis_hewan', $jenis->idjenis_hewan);
                            @endphp

                            @if($rasList->count() > 0)
                                @foreach($rasList as $ras)
                                    <div class="ras-item">
                                        <span>{{ $ras->nama_ras }}</span>

                                        <div>
                                            <a href="{{ route('admin.ras-hewan.edit', $ras->idras_hewan) }}"
                                               class="btn-action edit">Edit</a>

                                            <form action="{{ route('admin.ras-hewan.destroy', $ras->idras_hewan) }}"
                                                  method="POST"
                                                  style="display:inline-block"
                                                  onsubmit="return confirm('Yakin hapus ras ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn-action delete">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-gray-400">Belum ada ras.</p>
                            @endif
                        </td>

                        <td style="text-align:center;">
                            <a href="{{ route('admin.ras-hewan.create') }}" class="btn-add-ras">
                                Tambah Ras Baru
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">Tidak ada data.</td>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
