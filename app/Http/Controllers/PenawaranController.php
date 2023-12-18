<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        // dd(request());
        // $request->dd();
        $request->validate([
            'slug_customer' => 'required|max:255',
            'tgl_pengajuan' => 'required',
            'slug.*' => 'required|max:255',
            'stok.*' => 'required|numeric|min:1|gte:qty.*',
            'qty.*' => 'required|numeric|min:1',
            // 'formdata' => 'required|array',
            // 'formdata.*.slug' => 'required',
            // 'formdata.*.qty' => 'required|min:1|gt:formdata.*.stok',
        ]);
        // $request->dd();

        DB::beginTransaction();

        try {
            // get customer ID berdasarkan slug_customer
            $cust_data = Customer::where('slug', '=', request('slug_customer'))->get();
            // simpan data master penawaran spy bs dapet value utk field penawaran_id
            // utk dipake di tabel detail_penawaran
            // set timezone nya mjd GMT +7
            date_default_timezone_set('Asia/Jakarta');
            $insert_data = [
                'customer_id' => $cust_data[0]->id,
                'user_id' => (Auth::check() ? Auth::user()->id : 99),
                'tgl_pengajuan' => date('Y-m-d H:i:s', time()),
                'created_at' => date('Y-m-d H:i:s', time()),
            ];
            // dd($insert_data);
            DB::table('penawarans')->insert($insert_data);

            // populate data utk detail penawarannya

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return view('test', ['data' => $insert_data]);

        // $message = null;
        // $stock = [];
        // $result = [];
        // $inventory_entry_id = [];
        // $data = $request->all();

        // try {
        //     // loop through the received data
        // } catch (\Throwable $th) {
        //     //throw $th;
        // }
        // dd($validated);
        // dd((int)$request['harga']);
        // $request['harga'] = (int)$request['harga'];
        // $request['stok'] = (int)$request['stok'];

        // $validated = $request->validate([
        //     'nama' => 'required|max:255',
        //     'slug' => 'required|unique:barangs',
        //     'harga' => 'required|numeric|gte:1',
        //     'stok' => 'required|numeric|gte:1'
        // ]);
        // Penawaran::create($validated);

        // $request->session()->flash('penawaranSuccess', 'Data Penawaran berhasil disimpan');

        // return redirect('/barang');
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
