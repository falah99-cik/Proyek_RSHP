<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id('idrekam_medis');
            $table->foreignId('idpet')->constrained('pet', 'idpet')->onDelete('cascade');
            $table->foreignId('dokter_pemeriksa')->constrained('user', 'iduser')->onDelete('restrict');
            $table->text('diagnosa');
            $table->text('tindakan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekam_medis');
    }
};
