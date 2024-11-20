<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TitleSubmissionResource\Pages;
use App\Filament\Resources\TitleSubmissionResource\RelationManagers;
use App\Models\TitleSubmission;
use Filament\Forms;
use Filament\Forms\Form;
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
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
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
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->visible(fn() => auth()->user()->isLecturer())
                    ->required(),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull()
                    ->visible(fn() => auth()->user()->isLecturer()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('thesis.student.name')
                    ->label('Mahasiswa')
                    ->sortable()
                    ->visible(fn() => auth()->user()->isLecturer()),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
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
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
