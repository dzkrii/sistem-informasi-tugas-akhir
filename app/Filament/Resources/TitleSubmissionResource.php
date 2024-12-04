<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TitleSubmissionResource\Pages;
use App\Filament\Resources\TitleSubmissionResource\RelationManagers;
use App\Models\Thesis;
use App\Models\TitleSubmission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TitleSubmissionResource extends Resource
{
    protected static ?string $model = TitleSubmission::class;

    protected static ?string $navigationLabel = 'Pengajuan Judul';
    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationGroup = 'Manajemen Tugas Akhir';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('thesis_id')
                //     ->required()
                //     ->relationship('thesis', 'id'),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->disabled(fn() => !auth()->user()->isStudent())
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->disabled(fn() => !auth()->user()->isStudent())
                    ->columnSpanFull(),
                // Forms\Components\TextInput::make('status')
                //     ->required()
                //     ->maxLength(255)
                //     ->default('pending'),
                // * Field status dan rejection_note hanya visible untuk dosen
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'revision' => "Revision",
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->disabled(fn() => !auth()->user()->isLecturer())
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull()
                    ->disabled(fn() => !auth()->user()->isLecturer()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('thesis.student.name')
                    ->label('Mahasiswa')
                    ->sortable()
                    // visible ketika yang login bukan mahasiswa
                    ->visible(fn() => !auth()->user()->isStudent()),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'revision' => 'warning',
                        'rejected' => 'danger',
                        'accepted' => 'success',
                    }),
                Tables\Columns\TextColumn::make('note')
                    ->label('Note')
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Jika user adalah mahasiswa, tampilkan hanya judul miliknya
        if (auth()->user()->isStudent()) {
            $student = auth()->user()->userable;
            return $query->whereHas('thesis', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            });
        }

        // Jika user adalah dosen, tampilkan judul mahasiswa bimbingannya
        if (auth()->user()->isLecturer()) {
            $lecturer = auth()->user()->userable;
            return $query->whereHas('thesis', function ($query) use ($lecturer) {
                $query->where('lecturer_id', $lecturer->id);
            });
        }

        return $query;
    }

    public static function canCreate(): bool
    {
        // ceck apakah user adalah mahasiswa
        if (!auth()->user()->isStudent()) {
            return false;
        }

        // ambil data mahasiswa yang sedang login
        $student = auth()->user()->userable;

        // cek apakah mahasiswa memiliki thesis
        $existingThesis = Thesis::where('student_id', $student->id)->first();

        // jika belum memiliki thesis, tampilkan pesan
        if (!$existingThesis) {
            Notification::make()
                ->title('Tidak dapat mengajukan judul')
                ->body('Anda belum memiliki dosen pembimbing. Silahkan hubungi Kaprodi')
                ->danger()
                ->send();
            return false;
        }

        // cek apakah sudah pernah mengajukan judul
        $existingSubmission = TitleSubmission::whereHas('thesis', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })->first();

        if ($existingSubmission) {
            // Notification::make()
            //     ->title('Pengajuan Judul')
            //     ->body('Anda sudah pernah mengajukan judul. Silakan tunggu persetujuan.')
            //     ->warning()
            //     ->send();
            return false;
        }

        return true;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTitleSubmissions::route('/'),
            'create' => Pages\CreateTitleSubmission::route('/create'),
            'edit' => Pages\EditTitleSubmission::route('/{record}/edit'),
        ];
    }
}
