@extends('layouts.main')

@section('content')
    <div class="pt-3 pb-2 mb-3 border-bottom">
        <div class="h2 d-inline">{{ $page_title }}</div>
        <div class="ms-2 d-inline">
            <a href="/penawaran">
                Kembali ke List Penawaran
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="/penawaran/create" method="POST" id="form-create-penawaran">
                @csrf
                <input type="hidden" name="module" id="module" value="penawaran">
                <div class="mb-3">
                    <div class="row">
                        <label for="colFormLabel" class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-10 d-flex align-items-center">
                            <button type="button" class="btn btn-info btn-sm text-nowrap" id="btn-modal-cari-customer"
                                data-bs-toggle="modal" data-bs-target="#modalCariCustomer">
                                Cari Customer
                            </button>
                            @include('penawaran.modal-cari-customer')
                        </div>
                    </div>
                    <small>
                        <div class="row">
                            <div class="col-sm-10 offset-sm-2" id="dataCustomer">
                                <div>Nama : </div>
                                <div>Alamat : </div>
                                <div>Telepon : </div>
                            </div>
                        </div>
                    </small>
                </div>

                <div class="row g-3 align-items-center mb-3">
                    <div class="col-auto">
                        <label for="tglPengajuan" class="col-form-label">Tanggal Pengajuan</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="tglPengajuan" id="tglPengajuan"
                            class="form-control @error('tglPengajuan') is-invalid @enderror"
                            placeholder="Tanggal Pengajuan Penawaran (format DD-MM-YYYY) e.g. 25-01-2023" required
                            value="{{ old('tanggalPengajuan') }}">
                        @error('tanggalPengajuan')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Data Barang</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <button type="button" class="btn btn-info btn-sm text-nowrap" id="btn-modal-cari-barang" data-bs-toggle="modal"
                            data-bs-target="#modalAddPenawaranDataBarang">
                            Cari Barang
                        </button>
                        @include('partials.modal-add-penawaran-barang')
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="table-responsive small table-content overflow-visible overflow-y-scroll">
                        <table class="table table-striped table-bordered table-sm table-hover" id="tableDataBarangPenawaran">
                            <thead>
                                <tr>
                                    <th scope="col" class="fit text-start px-2">#</th>
                                    <th scope="col" class="px-2">Nama Barang</th>
                                    <th scope="col" class="fit px-2">Qty</th>
                                    <th scope="col" class="fit px-2">Harga Satuan</th>
                                    <th scope="col" class="fit px-2">Subtotal</th>
                                    <th scope="col" class="fit px-2">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td class="fit">1.</td>
                                    <td>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal-show-item-penawaran"
                                            data-slug="lampu-led-12-watt">
                                            Lampu LED 12 watt
                                        </a>
                                    </td>
                                    <td class="text-center fit">15</td>
                                    <td class="text-end fit">Rp 15.000</td>
                                    <td class="text-end fit">Rp 20.000</td>
                                    <td class="text-end fit">Rp 75.000</td>
                                </tr>
                                <tr>
                                    <td class="fit">2.</td>
                                    <td>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal-show-item-penawaran"
                                            data-slug="cctv-panasonic">
                                            CCTV Panasonic
                                        </a>
                                    </td>
                                    <td class="text-center fit">10</td>
                                    <td class="text-end fit">Rp 200.000</td>
                                    <td class="text-end fit">Rp 220.000</td>
                                    <td class="text-end fit">Rp 200.000</td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                <button class="btn btn-primary w-auto">Submit</button>
            </form>
        </div>
    </div>

    <script src="/assets/js/penawaran.js"></script>

@endsection
