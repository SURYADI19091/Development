<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'agenda_id',
        'participant_name',
        'participant_email',
        'participant_phone',
        'registration_date',
        'attendance_status',
        'notes',
    ];

    protected $casts = [
        'agenda_id' => 'integer',
        'registration_date' => 'datetime',
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class, 'agenda_id');
    }
}