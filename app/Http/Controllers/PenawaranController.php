<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class PenawaranController extends Controller
{
    public function index()
    {
        $data = Penawaran::latest();
        if (request('search')) {
            $data->where('nama_customer', 'like', '%'.request('search').'%')
                ->orWhereHas('barangs', function (Builder $query) {
                    $query->where('nama_barang', 'like', '%'.request('search').'%');
                });
        }

        $view_data = [
            'page_title' => 'List Penawaran',
            'active' => 'penawaran',
            'data' => $data->get()
        ];
        return view('penawaran.index', $view_data);
    }

    public function create()
    {
        $view_data = [
            'page_title' => 'Input Penawaran',
            'active' => 'penawaran',
        ];
        return view('penawaran.create', $view_data);
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
}
