<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Barang;
use App\Models\Customer;
use Illuminate\Http\Request;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class HomeController extends Controller
{
    //
    public function index()
    {
        if (auth()->check()) {
            return redirect('/penawaran');
        } else {
            return redirect('/login');
        }
    }

    public function checkSlug(Request $request)
    {
        $slug = '';
        if ($request->module == 'barang') {
            $slug = SlugService::createSlug(Barang::class, 'slug', $request->value);
        } elseif ($request->module == 'user') {
            $slug = SlugService::createSlug(User::class, 'slug', $request->value);
        } elseif ($request->module == 'customer') {
            $slug = SlugService::createSlug(Customer::class, 'slug', $request->value);
        }

        return response()->json(['slug' => $slug, 'params' => $request->module.';'.$request->value]);
    }
}
