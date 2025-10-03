<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'organizer',
        'priority',
        'max_participants',
        'current_participants',
        'requirements',
        'contact_person',
        'contact_phone',
        'is_public',
        'is_completed',
        'created_by',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_public' => 'boolean',
        'is_completed' => 'boolean',
        'max_participants' => 'integer',
        'current_participants' => 'integer',
        'created_by' => 'integer',
    ];

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_public', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('event_date', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('event_date', now()->month)
                    ->whereYear('event_date', now()->year);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('d M Y');
    }

    public function getFormattedTimeAttribute()
    {
        $start = $this->start_time ? Carbon::parse($this->start_time)->format('H:i') : '';
        $end = $this->end_time ? Carbon::parse($this->end_time)->format('H:i') : '';
        
        if ($start && $end) {
            return $start . ' - ' . $end . ' WIB';
        } elseif ($start) {
            return $start . ' WIB';
        }
        
        return 'Waktu belum ditentukan';
    }

    public function getCategoryLabelAttribute()
    {
        $labels = [
            'rapat' => 'RAPAT',
            'pelayanan' => 'PELAYANAN',
            'olahraga' => 'OLAHRAGA',
            'gotong_royong' => 'GOTONG ROYONG',
            'keagamaan' => 'KEAGAMAAN',
            'lainnya' => 'LAINNYA'
        ];

        return $labels[$this->category] ?? strtoupper($this->category);
    }

    public function getCategoryColorAttribute()
    {
        $colors = [
            'rapat' => 'green',
            'pelayanan' => 'blue',
            'olahraga' => 'purple',
            'gotong_royong' => 'yellow',
            'keagamaan' => 'red',
            'lainnya' => 'gray'
        ];

        return $colors[$this->category] ?? 'gray';
    }

    public function getIsOngoingAttribute()
    {
        $now = now();
        $startTime = Carbon::parse($this->event_date->format('Y-m-d') . ' ' . $this->start_time);
        $endTime = $this->end_time ? 
            Carbon::parse($this->event_date->format('Y-m-d') . ' ' . $this->end_time) : 
            $startTime->copy()->addHours(2);

        return $now->between($startTime, $endTime);
    }
}