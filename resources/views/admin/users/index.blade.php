
@extends('layouts.admin')

@section('title', 'Manajemen User - RSHP UNAIR')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Data User</h1>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Tambah User Baru
            </a>
        </div>

        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- Data Table -->
        <div class="premium-table">
    <div class="table-responsive">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                                <span>{{ $user->nama }}</span>
                            </div>
                        </td>

                        <td>{{ $user->email }}</td>

                        <td>{{ $user->nama_role ?? 'Belum Ditetapkan' }}</td>

                        <td>
                            <span class="status-pill {{ $user->status == 1 ? 'active':'inactive' }}">
                                {{ $user->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.users.edit', $user->iduser) }}" class="btn-action edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.users.destroy', $user->iduser) }}"
                                      onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn-action delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-slate-500">
                            Tidak ada data user.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/sidebar/admin.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/admin/user_management.css') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endpush