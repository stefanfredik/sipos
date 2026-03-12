<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kader extends Model
{
    use HasUlid, SoftDeletes, HasFactory;

    protected $table = 'kader';

    protected $fillable = [
        'user_id',
        'nama_kader',
        'foto_kader',
        'alamat',
        'no_telp',
        'jenis_kelamin',
    ];

    /**
     * Get the user that owns the kader.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the posyandu the kader belongs to.
     */
    public function posyandu(): BelongsTo
    {
        return $this->belongsTo(Posyandu::class);
    }
}
