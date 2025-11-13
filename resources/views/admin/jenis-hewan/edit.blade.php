@extends('layouts.admin')

@section('title', 'Edit Jenis Hewan - RSHP UNAIR')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/tambah_jenis_hewan_form.css') }}">
@endpush

@section('content')
<main class="main-content">
    <div class="page-header">
        <h1 class="page-title">Edit Jenis Hewan</h1>
    </div>

    <div class="form-container">
        <form action="{{ route('admin.jenis-hewan.update', $jenisHewan->idjenis_hewan) }}" method="POST" class="animal-form">
            @csrf
            @method('PUT')
            
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
                       value="{{ old('nama_jenis_hewan', $jenisHewan->nama_jenis_hewan) }}" 
                       required>
                @error('nama_jenis_hewan')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Update
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