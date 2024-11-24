<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ThesisResource\Pages;
use App\Filament\Resources\ThesisResource\RelationManagers;
use App\Models\Thesis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThesisResource extends Resource
{
    protected static ?string $model = Thesis::class;

    protected static ?string $navigationLabel = 'Thesis';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Manajemen Tugas Akhir';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->required()
                    ->relationship('student', 'name'),
                Forms\Components\Select::make('lecturer_id')
                    ->required()
                    ->relationship('lecturer', 'name'),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('progress'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->sortable()
                    ->label('Mahasiswa'),
                Tables\Columns\TextColumn::make('lecturer.name')
                    ->label('Dosen Pembimbing')
                    ->sortable(),
                // make status badge if progress == yellow, if finished == green
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'progress' => 'warning',
                        'finished' => 'success',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListTheses::route('/'),
            'create' => Pages\CreateThesis::route('/create'),
            'edit' => Pages\EditThesis::route('/{record}/edit'),
        ];
    }
}
