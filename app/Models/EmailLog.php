<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'application_status_history_id',
        'to_email',
        'channel',
        'subject',
        'body',
        'status',
        'sent_at',
        'attempts',
        'error_message',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function statusHistory()
    {
        return $this->belongsTo(ApplicationStatusHistory::class, 'application_status_history_id');
    }
}
