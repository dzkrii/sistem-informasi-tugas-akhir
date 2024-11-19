<?php

namespace App\Filament\Resources\ChapterStatusResource\Pages;

use App\Filament\Resources\ChapterStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChapterStatus extends EditRecord
{
    protected static string $resource = ChapterStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
