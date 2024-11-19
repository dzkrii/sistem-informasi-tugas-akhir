<?php

namespace App\Filament\Resources\KaprodiResource\Pages;

use App\Filament\Resources\KaprodiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateKaprodi extends CreateRecord
{
    protected static string $resource = KaprodiResource::class;

    // Ganti mutateFormDataBeforeCreate dengan handleRecordCreation
    protected function handleRecordCreation(array $data): Model
    {
        // Simpan data user
        $userData = [
            'name' => $data['name'],
            'email' => $data['user']['email'],
            'password' => Hash::make($data['user']['password']),
            'role' => 'kaprodi'
        ];
        unset($data['user']);

        // Buat student
        $student = static::getModel()::create($data);

        // Buat user yang terkait
        $student->user()->create($userData);

        return $student;
    }
}
