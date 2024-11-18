<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    // Ganti mutateFormDataBeforeCreate dengan handleRecordCreation
    protected function handleRecordCreation(array $data): Model
    {
        // Simpan data user
        $userData = [
            'name' => $data['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
            'role' => 'student'
        ];
        unset($data['user']);

        // Buat student
        $student = static::getModel()::create($data);

        // Buat user yang terkait
        $student->user()->create($userData);

        return $student;
    }
}
