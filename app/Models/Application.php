<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    public const STATUS_MENUNGGU = 'menunggu';
    public const STATUS_DITERIMA = 'diterima';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_PERBAIKAN = 'perlu_perbaikan';

    protected $fillable = [
        'application_no',
        'job_id',
        'nama',
        'email',
        'no_hp',
        'ttl_tempat',
        'ttl_tanggal',
        'cv_path',
        'surat_path',
        'status',
        'rejection_reason',
        'submitted_at',
        'decided_at',
        'decided_by',
    ];

    protected $casts = [
        'ttl_tanggal' => 'date',
        'submitted_at' => 'datetime',
        'decided_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function decider()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function histories()
    {
        return $this->hasMany(ApplicationStatusHistory::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }
}
