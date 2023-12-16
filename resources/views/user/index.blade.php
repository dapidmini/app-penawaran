@extends('layouts.main')

@section('content')
    @if (session()->has('userSuccess'))
        <div class="d-flex justify-content-center">
            <div class="alert alert-success alert-dismissible fade show d-inline-block" role="alert">
                {{ session('userSuccess') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <div class="h2">
            {{ $page_title }}
            <span class="ms-2">
                <a href="/user/create" class="btn btn-primary">
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
                        <th scope="col" class="col-2">Username</th>
                        <th scope="col" class="col-1">Level</th>
                        <th scope="col" class="col-4">Email</th>
                        <th scope="col" class="col-4">Full Name</th>
                        <th scope="col">Menu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td class="px-2">{{ $loop->index + 1 }}.</td>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->level }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->name }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="/user/{{ $row->slug }}/edit">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="/user/{{ $row->slug }}" method="POST">
                                                @method('delete')
                                                @csrf
                                                <a class="dropdown-item" href=""
                                                    onclick="return confirm('Data akan dihapus. Lanjutkan?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            </form>
                                            {{-- <a class="dropdown-item" href=""><i class="bi bi-trash"></i> Hapus</a> --}}
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-italic">Belum ada data User</p>
    @endif
    </div>
@endsection
