<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KaprodiResource\Pages;
use App\Filament\Resources\KaprodiResource\RelationManagers;
use App\Models\Kaprodi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KaprodiResource extends Resource
{
    protected static ?string $model = Kaprodi::class;

    protected static ?string $navigationLabel = 'Kaprodi';
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nidn')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->label('Program Studi')
                    ->required(),
                Forms\Components\TextInput::make('user.email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('user.password')
                    ->password()
                    ->revealable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nidn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Program Studi')
                    ->sortable(),
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
            'index' => Pages\ListKaprodis::route('/'),
            'create' => Pages\CreateKaprodi::route('/create'),
            'edit' => Pages\EditKaprodi::route('/{record}/edit'),
        ];
    }
}
