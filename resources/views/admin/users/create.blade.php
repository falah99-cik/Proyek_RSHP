@extends('layouts.admin')

@section('title', 'Tambah User Baru - RSHP UNAIR')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/edit_user_form.css') }}">
@endpush

@section('content')
    <main class="main-content form-page-container">
        <div class="form-card card-shadow">
            <header class="form-header">
                <h1 class="form-title">Tambah User Baru</h1>
                <p class="form-subtitle">Isi data di bawah ini untuk menambahkan akun pengguna baru.</p>
            </header>

            <form action="{{ route('admin.users.store') }}" method="POST" class="form-content">
                @csrf
                <div class="form-group">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <div class="input-icon-wrapper">
                        <!-- SVG icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" id="nama" name="nama" required class="form-input" placeholder="Masukkan nama lengkap" value="{{ old('nama') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-icon-wrapper">
                        <!-- SVG icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <input type="email" id="email" name="email" required class="form-input" placeholder="Masukkan alamat email" value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-icon-wrapper">
                        <!-- SVG icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <input type="password" id="password" name="password" required class="form-input" placeholder="Masukkan password">
                    </div>
                </div>

                <div class="form-group">
                    <label for="idrole" class="form-label">Role</label>
                    <div class="input-icon-wrapper">
                        <!-- SVG icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <select id="idrole" name="idrole" required class="form-input">
                            <option value="">Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->idrole }}">{{ $role->nama_role }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14m-7-7h14"></path>
                        </svg>
                        Tambah User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="cancel-btn">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </main>
@endsection