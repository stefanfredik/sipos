<?php

namespace App\Models;

use App\Helpers\UmurHelper;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Lansia extends Model
{
    use HasUlid, SoftDeletes, HasFactory;

    protected $table = 'lansia';

    protected $fillable = [
        'user_id',
        'nik',
        'nama',
        'foto',
        'tgl_lahir',
        'jenis_kelamin',
        'no_telp',
        'alamat',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get computed umur (tahun).
     */
    protected function umur(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tgl_lahir ? UmurHelper::hitungUmurLansia($this->tgl_lahir) : null,
        );
    }

    /**
     * Get formatted umur string.
     */
    protected function umurFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tgl_lahir ? UmurHelper::formatUmur($this->tgl_lahir) : null,
        );
    }

    /**
     * Get full URL for foto.
     */
    protected function fotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->foto ? Storage::disk('public')->url($this->foto) : null,
        );
    }

    /**
     * Get the user that owns the lansia.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the lansia's examinations.
     */
    public function pemeriksaan(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Pemeriksaan::class, 'peserta');
    }
}
