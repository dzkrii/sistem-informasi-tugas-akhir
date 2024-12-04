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

    protected static ?string $navigationLabel = 'Keterangan (Status) TA';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 6;
    protected static ?string $navigationGroup = 'Manajemen Tugas Akhir';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->required()
                    ->label("Mahasiswa")
                    ->relationship(
                        'student',
                        'name',
                        fn(Builder $query) =>
                        // Jika yang login adalah Kaprodi, filter mahasiswa sesuai departemennya
                        auth()->user()->role === 'kaprodi'
                            ? $query->where('department_id', auth()->user()->userable->department_id)
                            : $query
                    ),
                Forms\Components\Select::make('lecturer_id')
                    ->required()
                    ->label("Dosen Pembimbing")
                    ->relationship('lecturer', 'name'),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'progress' => 'Progress',
                        'finished' => 'Selesai',
                    ])
                    ->hidden()
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
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        // Jika yang login adalah Kaprodi, filter hanya thesis di departemennya
        if (auth()->user()->role === 'kaprodi') {
            $kaprodi = auth()->user()->userable;
            return $query->whereHas('student', function ($q) use ($kaprodi) {
                $q->where('department_id', $kaprodi->department_id);
            });
        }

        return $query;
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
