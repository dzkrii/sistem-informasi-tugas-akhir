<?php

namespace App\Filament\Resources\LecturerResource\Pages;

use App\Filament\Resources\LecturerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateLecturer extends CreateRecord
{
    protected static string $resource = LecturerResource::class;

    // Ganti mutateFormDataBeforeCreate dengan handleRecordCreation
    protected function handleRecordCreation(array $data): Model
    {
        // Simpan data user
        $userData = [
            'name' => $data['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
            'role' => 'lecturer'
        ];
        unset($data['user']);

        // Buat student
        $student = static::getModel()::create($data);

        // Buat user yang terkait
        $student->user()->create($userData);

        return $student;
    }
}
