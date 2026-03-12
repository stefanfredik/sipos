<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalPosyandu extends Model
{
    use HasUlid, SoftDeletes, HasFactory;

    protected $table = 'jadwal_posyandu';

    protected $fillable = [
        'posyandu_id',
        'kader_id',
        'bidan_id',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'catatan_bidan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_mulai' => 'string',
        'waktu_selesai' => 'string',
        'status' => \App\Enums\JadwalStatus::class,
    ];

    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }

    public function kader(): BelongsTo
    {
        return $this->belongsTo(Kader::class);
    }

    public function bidan(): BelongsTo
    {
        return $this->belongsTo(Bidan::class);
    }

    public function pemeriksaan(): HasMany
    {
        return $this->hasMany(Pemeriksaan::class);
    }

    // Scopes

    public function scopeValidated($query)
    {
        return $query->where('status', \App\Enums\JadwalStatus::Validated);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('tanggal', '>=', now()->toDateString())
            ->whereIn('status', [\App\Enums\JadwalStatus::Draft, \App\Enums\JadwalStatus::Validated])
            ->orderBy('tanggal', 'asc');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month);
    }
}
