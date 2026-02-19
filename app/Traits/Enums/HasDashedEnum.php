<?php

namespace App\Traits\Enums;

use Illuminate\Contracts\Support\Htmlable;

trait HasDashedEnum
{
    public function getLabel(): string|Htmlable|null
    {
        $label = str_replace('-', ' ', $this->value);
        $label = ucwords($label);

        return $label;
    }
}