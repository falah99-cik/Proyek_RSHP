@extends('layouts.admin')

@section('title', 'Tambah Jenis Hewan - RSHP UNAIR')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/tambah_jenis_hewan_form.css') }}">
@endpush

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1 class="page-title">Tambah Jenis Hewan</h1>
    </div>

    <div class="form-container">
        <form action="{{ route('admin.jenis-hewan.store') }}" method="POST" class="animal-form">
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
                <label for="nama_jenis_hewan" class="form-label">Nama Jenis Hewan</label>
                <input type="text" 
                       class="form-input @error('nama_jenis_hewan') is-invalid @enderror" 
                       id="nama_jenis_hewan" 
                       name="nama_jenis_hewan" 
                       value="{{ old('nama_jenis_hewan') }}" 
                       required>
                @error('nama_jenis_hewan')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan
                </button>
                <a href="{{ route('admin.jenis-hewan.index') }}" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                    Batal
                </a>
            </div>
        </form>
    </div>
</main>
@endsection