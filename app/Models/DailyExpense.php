<?php

namespace App\Models;

use App\Enums\ExpenseCategory;
use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyExpense extends Model
{
    use SoftDeletes;

    protected $table = 'daily_expenses';

    protected $fillable = [
        'date',
        'category',
        'title',
        'description',
        'amount',
        'paid_amount',
        'due_amount',
        'payment_method',
        'payment_status',
        'user_id',
        'project_id',
        'vendor',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_amount' => 'decimal:2',
            'status' => TransactionStatus::class,
            'category' => ExpenseCategory::class,
            'payment_method' => PaymentMethod::class,
        ];
    }

    protected static function booted()
    {
        static::saving(function (DailyExpense $expense) {
            $total = (float) $expense->amount;
            $paid = (float) $expense->paid_amount;

            if ($paid <= 0) {
                $expense->payment_status = 'unpaid';
            } elseif ($paid < $total) {
                $expense->payment_status = 'partial';
            } else {
                $expense->payment_status = 'paid';
            }

            $expense->due_amount = max(0, $total - $paid);
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', TransactionStatus::Completed);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }
}
