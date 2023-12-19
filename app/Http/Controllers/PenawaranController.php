<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
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
        // $data_detail = [
        //     [
        //         'penawaran_id' => 1,
        //         'barang_id' => 123,
        //         'qty' => 10,
        //         'harga' => 15000,
        //         'diskon_satuan' => 2000,
        //         'biaya_satuan' => '3.5%',
        //     ],
        //     [
        //         'penawaran_id' => 1,
        //         'barang_id' => 222,
        //         'qty' => 5,
        //         'harga' => 12000,
        //         'diskon_satuan' => 1000,
        //         'biaya_satuan' => '10%',
        //     ],
        // ];
        // dd($data_detail);
        // dd(request());
        // $request->dd();
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
        // $request->validate([
        //     'customer_id' => 'required|max:255',
        //     'total_penjualan_kotor' => 'required|numeric|gt:0',
        //     'tgl_pengajuan' => 'required',
        //     'slug.*' => 'required|max:255',
        //     'stok.*' => 'required|numeric|min:1|gte:qty.*',
        //     'qty.*' => 'required|numeric|min:1',
        // ]);
        // $request->dd();

        // populate data utk detail penawarannya
        $arr_slug = $request->input('slug');
        $arr_qty = $request->input('qty');
        $arr_harga_jual = $request->input('hargaJual');
        $arr_diskon_satuan = $request->input('diskonSatuan');
        $arr_biaya_satuan = $request->input('biayaSatuan');
        $arr_diskon_subtotal = $request->input('diskonSubtotal');
        $arr_biaya_subtotal = $request->input('biayaSubtotal');
        // $arr_subtotal_modal = $request->input('subtotalModal');
        // $arr_subtotal_jual = $request->input('subtotalJualSatuan');
        $arr_subtotal_barang = $request->input('subtotalJualBarang');

        DB::beginTransaction();

        try {
            // get customer ID berdasarkan slug_customer
            $cust_data = Customer::where('slug', '=', request('slug_customer'))->get();
            // simpan data master penawaran spy bs dapet value utk field penawaran_id
            // utk dipake di tabel detail_penawaran
            // set timezone nya mjd GMT +7
            date_default_timezone_set('Asia/Jakarta');
            $master_penawaran = [
                'customer_id' => $cust_data[0]->id,
                'penjualan_kotor' => $request->input('total_penjualan_kotor'),
                'diskon_kumulatif' => $request->input('diskon_kumulatif'),
                'biaya_kumulatif' => $request->input('biaya_kumulatif'),
                'profit' => $request->input('total_profit'),
                'user_id' => (Auth::check() ? Auth::user()->id : 99),
                'tgl_pengajuan' => date('Y-m-d H:i:s', time()),
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
            DB::table('detail_penawaran')->insert($insert_details);
            // dd($insert_details);
            // foreach ($q as $row) {
            //     $arr_barang[] = [
            //         'id' => $row->id,
            //         'slug' => $row->slug,
            //     ];
            // }
            // dd($arr_barang);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $request->session()->flash('penawaranSuccess', 'Data Penawaran berhasil disimpan');

        return redirect('/penawaran');

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
