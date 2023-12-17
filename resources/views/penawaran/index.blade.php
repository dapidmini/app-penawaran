@extends('layouts.main')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="h2">
            {{ $page_title }}
            <span class="ms-2">
                <a href="/penawaran/create" class="btn btn-primary">
                    <i class="bi bi-plus"></i>
                    Buat Baru
                </a>
            </span>
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
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col" class="text-start px-2">#</th>
                    <th scope="col" class="col-3">Nama Customer</th>
                    <th scope="col" class="col-2">Tanggal Penawaran</th>
                    <th scope="col" class="col-2">Nilai Total (Gross)</th>
                    <th scope="col" class="col-2">Nilai Total (Nett)</th>
                    <th scope="col" class="col-2">Profit</th>
                    <th scope="col">Menu</th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
    </div>

    <script src="/assets/js/penawaran.js"></script>

@endsection
