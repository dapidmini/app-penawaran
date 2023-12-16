<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DetailPenawaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penawaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penawaran_id');
            $table->foreignId('barang_id');
            $table->unsignedInteger('jumlah');
            $table->unsignedInteger('harga'); // harga jual per satuan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_penawaran');
    }
}
