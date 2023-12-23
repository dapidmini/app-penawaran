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
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="/penawaran/create" method="POST" id="formPenawaranCreate">
                @csrf
                <input type="hidden" name="module" id="module" value="penawaran">
                <div class="mb-3">
                    <div class="row">
                        <label for="colFormLabel" class="col-sm-2 col-form-label">Customer</label>
                        <div class="col-sm-10 d-flex align-items-center">
                            <button type="button" class="btn btn-info btn-sm text-nowrap" id="btnModalPilihCustomer"
                                data-bs-toggle="modal" data-bs-target="#modalPilihCustomer">
                                Pilih Customer
                            </button>
                            @include('partials.modal-pilih-customer')
                        </div>
                    </div>
                    <small>
                        <div class="row">
                            <div class="col-sm-10 offset-sm-2">
                                @include('partials.detail-pilih-customer')
                            </div>
                        </div>
                    </small>
                </div>

                <div class="row g-3 align-items-center mb-3">
                    <div class="col-auto">
                        <label for="tglPengajuan" class="col-form-label">Tanggal Pengajuan</label>
                    </div>
                    <div class="col-auto">
                        <input type="date" name="tgl_pengajuan" id="tglPengajuan"
                            class="form-control @error('tgl_pengajuan') is-invalid @enderror"
                            placeholder="Tanggal Pengajuan Penawaran (format DD-MM-YYYY) e.g. 25-01-2023" required
                            value="{{ old('tgl_pengajuan') }}">
                        @error('tgl_pengajuan')
                            {{ $message }}
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="colFormLabel" class="col-sm-2 col-form-label">Data Barang</label>
                    <div class="col-sm-10 d-flex align-items-center">
                        <button type="button" class="btn btn-info btn-sm text-nowrap" id="btn-modal-cari-barang"
                            data-bs-toggle="modal" data-bs-target="#modalPenawaranDataBarang">
                            Cari Barang
                        </button>
                        @include('partials.modal-add-penawaran-barang')
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="table-responsive small table-content overflow-visible overflow-y-scroll">
                        <input type="text" id="slugToEdit">
                        <table class="table table-striped table-bordered table-sm table-hover"
                            id="tableDataBarangPenawaran">
                            <thead>
                                <tr>
                                    <th scope="col" class="fit text-start px-2">#</th>
                                    <th scope="col" class="px-2">Nama Barang</th>
                                    <th scope="col" class="fit px-2">Qty</th>
                                    <th scope="col" class="fit px-2">Harga Satuan</th>
                                    <th scope="col" class="fit px-2">Subtotal</th>
                                    <th scope="col" class="fit px-2">Profit</th>
                                    <th scope="col" class="fit px-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="col-sm err-label fs-smaller fst-italic" id="errMsgTableDataBarangPenawaran"></div>
                </div>
                @include('partials.modal-edit-penawaran-barang')

                <div class="row mb-2" id="containerKomponenFinal">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-top">
                            <div class="flex-shrink me-2">
                                <label for="diskonFinalInput">Diskon Penjualan : </label>
                            </div>
                            <div class="flex-grow-1 pe-5">
                                <input type="text" id="diskonFinalInput" name="diskon_kumulatif" data-type="number"
                                    class="form-control form-control-sm" placeholder="Diskon final untuk penawaran ini"
                                    value="0">
                                <span id="diskonFinalValue" class="form-text fs-label-sm">Rp 0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex align-items-top justify-content-end">
                            <div class="flex-shrink me-2 ps-5">
                                <label for="biayaFinalInput">Biaya Penjualan : </label>
                            </div>
                            <div class="flex-grow-1">
                                <input type="text" id="biayaFinalInput" name="biaya_kumulatif" data-type="number"
                                    class="form-control form-control-sm" placeholder="Biaya final untuk penawaran ini"
                                    value="0">
                                <span id="biayaFinalValue" class="form-text fs-label-sm">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-2 fs-5">
                    <div class="col-sm-12">
                        <div class="d-flex align-items-top">
                            <span class="me-2">Total Penjualan Kotor :</span>
                            <span class="me-2" id="totalPenjualanKotor">Rp 0</span>
                            <input type="hidden" name="total_penjualan_kotor" id="totalPenjualanKotorValue">
                        </div>
                    </div>
                    <div class="col-sm-12 fw-bold">
                        <div class="d-flex align-items-top">
                            <span class="me-2">Total Penjualan Final :</span>
                            <span class="me-2" id="totalPenjualanFinal">Rp 0</span>
                            <input type="hidden" name="total_penjualan_final" id="totalPenjualanFinalValue">
                        </div>
                    </div>
                    <div class="col-sm-12 fw-bold">
                        <span>Total Profit :</span>
                        <span class="me-3" id="totalProfit">Rp 0</span>
                        <input type="hidden" name="total_profit" id="totalProfitValue">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-12">
                        <button class="btn btn-primary w-auto">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="/assets/js/penawaran2.js"></script>

@endsection
