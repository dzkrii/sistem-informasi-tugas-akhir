<?php

namespace App\Filament\Resources\TitleSubmissionResource\Pages;

use App\Filament\Resources\TitleSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTitleSubmissions extends ListRecords
{
    protected static string $resource = TitleSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
