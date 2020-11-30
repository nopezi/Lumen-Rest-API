<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePosting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('posting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('id_kategori');
            $table->string('judul', 200)->nullable();
            $table->text('isi')->nullable();
            $table->date('created_at');
            $table->date('updated_at');
        });

        Schema::create('kategori', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('nama_kategori')->nullable();
            $table->date('created_at');
            $table->date('updated_at');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
