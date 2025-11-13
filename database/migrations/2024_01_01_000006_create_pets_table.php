<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pet', function (Blueprint $table) {
            $table->id('idpet');
            $table->foreignId('idpemilik')->constrained('pemilik', 'idpemilik')->onDelete('cascade');
            $table->string('nama', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Jantan', 'Betina']);
            $table->string('warna_tanda', 100)->nullable();
            $table->foreignId('idras_hewan')->constrained('ras_hewan', 'idras_hewan')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pet');
    }
};
