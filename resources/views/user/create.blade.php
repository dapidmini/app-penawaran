@extends('layouts.main')

@section('content')
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <div class="h2 d-inline">{{ $page_title }}</div>
        <div class="ms-2 d-inline">
            <a href="/barang">
                Kembali ke List User
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form action="/user/create" method="POST" id="form-create-user">
                @csrf

                <input type="hidden" id="module" value="user">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                        name="username" placeholder="username" required value="{{ old('username') }}" autofocus>
                    @error('username')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                        name="slug" placeholder="username-saya" required value="{{ old('slug') }}">
                    @error('slug')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="email.saya@gmail.com" required value="{{ old('email') }}">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="password saya" required value="{{ old('password') }}">
                    @error('password')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama User</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" placeholder="nama saya" required value="{{ old('name') }}">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="level" class="form-label">Level Akses</label>
                    <select class="form-select" name="level" id="level">
                        <option {{ old('level') ? '' : 'selected' }}>Pilih salah satu level hak akses User ini</option>
                        <option value="staff" {{ old('level') == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="admin" {{ old('level') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="superadmin" {{ old('level') == 'superadmin' ? 'selected' : '' }}>
                            Super Admin
                        </option>
                    </select>
                    @error('level')
                        {{ $message }}
                    @enderror
                </div>

                <button class="btn btn-primary py-2" type="submit">Create User</button>
            </form>
        </div>
    </div>
@endsection
