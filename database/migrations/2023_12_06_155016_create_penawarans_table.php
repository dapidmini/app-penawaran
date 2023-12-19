<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenawaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penawarans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('user_id'); // ID user yang menginputkan data
            $table->string('penjualan_kotor'); // nilai penjualan kotor (hasil penjumlahan subtotal semua barang)
            $table->string('diskon_kumulatif'); // diskon yg diberikan terhadap nilai penjualan akhir (kotor)
            $table->string('biaya_kumulatif'); // biaya yg dibebankan terhadap nilai penjualan akhir (kotor)
            $table->string('profit'); // profit akhir
            $table->timestamp('tgl_pengajuan');
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
        Schema::dropIfExists('penawarans');
    }
}
