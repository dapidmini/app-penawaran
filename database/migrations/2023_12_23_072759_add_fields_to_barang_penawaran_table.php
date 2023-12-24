<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBarangPenawaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barang_penawaran', function (Blueprint $table) {
            $table->unsignedInteger('qty');
            $table->unsignedInteger('hargaBarang');
            $table->unsignedInteger('hargaJualSatuan');
            $table->unsignedInteger('diskonSatuanOri');
            $table->unsignedInteger('diskonSatuanValue');
            $table->unsignedInteger('biayaSatuanOri');
            $table->unsignedInteger('biayaSatuanValue');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang_penawaran');
    }
}
