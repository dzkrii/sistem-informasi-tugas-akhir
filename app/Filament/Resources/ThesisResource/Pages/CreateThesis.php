<?php

namespace App\Filament\Resources\ThesisResource\Pages;

use App\Filament\Resources\ThesisResource;
use App\Models\Student;
use App\Models\Thesis;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateThesis extends CreateRecord
{
    protected static string $resource = ThesisResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Cek apakah mahasiswa sudah memiliki thesis
        $existingThesis = Thesis::where('student_id', $data['student_id'])->first();

        if ($existingThesis) {
            // Tampilkan notifikasi
            Notification::make()
                ->title('Gagal Membuat Thesis')
                ->body("Mahasiswa sudah memiliki thesis.")
                ->danger()
                ->send();

            // Redirect atau stop proses
            $this->halt();
        }

        // Validasi departemen untuk Kaprodi (seperti sebelumnya)
        if (auth()->user()->role === 'kaprodi') {
            $kaprodi = auth()->user()->userable;
            $student = Student::findOrFail($data['student_id']);

            if ($student->department_id !== $kaprodi->department_id) {
                Notification::make()
                    ->title('Akses Ditolak')
                    ->body('Anda hanya dapat membuat thesis untuk mahasiswa di departemen Anda.')
                    ->danger()
                    ->send();

                $this->halt();
            }
        }

        return $data;
    }
}
