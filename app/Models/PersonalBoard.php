<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class PersonalBoard extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'slug',
        'archived_at',
    ];

    protected function casts()
    {
        return [
            'archived_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function(PersonalBoard $personalBoard){
            $personalBoard->slug = Str::slug($personalBoard->name) . '-' . Str::random(8);
        });
    }

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): MorphMany
    {
        return $this->morphMany(Task::class, 'taskable');
    }
}
