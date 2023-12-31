<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class CustomerController extends Controller
{
    public function index()
    {
        $q = Customer::orderBy('nama', 'ASC');

        if (request('search')) {
            $q->where('nama', 'like', '%'.request('search').'%');
        }

        $data = $q->get();

        if (request('fetch')) {
            return response()->json($data);
        } else {
            $view_data = [
                'page_title' => 'List Customer',
                'active' => 'customer',
                'data' => $data,
            ];

            return view('customer.index', $view_data);
        }
    }
    // end function index

    public function create()
    {
        $view_data = [
            'page_title' => 'Input Data Customer',
            'active' => 'customer',
        ];
        return view('customer.create', $view_data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:255',
            'alamat' => 'required|max:255',
            'telepon' => 'required|max:20',
            'email' => 'required|email|max:255',
            'slug' => 'required|unique:customers',
        ]);
        Customer::create($validated);

        $request->session()->flash('customerSuccess', 'Data Customer berhasil disimpan');

        return redirect('/customer');
    }

    public function edit(Customer $customer)
    {
        $view_data = [
            'page_title' => 'Edit Data Customer',
            'active' => 'customer',
            'data' => $customer,
        ];
        return view('customer.edit', $view_data);
    }

    public function update(Request $request, Customer $customer)
    {
        $rules = [
            'nama' => 'required|max:255',
            'harga' => 'required|numeric|gte:1',
            'stok' => 'required|numeric|gte:1'
        ];
        if ($request->slug != $customer->slug) {
            $rules['slug'] = 'required|unique:customers';
        }

        $validated = $request->validate($rules);

        Customer::where('id', $customer->id)
            ->update($validated);

        return redirect('/customer')->with('customerSuccess', 'Data Customer berhasil diperbarui');
    }

    public function destroy(Customer $customer)
    {
        Customer::destroy($customer->id);

        return redirect('/customer')->with('customerSuccess', 'Data Customer berhasil dihapus');
    }
}
