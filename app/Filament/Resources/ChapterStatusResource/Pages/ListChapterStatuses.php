<?php

namespace App\Filament\Resources\ChapterStatusResource\Pages;

use App\Filament\Resources\ChapterStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChapterStatuses extends ListRecords
{
    protected static string $resource = ChapterStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
