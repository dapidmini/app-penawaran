<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use App\Models\Customer;
use App\Models\DetailPenawaran;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Super Administrator',
            'username' => 'admin01',
            'email' => 'admin01@gmail.com',
            'password' => bcrypt('12345'),
            'level' => 'superadmin'
        ]);

        Barang::create([
            'nama' => 'Lampu LED Phillips 12 Watt',
            'slug' => 'lampu-led-phillips-12-watt',
            'harga' => 25000,
            'stok' => 50
        ]);
        Barang::create([
            'nama' => 'CCTV Panasonic FHD Bluetooth 5',
            'slug' => 'cctv-panasonic-fhd-bluetooth-5',
            'harga' => 100000,
            'stok' => 25
        ]);

        Customer::create([
            'nama' => 'Toko ABC',
            'alamat' => 'Jalan Ngagel Jaya Selatan no.100',
            'telepon' => '0811222333444',
            'email' => 'toko.abc.surabaya@gmail.com',
            'slug' => 'toko-abc',
        ]);
        Customer::create([
            'nama' => 'Indomaret Ngagel 212',
            'alamat' => 'Jalan Ngagel Madya no.10, Surabaya',
            'telepon' => '0812345678901',
            'email' => 'toko.indomaret.212@gmail.com',
            'slug' => 'indomaret-ngagel-212',
        ]);

        // \App\Models\Penawaran::factory(5)->create();

        // DetailPenawaran::create([

        // ])
    }
}
