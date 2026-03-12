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
        Schema::create('jadwal_posyandu', function (Blueprint $table) {
            $table->char('id', 26)->primary()->comment('ULID');
            $table->char('posyandu_id', 26);
            $table->char('kader_id', 26)->comment('Kader yang mengusulkan');
            $table->char('bidan_id', 26)->nullable()->comment('Bidan yang memvalidasi');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->enum('status', ['draft', 'validated', 'rejected', 'completed'])->default('draft');
            $table->text('catatan_bidan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('posyandu_id')->references('id')->on('posyandu');
            $table->foreign('kader_id')->references('id')->on('kader');
            $table->foreign('bidan_id')->references('id')->on('bidan');
            
            $table->index('tanggal', 'idx_tanggal');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_posyandu');
    }
};
