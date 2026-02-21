<?php

namespace App\Filament\Actions;

use Closure;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Validator;

abstract class BaseAction
{
    abstract protected static function name(array $bindings): string;
    abstract protected static function schema(array $bindings): array|Closure;
    abstract protected static function action(array $bindings): Closure;
    
    protected static function bindingRules(): array{
        return [];
    }

    protected static function mutateFilamentAction(Action $filamentAction): Action
    {
        return $filamentAction;
    }

    public static function make(array $bindings = []): Action
    {
        $bindings = Validator::validate(
            $bindings,
            self::bindingRules()
        );

        $filamentAction = Action::make(static::name($bindings))
            ->schema(static::schema($bindings))
            ->action(static::action($bindings));

        return static::mutateFilamentAction($filamentAction);
    }
}
