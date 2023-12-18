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
            // $table->string('nama_customer');
            // $table->string('alamat_customer');
            // $table->string('email_customer');
            // $table->string('telepon_customer');
            $table->unsignedBigInteger('user_id'); // ID user yang menginputkan data
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
