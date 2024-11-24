<?php

namespace App\Filament\Resources\TitleSubmissionResource\Pages;

use App\Filament\Resources\TitleSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTitleSubmission extends CreateRecord
{
    protected static string $resource = TitleSubmissionResource::class;

    protected function afterCreate(): void
    {
        // Ambil thesis dari submission yang baru dibuat
        $thesis = $this->record->thesis;

        // Cek apakah thesis sudah memiliki chapter status
        if ($thesis->chapterStatuses()->count() === 0) {
            $thesis->createInitialChapters();
        }
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Jika mahasiswa yang membuat, set thesis_id otomatis
        if (auth()->user()->isStudent()) {
            $student = auth()->user()->userable;
            $thesis = $student->thesis;

            if ($thesis) {
                $data['thesis_id'] = $thesis->id;
                $data['status'] = 'pending';
            }
        }

        return $data;
    }
}
