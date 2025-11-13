<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jenis_hewan', function (Blueprint $table) {
            $table->id('idjenis_hewan');
            $table->string('nama_jenis_hewan', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_hewan');
    }
};
