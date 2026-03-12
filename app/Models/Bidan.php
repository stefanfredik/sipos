<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bidan extends Model
{
    use HasUlid, SoftDeletes, HasFactory;

    protected $table = 'bidan';

    protected $fillable = [
        'user_id',
        'nama_bidan',
        'foto_bidan',
        'alamat',
        'no_telp',
        'jenis_kelamin',
    ];

    /**
     * Get the user that owns the bidan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
