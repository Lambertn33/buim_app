<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\PermissionsRelationManager;
use App\Models\Role;
use App\Models\User;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Card;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Access control';

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfUsers();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('user names')
                            ->required()
                            ->disableAutocomplete()
                            ->placeholder('enter user names'),
                        TextInput::make('email')
                            ->email()
                            ->label('user email')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disableAutocomplete()
                            ->placeholder('enter user email'),
                        TextInput::make('telephone')
                            ->tel()
                            ->label('user telephone (250...)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('enter user telephone'),
                        Select::make('role_id')
                            ->label('user role')
                            ->required()
                            ->placeholder('select user role')
                            ->relationship('role', 'role'),
                        Select::make('account_status')
                            ->hiddenOn('create')
                            ->options(self::$model::ACCOUNT_STATUS)
                            ->disablePlaceholderSelection()
                            ->label('account status'),
                        TextInput::make('password')
                            ->password()
                            ->minLength(8)
                            ->disableAutocomplete()
                            ->visibleOn('create'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Names')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('role.role')
                    ->sortable(),
                BadgeColumn::make('account_status')
                    ->label('account status')
                    ->sortable()
                    ->colors([
                        'success' => static fn ($state): bool => $state === User::ACTIVE,
                        'danger' => static fn ($state): bool => $state === User::CLOSED,
                    ])

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->role->role === Role::ADMIN_ROLE)
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PermissionsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }
}
