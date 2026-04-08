<?php

namespace App\Models;

use App\Enums\Transaction\TransactionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expenditure extends Model
{
    protected $fillable = [
        'supplier_id',
        'user_id',
        'project_id',
        'title',
        'description',
        'amount',
        'status',
    ];

    protected function casts()
    {
        return [
            'status' => TransactionStatus::class,
        ];
    }

    // Relationship

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
