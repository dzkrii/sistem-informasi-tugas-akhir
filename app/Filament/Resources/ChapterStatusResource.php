<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterStatusResource\Pages;
use App\Filament\Resources\ChapterStatusResource\RelationManagers;
use App\Models\ChapterStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChapterStatusResource extends Resource
{
    protected static ?string $model = ChapterStatus::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('thesis_id')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('chapter_number')
                //     ->required()
                //     ->numeric(),
                // Forms\Components\TextInput::make('status')
                //     ->required()
                //     ->maxLength(255)
                //     ->default('not_started'),
                // Forms\Components\Textarea::make('note')
                //     ->columnSpanFull(),

                Forms\Components\TextInput::make('chapter_number')
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'not_started' => 'Belum Dimulai',
                        'in_review' => 'Sedang Direview',
                        'revision_needed' => 'Perlu Revisi',
                        'accepted' => 'Diterima'
                    ])
                    ->required()
                    ->visible(fn() => auth()->user()->isLecturer()),
                Forms\Components\Textarea::make('revision_note')
                    ->visible(fn() => auth()->user()->isLecturer()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('thesis_id')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('chapter_number')
                //     ->numeric()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('status')
                //     ->searchable(),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // Tables\Columns\TextColumn::make('deleted_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('thesis.student.name')
                    ->label('Mahasiswa')
                    ->visible(fn() => auth()->user()->isLecturer()),
                Tables\Columns\TextColumn::make('chapter_number')
                    ->label('Bab'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'not_started' => 'gray',
                        'in_review' => 'warning',
                        'revision_needed' => 'danger',
                        'accepted' => 'success',
                    }),
                Tables\Columns\TextColumn::make('last_reviewed_at')
                    ->dateTime()
                    ->label('Terakhir Direview'),
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

        // Jika user adalah mahasiswa, tampilkan hanya chapter miliknya
        if (auth()->user()->isStudent()) {
            $student = auth()->user()->userable;
            return $query->whereHas('thesis', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            });
        }

        // Jika user adalah dosen, tampilkan chapter mahasiswa bimbingannya
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
            'index' => Pages\ListChapterStatuses::route('/'),
            'create' => Pages\CreateChapterStatus::route('/create'),
            'edit' => Pages\EditChapterStatus::route('/{record}/edit'),
        ];
    }
}
