<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pemeriksaan extends Model
{
    use HasUlid, SoftDeletes, HasFactory;

    protected $table = 'pemeriksaan';

    protected $fillable = [
        'jadwal_posyandu_id',
        'kader_id',
        'bidan_id',
        'peserta_type',
        'peserta_id',
        'tgl_pemeriksaan',
        'berat_badan',
        'tinggi_badan',
        'sistole',
        'diastole',
        'usia_kehamilan',
        'lila',
        'kek_status',
        'mt_bumil',
        'ttd_status',
        'kelas_bumil',
        'lingkar_kepala',
        'lingkar_lengan',
        'obat_cacing',
        'vitamin',
        'lingkar_perut',
        'jenis_keluhan',
        'obat_vitamin',
        'edukasi',
        'keterangan',
        'hadir',
    ];

    protected $casts = [
        'tgl_pemeriksaan' => 'date',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
        'sistole' => 'integer',
        'diastole' => 'integer',
        'usia_kehamilan' => 'integer',
        'lila' => 'decimal:2',
        'kek_status' => 'boolean',
        'mt_bumil' => 'boolean',
        'ttd_status' => 'boolean',
        'kelas_bumil' => 'boolean',
        'lingkar_kepala' => 'decimal:2',
        'lingkar_lengan' => 'decimal:2',
        'obat_cacing' => 'boolean',
        'lingkar_perut' => 'decimal:2',
        'hadir' => 'boolean',
    ];

    public function peserta(): MorphTo
    {
        return $this->morphTo();
    }

    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(JadwalPosyandu::class, 'jadwal_posyandu_id');
    }

    public function jadwalPosyandu(): BelongsTo
    {
        return $this->belongsTo(JadwalPosyandu::class, 'jadwal_posyandu_id');
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(Kader::class);
    }

    public function bidan(): BelongsTo
    {
        return $this->belongsTo(Bidan::class);
    }
}
