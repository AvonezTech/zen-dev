<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name',
        'contact_name',
        'contact_email',
        'contact_number',
    ];

    // Relationships

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function incomes(): HasMany
    {
        return $this->hasMany(Income::class);
    }
}
