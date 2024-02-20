<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_produk');
            $table->string('nama_pelanggan');
            $table->integer('jmlh_org');
            $table->string('nomor_unik');
            $table->date('checkin');
            $table->date('checkout');
            $table->integer('harga_awal');
            $table->integer('total_harga');
            $table->integer('uang_bayar');
            $table->integer('uang_kembali');
            $table->timestamps();

            $table->foreign('id_produk')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
