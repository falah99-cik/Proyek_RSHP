@extends('layouts.admin')

@section('title', 'Tambah Ras Hewan - RSHP UNAIR')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/admin/tambah_ras_hewan_form.css') }}">
@endpush

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1 class="page-title">Tambah Ras Hewan</h1>
    </div>

    <div class="form-page-container">
        <div class="form-card">
            <header class="form-header">
                <h1 class="form-title">Tambah Ras Hewan</h1>
                <p class="form-subtitle">Tambahkan ras hewan baru ke dalam sistem.</p>
            </header>

            <form action="{{ route('admin.ras-hewan.store') }}" method="POST" class="form-content">
                @csrf
                
                @if($errors->any())
                    <div class="error-message">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="idjenis_hewan" class="form-label">Jenis Hewan</label>
                    <div class="input-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <select class="form-select @error('idjenis_hewan') is-invalid @enderror" 
                                id="idjenis_hewan" name="idjenis_hewan" required>
                            <option value="">Pilih Jenis Hewan</option>
                            @foreach($jenisHewan as $jenis)
                            <option value="{{ $jenis->idjenis_hewan }}" {{ old('idjenis_hewan') == $jenis->idjenis_hewan ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis_hewan }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('idjenis_hewan')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_ras" class="form-label">Nama Ras</label>
                    <div class="input-icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <input type="text" class="form-input @error('nama_ras') is-invalid @enderror" 
                               id="nama_ras" name="nama_ras" value="{{ old('nama_ras') }}" required>
                    </div>
                    @error('nama_ras')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan
                    </button>
                    <a href="{{ route('admin.ras-hewan.index') }}" class="cancel-btn">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection