@extends('layouts.main')

@section('content')
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <div class="h2 d-inline">{{ $page_title }}</div>
        <div class="ms-2 d-inline">
            <a href="/user">
                Kembali ke List User
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <form action="/user/create" method="POST" id="form-barang-edit">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                        name="nama" placeholder="nama barang" required
                        value="{{ old('nama') ? old('nama') : $data->nama }}" autofocus>
                    @error('nama')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="slug" class="form-label">Kode Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug"
                        name="slug" placeholder="nama-barang" required
                        value="{{ old('slug') ? old('slug') : $data->slug }}">
                    @error('slug')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="text" class="form-control @error('harga') is-invalid @enderror" id="harga"
                        name="harga" data-type="number" placeholder="Harga Nett per satuan" required
                        value="{{ old('harga') ? old('harga') : $data->harga }}">
                    @error('harga')
                        {{ $message }}
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Sisa Stok</label>
                    <input type="text" class="form-control @error('stok') is-invalid @enderror" id="stok"
                        name="stok" data-type="number" placeholder="Sisa stok saat ini" required
                        value="{{ old('stok') ? old('stok') : $data->stok }}">
                    @error('stok')
                        {{ $message }}
                    @enderror
                </div>

                <button class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
