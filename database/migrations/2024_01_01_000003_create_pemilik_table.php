<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemilik', function (Blueprint $table) {
            $table->id('idpemilik');
            $table->foreignId('iduser')->constrained('user', 'iduser')->onDelete('cascade');
            $table->string('no_wa', 20);
            $table->text('alamat');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemilik');
    }
};
