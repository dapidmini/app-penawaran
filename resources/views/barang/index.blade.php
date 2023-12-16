@extends('layouts.main')

@section('content')
    @if (session()->has('barangSuccess'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert">
                {{ session('barangSuccess') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="h2">
            {{ $page_title }}
            <span class="ms-2">
                <a href="/barang/create" class="btn btn-primary">
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

    @if ($data->count())
        <div class="table-responsive small" style="overflow-x: unset">
            <table class="table table-striped table-sm">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="text-start px-2">#</th>
                        <th scope="col" class="col-7">Nama Barang</th>
                        <th scope="col" class="col-2">Stok</th>
                        <th scope="col" class="col-2">Harga</th>
                        <th scope="col">Menu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td class="px-2">{{ $loop->index + 1 }}.</td>
                            <td>{{ $row->nama }}</td>
                            <td class="text-end">{{ number_format($row->stok) }}</td>
                            <td class="text-end">Rp {{ number_format($row->harga) }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="/barang/{{ $row->slug }}/edit">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="/barang/{{ $row->slug }}" method="POST" id="delete-form-{{ $row->slug }}">
                                                @method('delete')
                                                @csrf
                                                <a class="dropdown-item" href="#">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td>2.</td>
                        <td>CCTV Bluetooth Sony</td>
                        <td class="text-end">30</td>
                        <td class="text-end">Rp 500.000</td>
                        <td class="text-end">Rp 0</td>
                        <td class="text-end">Rp 500.000</td>
                    </tr> --}}
                </tbody>
            </table>
        @else
            <p class="text-italic">Belum ada data Barang</p>
        </div>
    @endif
@endsection
