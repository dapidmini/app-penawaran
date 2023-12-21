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
        $query = Penawaran::orderBy('id', 'DESC');
        // dd($query);
        if (request('search')) {
            $query->with([
                'customer' => function ($query) {
                    $query->where('nama', 'like', '%'.request('search').'%');
                },
                // 'detail_penawaran.barang' => function($query) {
                //     $query->where('nama', 'like', '%'.request('search').'%');
                // }
            ]);
        }

        $query->paginate(20);
        
        $data = $query->get();
        
        foreach($data as $key => $row) {
            $row['nilai_diskon_kumulatif'] = $row['diskon_kumulatif'];
            // kalau diskon kumulatif ditulis dlm bentuk persentase
            // maka hitung nilai rupiahnya
            if (strpos($row['diskon_kumulatif'], '%') !== false) {
                $value = preg_replace("/[^0-9.]/", "", $row['diskon_kumulatif']);
                $row['nilai_diskon_kumulatif'] = $row['penjualan_kotor'] * $value / 100;
            }
            $row['nilai_biaya_kumulatif'] = $row['biaya_kumulatif'];
            // kalau biaya kumulatif ditulis dlm bentuk persentase
            // maka hitung nilai rupiahnya
            if (strpos($row['biaya_kumulatif'], '%') !== false) {
                $value = preg_replace("/[^0-9.]/", "", $row['biaya_kumulatif']);
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
        $validator = Validator::make($request->all(), [
            'slug_customer' => 'required|max:255',
            'total_penjualan_kotor' => 'required|numeric|gt:0',
            'tgl_pengajuan' => 'required',
            'slug.*' => 'required|max:255',
            'stok.*' => 'required|numeric|min:1|gte:qty.*',
            'qty.*' => 'required|numeric|min:1',
        ]);
        if ($validator->fails()) {
            return redirect('/penawaran/create')
                    ->withErrors($validator)
                    ->withInput();
        }

        // populate data utk detail penawarannya
        $arr_slug = $request->input('slug');
        $arr_qty = $request->input('qty');
        $arr_harga_jual = $request->input('hargaJual');
        $arr_diskon_satuan = $request->input('diskonSatuanOri');
        $arr_biaya_satuan = $request->input('biayaSatuanOri');
        $arr_diskon_subtotal = $request->input('diskonSubtotalOri');
        $arr_biaya_subtotal = $request->input('biayaSubtotalOri');
        $arr_subtotal_barang = $request->input('subtotalJualBarang');

        DB::beginTransaction();

        try {
            // get customer ID berdasarkan slug_customer
            $cust_data = Customer::where('slug', '=', request('slug_customer'))->get();
            // simpan data master penawaran spy bs dapet value utk field penawaran_id
            // utk dipake di tabel detail_penawarans
            // set timezone nya mjd GMT +7
            date_default_timezone_set('Asia/Jakarta');
            $master_penawaran = [
                'customer_id' => $cust_data[0]->id,
                'penjualan_kotor' => $request->input('total_penjualan_kotor'),
                'diskon_kumulatif' => $request->input('diskon_kumulatif'),
                'biaya_kumulatif' => $request->input('biaya_kumulatif'),
                'profit' => $request->input('total_profit'),
                'user_id' => (Auth::check() ? Auth::user()->id : 99),
                'tgl_pengajuan' => date('Y-m-d H:i:s', strtotime($request->input('tgl_pengajuan'))),
                'created_at' => date('Y-m-d H:i:s', time()),
            ];
            // dd($master_penawaran);
            $insertedID = DB::table('penawarans')->insertGetId($master_penawaran);

            $insert_details = [];
            // dapatkan ID semua barang yg ada dlm penawaran tsb
            $q = Barang::orWhereIn('slug', $arr_slug);
            $q = $q->get();

            foreach ($arr_slug as $key => $value) {
                $tempRow = [];
                $tempRow['penawaran_id'] = $insertedID;
                // $tempRow['slug'] = $arr_slug[$key];
                foreach ($q as $barang) {
                    if ($barang->slug == $value) {
                        $tempRow['barang_id'] = $barang->id;
                        break;
                    }
                }
                $tempRow['qty'] = $arr_qty[$key];
                $tempRow['harga_jual'] = $arr_harga_jual[$key];
                $tempRow['diskon_satuan'] = $arr_diskon_satuan[$key];
                $tempRow['biaya_satuan'] = $arr_biaya_satuan[$key];
                $tempRow['diskon_subtotal'] = $arr_diskon_subtotal[$key];
                $tempRow['biaya_subtotal'] = $arr_biaya_subtotal[$key];
                $tempRow['subtotal'] = $arr_subtotal_barang[$key];

                $insert_details[] = $tempRow;
            }
            DB::table('detail_penawarans')->insert($insert_details);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $request->session()->flash('penawaranSuccess', 'Data Penawaran berhasil disimpan');

        return redirect('/penawaran');
    }
    // end public function store

    public function edit($id)
    {
        // dd($id);
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
