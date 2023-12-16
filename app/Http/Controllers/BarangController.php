<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $view_data = [
            'page_title' => 'List Barang',
            'active' => 'barang',
            // 'username' => Auth::check() ? Auth::user() : '',
            'data' => Barang::all(),
        ];
        // if (Auth::check()) {
        //     dd(Auth::user());
        // }
        return view('barang.index', $view_data);
    }

    public function create()
    {
        $view_data = [
            'page_title' => 'Input Data Barang',
            'active' => 'barang',
        ];
        return view('barang.create', $view_data);
    }

    public function store(Request $request)
    {
        // dd((int)$request['harga']);
        // $request['harga'] = (int)$request['harga'];
        // $request['stok'] = (int)$request['stok'];

        $validated = $request->validate([
            'nama' => 'required|max:255',
            'slug' => 'required|unique:barangs',
            'harga' => 'required|numeric|gte:1',
            'stok' => 'required|numeric|gte:1'
        ]);
        Barang::create($validated);

        $request->session()->flash('barangSuccess', 'Data Barang berhasil disimpan');

        return redirect('/barang');
    }

    public function edit(Barang $barang)
    {
        $view_data = [
            'page_title' => 'Edit Data Barang',
            'active' => 'barang',
            'data' => $barang,
        ];
        return view('barang.edit', $view_data);
    }

    public function update(Request $request, Barang $barang)
    {
        $rules = [
            'nama' => 'required|max:255',
            'harga' => 'required|numeric|gte:1',
            'stok' => 'required|numeric|gte:1'
        ];
        if ($request->slug != $barang->slug) {
            $rules['slug'] = 'required|unique:barangs';
        }

        $validated = $request->validate($rules);

        Barang::where('id', $barang->id)
            ->update($validated);

        return redirect('/barang')->with('barangSuccess', 'Data Barang berhasil diperbarui');
    }

    public function destroy(Barang $barang)
    {
        Barang::destroy($barang->id);

        return redirect('/barang')->with('barangSuccess', 'Data Barang berhasil dihapus');
    }

    public function test()
    {
        $view_data = [
            'page_title' => 'List Barang',
            'active' => 'barang',
        ];
        return view('barang.test', $view_data);
    }

    public function fetchData()
    {
        $data = Barang::orderBy('nama', 'ASC');

        if (request('search')) {
            $data->where('nama', 'like', '%'.request('search').'%');
        }

        return response()->json($data->get());
    }
}
