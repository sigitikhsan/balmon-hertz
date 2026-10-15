<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'period_id',
        'field_id',
        'title',
        'slug',
        'type',
        'duration',
        'quota',
        'description',
        'requirements',
        'jurusan',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }
}
