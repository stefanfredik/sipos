<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Posyandu extends Model
{
    use HasUlid, SoftDeletes, HasFactory;

    protected $table = 'posyandu';

    protected $fillable = [
        'nama_posyandu',
        'lokasi',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the kader for the posyandu.
     */
    public function kader(): HasMany
    {
        return $this->hasMany(Kader::class);
    }
}
