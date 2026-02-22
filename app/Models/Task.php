<?php

namespace App\Models;

use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStatus;
use Filament\Forms\Components\RichEditor\MentionProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tilto\Commentable\Contracts\Commentable;
use Tilto\Commentable\Traits\HasComments;

class Task extends Model implements Commentable
{
    use HasComments;

    protected $fillable = [
        'project_id',
        'parent_id',
        'title',
        'status',
        'priority',
        'estimated_days',
        'description',
        'assigned_to_id',
        'start_date',
        'due_date',
        'completed_at',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'start_date' => 'date',
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    // Comment mentions
    public function getCommentMentionProviders(): array|null
    {
        return [
            MentionProvider::make('@')
                ->getSearchResultsUsing(function (string $search): array {
                    return User::query()
                        ->where('name', 'like', "%{$search}%")
                        ->orderBy('name')
                        ->limit(10)
                        ->pluck('name', 'id')
                        ->all();
                })
                ->getLabelsUsing(function (array $ids): array {
                    return User::query()
                        ->whereIn('id', $ids)
                        ->pluck('name', 'id')
                        ->all();
                }),
        ];
    }

    // Relationship

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_id', 'id');
    }
}
