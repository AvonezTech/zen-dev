<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'address',
    ];

    // Relationships

    public function expenditures(): HasMany
    {
        return $this->hasMany(Expenditure::class);
    }
}
