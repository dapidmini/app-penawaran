@extends('layouts.main')

@section('content')
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <div class="h2 d-inline">{{ $page_title }}</div>
        <div class="ms-2 d-inline">
            <a href="/customer">
                Kembali ke List Customer
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form action="/customer/create" method="POST" id="formCustomerCreate">
                @csrf
                <input type="hidden" name="module" id="module" value="customer">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Customer</label>
                    <input type="text" class="form-control form-control-sm @error('nama') is-invalid @enderror" id="nama"
                        name="nama" data-slugSelector="#slug" placeholder="nama customer" required value="{{ old('nama') }}" autofocus>
                    @error('nama')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control form-control-sm @error('slug') is-invalid @enderror" id="slug"
                        name="slug" placeholder="nama-customer" required value="{{ old('slug') }}">
                    @error('slug')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control form-control-sm @error('alamat') is-invalid @enderror" placeholder="Alamat Customer" cols="30" rows="5">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="telepon" class="form-label">No.Telepon</label>
                    <input type="text" class="form-control form-control-sm @error('telepon') is-invalid @enderror" id="telepon"
                        name="telepon" placeholder="Nomor telepon Customer" required
                        value="{{ old('telepon') }}">
                    @error('telepon')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="Alamat email untuk menghubungi Customer" required
                        value="{{ old('email') }}">
                    @error('email')
                        {{ $message }}
                    @enderror
                </div>

                <button class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
