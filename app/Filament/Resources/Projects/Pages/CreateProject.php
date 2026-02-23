<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = ProjectResource::class;

    protected function getSteps(): array
    {
        return ProjectForm::getSteps();
    }
}
