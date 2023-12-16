<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $view_data = [
            'page_title' => 'List User',
            'active' => 'user',
            'data' => User::all(),
        ];
        return view('user.index', $view_data);
    }

    public function create()
    {
        $view_data = [
            'page_title' => 'Buat User Baru',
            'active' => 'user',
        ];
        return view('user.create', $view_data);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|min:3|max:255|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:5|max:255',
            'level' => 'nullable'
        ]);

        // $validatedData['password'] = bcrypt($validatedData['password']);
        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        $request->session()->flash('userSuccess', 'User berhasil dibuat');

        return redirect('/user');
    }

    public function edit(User $user)
    {
        $view_data = [
            'page_title' => 'Edit Data User',
            'active' => 'user',
            'data' => $user,
        ];
        return view('user.edit', $view_data);
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'nama' => 'required|max:255',
            'harga' => 'required|numeric|gte:1',
            'stok' => 'required|numeric|gte:1'
        ];
        if ($request->slug != $user->slug) {
            $rules['slug'] = 'required|unique:users';
        }

        $validated = $request->validate($rules);

        User::where('id', $user->id)
            ->update($validated);

        return redirect('/user')->with('userSuccess', 'Data User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        User::destroy($user->id);

        // $request->session()->flash('userSuccess', 'Data User berhasil disimpan');

        return redirect('/user')->with('userSuccess', 'Data User berhasil dihapus');
    }
}
