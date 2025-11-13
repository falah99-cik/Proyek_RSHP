<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ras_hewan', function (Blueprint $table) {
            $table->id('idras_hewan');
            $table->string('nama_ras', 100);
            $table->foreignId('idjenis_hewan')->constrained('jenis_hewan', 'idjenis_hewan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ras_hewan');
    }
};
