<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $view_data = [
            'title' => 'Create User',
            'page_header' => 'Buat User Baru'
        ];
        return view('user.register', $view_data);
    }
}
