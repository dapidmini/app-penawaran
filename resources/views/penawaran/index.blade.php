@extends('layouts.main')

@section('content')
    @if (session()->has('penawaranSuccess'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert">
                {{ session('penawaranSuccess') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="d-flex justify-content-start">
            <h2 class="me-2">{{ $page_title }}</h2>
            <div class="me-2">
                <a href="/penawaran/create" class="btn btn-primary">
                    <i class="bi bi-plus"></i>
                    Buat Baru
                </a>
            </div>
            <div>
                <form action="/penawaran">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari Penawaran"
                            id="inputFilterBarang" name="search" value="{{ old('search') }}">
                        <button class="btn btn-outline-secondary" type="submit" id="btnFilterBarang">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="btn-toolbar mb-2 mb-md-3 d-flex justify-content-end">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
                <svg class="bi">
                    <use xlink:href="#calendar3" />
                </svg>
                This week
            </button>
        </div>
    </div>

    <div class="table-responsive small table-content overflow-visible">
        <table class="table table-striped table-bordered table-hover table-sm">
            <thead>
                <tr>
                    <th scope="col" class="text-start px-2 fit">#</th>
                    <th scope="col" class="">Nama Customer</th>
                    <th scope="col" class="px-2 fit">Tgl Penawaran</th>
                    <th scope="col" class="px-2 fit">Nilai Total (Gross)</th>
                    <th scope="col" class="px-2 fit">Nilai Total (Nett)</th>
                    @if (in_array(Auth::user()->level, ['superadmin']))
                        <th scope="col" class="px-2 fit">Profit</th>
                    @endif
                    <th scope="col" class="px-2 fit">Menu</th>
                </tr>
            </thead>
            <tbody>
                @if (count($data) > 0)
                {{-- @dd($data) --}}
                    @foreach ($data as $key => $row)
                        <tr>
                            <td>{{ number_format($loop->index + 1) }}.</td>
                            <td>{{ $row->customer->nama }}</td>
                            <td class="text-nowrap px-2 text-center">{{ date('d-M-Y', strtotime($row->tgl_pengajuan)) }}</td>
                            <td class="text-nowrap px-2 text-end">Rp {{ number_format($row->penjualan_kotor) }}</td>
                            <td class="text-nowrap px-2 text-end">Rp {{ number_format($row->penjualan_nett) }}</td>
                            @if (in_array(Auth::user()->level, ['superadmin']))
                                <td class="text-nowrap px-2 text-end">Rp {{ number_format($row->profit) }}</td>
                            @endif
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown"></button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="/penawaran/{{ $row->id }}">
                                                <i class="bi bi-eye-fill"></i> View Detail
                                            </a>
                                        </li>
                                        @if (in_array(Auth::user()->level, ['admin', 'superadmin']))
                                            <li>
                                                <a class="dropdown-item" href="/penawaran/{{ $row->id }}/edit">
                                                    <i class="bi bi-pencil-square"></i> Edit
                                                </a>
                                            </li>
                                        @endif
                                        @if (in_array(Auth::user()->level, ['superadmin']))
                                            <li>
                                                <form action="/penawaran/{{ $row->id }}" method="POST"
                                                    id="delete-form-slug-penawaran">
                                                    @method('delete')
                                                    @csrf
                                                    <a class="dropdown-item" href="#"
                                                        onclick="confirmDelete('{{ $row->id }}')">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </a>
                                                </form>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <p class="fst-italic">Belum ada data penawaran.</p>
                @endif
                {{-- 
                <tr>
                    <td>1.</td>
                    <td>Toko ABC Jl.Tidar no.100</td>
                    <td>22 November 2023</td>
                    <td>Rp 1.500.000</td>
                    <td>Rp 1.200.000</td>
                    <td>Rp 300.000</td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/penawaran/slug-penawaran">
                                        <i class="bi bi-eye-fill"></i> View Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/penawaran/slug-penawaran/edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="/penawaran/slug-penawaran" method="POST" id="delete-form-slug-penawaran">
                                        @method('delete')
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="confirmDelete('slug-penawaran')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>2.</td>
                    <td>Indomaret Jl.Ngagel Jaya Selatan no.22</td>
                    <td>12 November 2023</td>
                    <td>Rp 1.500.000</td>
                    <td>Rp 1.200.000</td>
                    <td>Rp 300.000</td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/penawaran/slug-penawaran">
                                        <i class="bi bi-eye-fill"></i> View Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/penawaran/slug-penawaran/edit">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="/penawaran/slug-penawaran" method="POST" id="delete-form-slug-penawaran">
                                        @method('delete')
                                        @csrf
                                        <a class="dropdown-item" href="#" onclick="confirmDelete('slug-penawaran')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                 --}}
            </tbody>
        </table>
    </div>

    {{-- <script src="/assets/js/penawaran2.js"></script> --}}
@endsection
