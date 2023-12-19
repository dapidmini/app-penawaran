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
            $table->unsignedInteger('qty');
            $table->unsignedInteger('harga_jual'); // harga jual per satuan
            $table->string('diskon_satuan'); // diskon per satuan barang
            $table->string('biaya_satuan'); // biaya per satuan barang
            $table->string('diskon_subtotal'); // diskon utk subtotal
            $table->string('biaya_subtotal'); // biaya utk subtotal
            $table->unsignedInteger('subtotal'); // subtotal harga jual (krn bisa didiskon di subtotalnya)
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
