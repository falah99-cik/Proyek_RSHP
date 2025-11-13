<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('temu_dokter', function (Blueprint $table) {
            $table->id('idtemu');
            $table->integer('no_urut');
            $table->timestamp('waktu_daftar')->useCurrent();
            $table->date('tanggal_temu');
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('idpet');
            $table->unsignedBigInteger('idrole_user');

            $table->timestamps();

            $table->foreign('idpet')->references('idpet')->on('pet');
            $table->foreign('idrole_user')->references('id')->on('role_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temu_dokter');
    }
};
