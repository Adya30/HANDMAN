<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailTugas extends Model
{
    use HasUlids;

    protected $table = 'detail_tugass';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tugas_id',
        'user_id',
        'grup_kerja_id',
    ];

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function grupKerja(): BelongsTo
    {
        return $this->belongsTo(GrupKerja::class, 'grup_kerja_id');
    }
}
