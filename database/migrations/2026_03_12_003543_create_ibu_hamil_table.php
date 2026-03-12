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
        Schema::create('ibu_hamil', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->char('user_id', 26)->nullable()->comment('Akun peserta (optional)');
            $table->string('nik', 16)->unique();
            $table->string('nama', 100);
            $table->string('foto', 255)->nullable();
            $table->date('tgl_lahir');
            $table->tinyInteger('kehamilan_keberapa')->unsigned()->default(1);
            $table->tinyInteger('jarak_anak')->unsigned()->nullable()->comment('Bulan');
            $table->tinyInteger('usia_kehamilan')->unsigned()->comment('Minggu');
            $table->string('no_telp', 20);
            $table->text('alamat');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('nik');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibu_hamil');
    }
};
