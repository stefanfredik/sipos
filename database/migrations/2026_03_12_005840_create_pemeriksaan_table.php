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
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->char('id', 26)->primary()->comment('ULID');
            $table->char('jadwal_posyandu_id', 26);
            $table->char('kader_id', 26)->nullable()->comment('Kader yang menginput');
            $table->char('bidan_id', 26)->nullable()->comment('Bidan yang memeriksa');
            $table->enum('peserta_type', ['ibu_hamil', 'balita', 'lansia']);
            $table->char('peserta_id', 26);
            $table->date('tgl_pemeriksaan');

            // Vital Signs (semua kategori)
            $table->decimal('berat_badan', 5, 2)->nullable()->comment('kg');
            $table->decimal('tinggi_badan', 5, 2)->nullable()->comment('cm');
            $table->unsignedSmallInteger('sistole')->nullable()->comment('mmHg');
            $table->unsignedSmallInteger('diastole')->nullable()->comment('mmHg');

            // Khusus Ibu Hamil
            $table->unsignedTinyInteger('usia_kehamilan')->nullable()->comment('Minggu');
            $table->decimal('lila', 5, 2)->nullable()->comment('Lingkar Lengan Atas (cm)');
            $table->boolean('kek_status')->nullable()->comment('Status KEK');
            $table->boolean('mt_bumil')->nullable()->comment('Pemberian MT Bumil');
            $table->boolean('ttd_status')->nullable()->comment('Konsumsi TTD');
            $table->boolean('kelas_bumil')->nullable()->comment('Mengikuti kelas bumil');

            // Khusus Balita
            $table->decimal('lingkar_kepala', 5, 2)->nullable()->comment('cm');
            $table->decimal('lingkar_lengan', 5, 2)->nullable()->comment('cm');
            $table->boolean('obat_cacing')->nullable();
            $table->string('vitamin', 100)->nullable();

            // Khusus Lansia
            $table->decimal('lingkar_perut', 5, 2)->nullable()->comment('cm');
            $table->string('jenis_keluhan', 255)->nullable();
            $table->string('obat_vitamin', 255)->nullable();

            // Edukasi & Catatan
            $table->text('edukasi')->nullable()->comment('Catatan edukasi dari bidan');
            $table->text('keterangan')->nullable();
            $table->boolean('hadir')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('jadwal_posyandu_id')->references('id')->on('jadwal_posyandu');
            $table->foreign('kader_id')->references('id')->on('kader');
            $table->foreign('bidan_id')->references('id')->on('bidan');
            
            $table->index(['peserta_type', 'peserta_id'], 'idx_peserta');
            $table->index('tgl_pemeriksaan', 'idx_tgl');
            $table->index('jadwal_posyandu_id', 'idx_jadwal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};
