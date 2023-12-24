@extends('layouts.main')

@section('content')
    @if (session()->has('customerSuccess'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert">
                {{ session('customerSuccess') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
        <div class="d-flex justify-content-start align-items-center w-auto">
            <h2>{{ $page_title }}</h2>
            <div class="mx-2">
                <a href="/customer/create" class="btn btn-primary">
                    <i class="bi bi-plus"></i>
                    Buat Baru
                </a>
            </div>

            <form action="/customer">
                <div class="input-group w-auto">
                    <input type="text" class="form-control form-control-sm" placeholder="Cari customer" name="search">
                    <button class="btn btn-outline-secondary" type="submit" id="btnSearch">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="btn-toolbar mb-2 mb-md-3 d-flex justify-content-end align-items-center">
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
            <table class="table table-striped table-bordered table-hover table-sm">
                <thead>
                    <tr class="text-center">
                        <th scope="col" class="text-start px-2 fit">#</th>
                        <th scope="col">Nama</th>
                        <th scope="col">Alamat</th>
                        <th scope="col">Telepon</th>
                        <th scope="col">Email</th>
                        {{-- <th scope="col">Tgl</th> --}}
                        <th scope="col">Menu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td class="px-2 fit">{{ $loop->index + 1 }}.</td>
                            <td class="px-2 fit">{{ $row->nama }}</td>
                            <td class="px-2">{{ $row->alamat }}</td>
                            <td class="px-2 fit">{{ $row->telepon }}</td>
                            <td class="px-2 fit">{{ $row->email }}</td>
                            {{-- <td class="px-2 fit">{{ $row->latestPenawaranByCust??'-' }}</td> --}}
                            <td class="text-center fit">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="/customer/{{ $row->slug }}/edit">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="/customer/{{ $row->slug }}" method="POST"
                                                id="delete-form-{{ $row->slug }}">
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
                </tbody>
            </table>
        @else
            <p class="text-italic">Belum ada data Customer</p>
        </div>
    @endif
@endsection
