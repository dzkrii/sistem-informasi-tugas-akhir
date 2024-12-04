<?php

namespace App\Filament\Resources\TitleSubmissionResource\Pages;

use App\Filament\Resources\TitleSubmissionResource;
use App\Models\Thesis;
use App\Models\TitleSubmission;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTitleSubmission extends CreateRecord
{
    protected static string $resource = TitleSubmissionResource::class;

    // Override method create untuk tambahan validasi
    // public function create(array $data): void
    // {
    //     // Pastikan hanya mahasiswa yang bisa membuat
    //     if (!auth()->user()->isStudent()) {
    //         Notification::make()
    //             ->title('Akses Ditolak')
    //             ->body('Hanya mahasiswa yang dapat mengajukan judul.')
    //             ->danger()
    //             ->send();

    //         $this->halt();
    //         return;
    //     }

    //     // Ambil data mahasiswa yang sedang login
    //     $student = auth()->user()->userable;

    //     // Cek apakah mahasiswa sudah memiliki thesis
    //     $existingThesis = Thesis::where('student_id', $student->id)->first();

    //     if (!$existingThesis) {
    //         Notification::make()
    //             ->title('Tidak Dapat Mengajukan Judul')
    //             ->body('Anda belum dialokasikan dosen pembimbing. Silakan hubungi Kaprodi.')
    //             ->danger()
    //             ->send();

    //         $this->halt();
    //         return;
    //     }

    //     // Cek apakah sudah pernah mengajukan judul
    //     $existingSubmission = TitleSubmission::whereHas('thesis', function ($query) use ($student) {
    //         $query->where('student_id', $student->id);
    //     })->first();

    //     if ($existingSubmission) {
    //         Notification::make()
    //             ->title('Pengajuan Judul')
    //             ->body('Anda sudah pernah mengajukan judul. Silakan tunggu persetujuan.')
    //             ->warning()
    //             ->send();

    //         $this->halt();
    //         return;
    //     }

    //     // Lanjutkan proses create jika lolos validasi
    //     parent::create($data);
    // }

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
