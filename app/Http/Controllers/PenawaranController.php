<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\DetailPenawaran;
use App\Models\Penawaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;

class PenawaranController extends Controller
{
    public function index()
    {
        $query = Penawaran::with(['customer']);

        if (request('search')) {
            $query->with([
                'customer' => function ($customerQuery) {
                    $customerQuery->where('nama', 'like', '%'.request('search').'%');
                },
                // 'detail_penawaran.barang' => function($query) {
                //     $query->where('nama', 'like', '%'.request('search').'%');
                // }
            ]);
        }

        $query->paginate(20);
        
        $data = $query->get();
        // dd($data->barangs);
        foreach($data as $key => $row) {
            $row['nilai_diskon_kumulatif'] = $row['diskon_kumulatif'];
            // kalau diskon kumulatif ditulis dlm bentuk persentase
            // maka hitung nilai rupiahnya
            if (strpos($row['diskon_kumulatif'], '%') !== false) {
                // hapus semua karakter selain angka diskonnya (tanda persen dan spasi kalau ada)
                $value = preg_replace("/[^0-9,]/", "", $row['diskon_kumulatif']);
                $value = (float)str_replace(',', '.', $value);
                $row['nilai_diskon_kumulatif'] = $row['penjualan_kotor'] * $value / 100;
            }
            $row['nilai_biaya_kumulatif'] = $row['biaya_kumulatif'];
            // kalau biaya kumulatif ditulis dlm bentuk persentase
            // maka hitung nilai rupiahnya
            if (strpos($row['biaya_kumulatif'], '%') !== false) {
                $value = preg_replace("/[^0-9,]/", "", $row['biaya_kumulatif']);
                $value = (float)str_replace(',', '.', $value);
                $row['nilai_biaya_kumulatif'] = $row['penjualan_kotor'] * $value / 100;
            }
            $data[$key]['penjualan_nett'] = $row['penjualan_kotor'] - $row['nilai_diskon_kumulatif'] + $row['nilai_biaya_kumulatif'];
            // dd($data[$key]);
        }

        $view_data = [
            'page_title' => 'List Penawaran',
            'active' => 'penawaran',
            'data' => $data
        ];
        return view('penawaran.index', $view_data);
    }

    public function show(Penawaran $penawaran)
    {
        $penawaran::with(['customer', 'barangs'])->firstOrFail();
        // dd($penawaran->barangs);
        // $query = Penawaran::with('customer')
        //     ->where('id', '=', $request->id)
        //     ->firstOrFail();
        foreach($penawaran->barangs as $dpenawaran) {
            $dataPivot = $dpenawaran->pivot;
            $dpenawaran['subtotalBarang'] = ($dataPivot->hargaJualSatuan - $dataPivot->diskonSatuanValue + $dataPivot->biayaSatuanValue) * $dataPivot->qty;
            $dpenawaran['profitBarang'] = $dpenawaran['subtotalBarang'] - $dataPivot->hargaBarang;
        }
        // dd($penawaran->barangs);
        $view_data = [
            'page_title' => 'List Penawaran',
            'active' => 'penawaran',
            'data' => $penawaran
        ];
        return view('penawaran.show', $view_data);
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
        // $request->dd();
        $insert_data = [
            'user_id' => Auth::user()->id,
            'customer_id' => $request->input('id_customer'),
            'penjualan_kotor' => $request->input('total_penjualan_kotor'),
            'diskon_kumulatif' => $request->input('diskon_kumulatif'),
            'biaya_kumulatif' => $request->input('biaya_kumulatif'),
            'profit' => $request->input('total_profit'),
            'tgl_pengajuan' => $request->input('tgl_pengajuan'),
        ];
        $penawaran = Penawaran::create($insert_data);

        $insert_details = [];
        $arr_id = $request->input('idBarang');
        $arr_qty = $request->input('qty');
        $arr_hb = $request->input('hargaBarang');
        $arr_hjs = $request->input('hargaJualSatuan');
        $arr_dso = $request->input('diskonSatuanOri');
        $arr_dsv = $request->input('diskonSatuanValue');
        $arr_bso = $request->input('biayaSatuanOri');
        $arr_bsv = $request->input('biayaSatuanValue');
        for($i=0; $i<count($arr_id); $i++) {
            $id = $arr_id[$i];
            $temp_arr = [
                'qty' => $arr_qty[$i],
                'hargaBarang' => $arr_hb[$i],
                'hargaJualSatuan' => $arr_hjs[$i],
                'diskonSatuanOri' => $arr_dso[$i],
                'diskonSatuanValue' => $arr_dsv[$i],
                'biayaSatuanOri' => $arr_bso[$i],
                'biayaSatuanValue' => $arr_bsv[$i],
            ];
            $insert_details[$id] = $temp_arr;
        }
        $penawaran->barangs()->sync($insert_details);

        $request->session()->flash('penawaranSuccess', 'Data Penawaran berhasil disimpan');

        return redirect('/penawaran');
    }
    // end public function store

    public function edit($id)
    {
        $data = Penawaran::with('customer')->where('id', '=', $id);
        // $data->with()
        
        $data = $data->firstOrFail();
        $data['detail_penawaran'] = DetailPenawaran::with('barang')->where('penawaran_id', '=', $id)->get();
        // foreach($penawaran as $key => &$row) {
        //     $row['lastPenawaran'] = Customer::with(['penawarans' => function($query) {
        //         $query->whereNotNull('updated_at')->orderBy('id', 'DESC')->last();
        //     }]);
        // }
        dd($data->detail_penawaran);
        if ($data) {
        //     $cust_data = $penawaran[0]['customer'];
        //     $cust_data['latestPenawaranByCust'] = Penawaran::where('')
        //     dd($penawaran[0]['customer']);
        //     // $tglPenawaranByCust = Customer::where('id', '=', $penawaran[0]->customer->id)
        //     //     ->with('latestPenawaranByCust')
        //     //     ->latest();
        //     //     // ->with(['latestPenawaranByCust' => function($query) {
        //     //     //     $query->whereNotNull('updated_at');
        //     //     // }])
        //     //     // ->orderBy('nama', 'ASC')
        //     //     // ->get();
        //     // $penawaran[0]['customer']['latestPenawaranByCust'] = $tglPenawaranByCust;
        //     // $cust_data = Customer::where('id', '=', $penawaran[0]-)
        //     // $penawaran[0]['customer'] = Customer::where('id', '=', $penawaran[0]->customer->id);
        }
        // dd($penawaran[0]->customer);
        $view_data = [
            'page_title' => 'Edit Data Penawaran',
            'active' => 'penawaran',
            'submenu' => 'edit',
            'data' => $data,
        ];
        return view('penawaran.edit', $view_data);
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
