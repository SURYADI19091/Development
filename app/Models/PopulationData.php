<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PopulationData extends Model
{
    use HasFactory;

    protected $table = 'population_data';

    protected $fillable = [
        'serial_number',
        'family_card_number',
        'identity_card_number',
        'name',
        'birth_place',
        'birth_date',
        'age',
        'address',
        'settlement_id',
        'gender',
        'marital_status',
        'family_relationship',
        'head_of_family',
        'religion',
        'occupation',
        'residence_type',
        'independent_family_head',
        'district',
        'regency',
        'province',
        'status',
        'death_date',
        'death_cause',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'serial_number' => 'integer',
    ];

    public function settlement(): BelongsTo
    {
        return $this->belongsTo(Settlement::class, 'settlement_id');
    }
}
