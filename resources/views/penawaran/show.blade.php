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

    <div class="row mb-3 d-flex align-items-top">
        <div class="col-sm-2">Tgl Pengajuan</div>
        <div class="col-sm-10">{{ date('d-M-Y', strtotime($data->tgl_pengajuan)) }}</div>
    </div>

    <div class="row mb-3 d-flex align-items-top">
        <div class="col-sm-2">Customer</div>
        <div class="col-sm-10 fs-smaller">
            @include('partials.detail-pilih-customer')
        </div>
    </div>

    <div class="row mb-3 d-flex align-items top">
        <h4 class="fw-bold">Data Penawaran Barang</h4>

        <div class="table-responsive small table-content overflow-visible overflow-y-scroll">
            <table class="table table-striped table-bordered table-sm table-hover" id="tableDataBarangPenawaran">
                <thead>
                    <tr>
                        <th scope="col" class="fit text-start px-2">#</th>
                        <th scope="col" class="px-2">Nama Barang</th>
                        <th scope="col" class="fit px-2">Qty</th>
                        <th scope="col" class="fit px-2">Harga Satuan</th>
                        <th scope="col" class="fit px-2">Subtotal</th>
                        @if (Auth::user()->level == 'superadmin')
                            <th scope="col" class="fit px-2">Profit</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data->barangs as $dpenawaran)
                        <tr data-slug="{{ $dpenawaran->slug }}">
                            <td class="fit">{{ number_format($loop->index + 1) }}.</td>
                            <td>{{ $dpenawaran->nama }}</td>
                            <td class="text-center fit">{{ number_format($dpenawaran->pivot->qty, 0, ',', '.') }}</td>
                            <td class="text-end fit">
                                {{ number_format($dpenawaran->pivot->hargaJualSatuan, 0, ',', '.') }}
                            </td>
                            <td class="text-end fit">
                                {{ number_format($dpenawaran->subtotalBarang, 0, ',', '.') }}
                            </td>
                            @if (Auth::user()->level == 'superadmin')
                                <td class="text-end fit">{{ number_format($dpenawaran->profitBarang, 0, ',', '.') }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-auto">Total Penjualan (Kotor)</div>
        <div class="col-auto">: Rp {{ number_format($data->penjualan_kotor, 0, ',', '.') }}</div>
    </div>
    <div class="row mb-3">
        <div class="col-auto">Diskon Kumulatif</div>
        <div class="col-auto">: Rp {{ number_format($data->diskon_kumulatif, 0, ',', '.') }}</div>
    </div>
    <div class="row mb-3">
        <div class="col-auto">Biaya Kumulatif</div>
        <div class="col-auto">: Rp {{ number_format($data->biaya_kumulatif, 0, ',', '.') }}</div>
    </div>
    <div class="row mb-3 fw-bold">
        <div class="col-auto">Total Penjualan (Nett)</div>
        @php
            $penjualan_nett = $data->penjualan_kotor - $data->diskon_kumulatif + $data->biaya_kumulatif;
        @endphp
        <div class="col-auto">: Rp {{ number_format($penjualan_nett, 0, ',', '.') }}</div>
    </div>
@endsection
