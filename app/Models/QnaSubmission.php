<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QnaSubmission extends Model
{
    protected $fillable = [
        'nama',
        'email_or_phone',
        'pertanyaan',
        'jawaban',
        'status',
    ];
}
