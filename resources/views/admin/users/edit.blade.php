
@extends('layouts.admin')

@section('title', 'Edit User - RSHP UNAIR')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/edit_user_form.css') }}">
    <style>
        .error-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .error-container h1 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .error-container a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .error-container a:hover {
            background: #2980b9;
        }
    </style>
@endpush

@section('content')
@php
    // Debug untuk melihat data yang tersedia
    \Log::info('Edit user view loaded', [
        'user_exists' => isset($user),
        'roles_exists' => isset($roles),
        'roles_count' => isset($roles) ? count($roles) : 0,
        'user_data' => isset($user) ? $user->toArray() : null
    ]);
@endphp

<main class="main-content form-page-container">
    <div class="form-card card-shadow">
        <header class="form-header">
            <h1 class="form-title">Edit User</h1>
            <p class="form-subtitle">Ubah informasi pengguna <b>{{ $user->nama }}</b>.</p>
        </header>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="form-content">
            @csrf
            @method('PUT')
            <input type="hidden" name="iduser" value="{{ $user->iduser }}">

            <div class="form-group">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <div class="input-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required class="form-input">
                </div>
                @error('nama')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                </div>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password (Kosongkan jika tidak diubah)</label>
                <div class="input-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diubah">
                </div>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="idrole" class="form-label">Role</label>
                <div class="input-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                    <select id="idrole" name="idrole" required class="form-input">
                        @php
                            $userRole = $user->roles->first();
                            $selectedRoleId = old('idrole', $userRole ? $userRole->idrole : '');
                            \Log::info('Role dropdown debug', [
                                'user_role' => $userRole ? $userRole->toArray() : null,
                                'selected_role_id' => $selectedRoleId,
                                'available_roles' => $roles->toArray()
                            ]);
                        @endphp
                        @foreach($roles as $role)
                            <option value="{{ $role->idrole }}"
                                {{ $selectedRoleId == $role->idrole ? 'selected' : '' }}>
                                {{ $role->nama_role }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('idrole')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <div class="input-icon-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="1" y="3" width="22" height="18" rx="2" ry="2"></rect>
                        <path d="M7 3v18M17 3v18"></path>
                        <path d="M1 9h22M1 15h22"></path>
                    </svg>
                    <select id="status" name="status" required class="form-input">
                        @php
                            $userRole = $user->roles->first();
                            $selectedStatus = old('status', $userRole && $userRole->pivot ? $userRole->pivot->status : 1);
                            \Log::info('Status dropdown debug', [
                                'user_role' => $userRole ? $userRole->toArray() : null,
                                'user_role_pivot' => $userRole && $userRole->pivot ? $userRole->pivot->toArray() : null,
                                'selected_status' => $selectedStatus
                            ]);
                        @endphp
                        <option value="1" {{ $selectedStatus == 1 ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $selectedStatus == 0 ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                @error('status')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="submit-btn save-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="cancel-btn">
                    Batal
                </a>
            </div>
        </form>
    </div>
</main>
@endsection