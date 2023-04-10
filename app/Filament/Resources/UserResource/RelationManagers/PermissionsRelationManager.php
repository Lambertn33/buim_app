<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\UserResource\Pages\ViewUser;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $recordTitleAttribute = 'permission';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('permission')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('permission'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->hidden(fn ($livewire) => $livewire->pageClass === ViewUser::class),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                ->hidden(fn ($livewire) => $livewire->pageClass === ViewUser::class),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make()
                ->hidden(fn ($livewire) => $livewire->pageClass === ViewUser::class),
            ]);
    }    
}
