<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity_type',
        'description',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'model_id' => 'integer',
    ];

    public $timestamps = false;
    
    protected $dates = ['created_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function model()
    {
        if ($this->model_type && $this->model_id) {
            return $this->morphTo('model', 'model_type', 'model_id');
        }
        return null;
    }
}