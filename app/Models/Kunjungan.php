<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $fillable = [
        'ticket_code',
        'nama',
        'no_telpon',
        'jenis_kunjungan',
        'instansi',
        'keperluan',
        'is_anonymous',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ticket_code)) {
                $datePrefix = date('Ymd');
                $countToday = static::whereDate('created_at', today())->count() + 1;
                $model->ticket_code = 'KNJ-'.$datePrefix.'-'.str_pad((string) $countToday, 4, '0', STR_PAD_LEFT);
            }

            if (empty(trim((string) $model->nama)) || strtolower(trim((string) $model->nama)) === 'anonymous' || strtolower(trim((string) $model->nama)) === 'anonim') {
                $model->nama = 'Anonymous';
                $model->is_anonymous = true;
            }
        });
    }
}
