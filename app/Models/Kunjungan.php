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

    /**
     * Generate a guaranteed unique ticket code for today.
     */
    public static function generateUniqueTicketCode(): string
    {
        $datePrefix = date('Ymd');

        // Look for the highest sequence number from today's ticket codes
        $latestTicket = static::where('ticket_code', 'LIKE', "KNJ-{$datePrefix}-%")
            ->orderByDesc('id')
            ->value('ticket_code');

        $nextSeq = 1;
        if ($latestTicket && preg_match('/KNJ-\d+-(\d+)/', $latestTicket, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }

        // Check and increment until an unused code is found
        do {
            $candidate = 'KNJ-'.$datePrefix.'-'.str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
            $exists = static::where('ticket_code', $candidate)->exists();
            if ($exists) {
                $nextSeq++;
            }
        } while ($exists);

        return $candidate;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ticket_code)) {
                $model->ticket_code = static::generateUniqueTicketCode();
            }

            if (empty(trim((string) $model->nama)) || strtolower(trim((string) $model->nama)) === 'anonymous' || strtolower(trim((string) $model->nama)) === 'anonim') {
                $model->nama = 'Anonymous';
                $model->is_anonymous = true;
            }
        });
    }
}
