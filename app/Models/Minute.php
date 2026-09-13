<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Minute extends Model
{
    use SoftDeletes;

    protected $table = 'minutes';

    protected $fillable = [
        'title',
        'meeting_date',
        'venue',
        'agenda',
        'discussion',
        'resolutions',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'meeting_date' => 'date',
        ];
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
