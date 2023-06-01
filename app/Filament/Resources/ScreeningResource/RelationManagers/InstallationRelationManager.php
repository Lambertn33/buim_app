<?php

namespace App\Filament\Resources\ScreeningResource\RelationManagers;

use App\Models\Role;
use App\Models\ScreeningInstallation;
use App\Models\Technician;
use App\Services\ScreeningServices;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class InstallationRelationManager extends RelationManager
{
    protected static string $relationship = 'installation';

    protected static ?string $recordTitleAttribute = 'installation_status';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('installation_status')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                BadgeColumn::make('installation_status')
                    ->colors([
                        'warning' => static fn ($state): bool => $state == ScreeningInstallation::INSTALLATION_PENDING,
                        'success' => static fn ($state): bool => $state == ScreeningInstallation::INSTALLATION_INSTALLED,
                    ]),
                BadgeColumn::make('verification_status')
                    ->colors([
                        'warning' => static fn ($state): bool => $state == ScreeningInstallation::VERIFICATION_PENDING,
                        'success' => static fn ($state): bool => $state == ScreeningInstallation::VERIFICATION_VERIFIED,
                    ]),
                TextColumn::make('technician.names')
                    ->label('Installed by'),
                TextColumn::make('latitude'),
                TextColumn::make('longitude'),
                TextColumn::make('verified_by')
                    ->formatStateUsing(fn ($record) => $record->verifiedBy())
                    ->label('Verified by')
            ])
            ->filters([
                //
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('install')
                    ->requiresConfirmation()
                    ->action(
                        fn (array $data, ScreeningInstallation $record) => (new ScreeningServices)->installScreeningDevice($data, $record->id)
                    )
                    ->modalHeading('Install Device')
                    ->modalSubheading('select technician')
                    ->form([
                        Select::make('technician_id')
                            ->required()
                            ->label('select technician')
                            ->placeholder('select technician')
                            ->options(function (RelationManager $livewire) {
                                return Technician::whereHas('district', function ($query) use ($livewire) {
                                    return $query->where('district', $livewire->ownerRecord->district);
                                })->get()->pluck('names', 'id')->toArray();
                            }),
                        TextInput::make('latitude')
                            ->label('enter latitude')
                            ->required()
                            ->numeric(),
                        TextInput::make('longitude')
                            ->label('enter longitude')
                            ->required()
                            ->numeric(),
                    ])
                    ->modalButton('Install device')
                    ->visible(fn ($record) => Auth::user()->role->role == Role::SECTOR_LEADER_ROLE && $record->installation_status == ScreeningInstallation::INSTALLATION_PENDING)
                    ->icon('heroicon-o-tag'),
                Tables\Actions\Action::make('verify')
                    ->requiresConfirmation()
                    ->modalHeading('Verify installation')
                    ->modalSubheading('After checking the installation, I confirm that the data are true')
                    ->action(fn (ScreeningInstallation $record) => (new ScreeningServices)->verifyScreeningDevice($record->id))
                    ->visible(fn ($record) => Auth::user()->role->role == Role::SECTOR_LEADER_ROLE && $record->verification_status == ScreeningInstallation::VERIFICATION_PENDING && 
                        $record->installation_status == ScreeningInstallation::INSTALLATION_INSTALLED)
                    ->color('success')
                    ->modalButton('verify installation')
                    ->icon('heroicon-o-check-circle'),
            ])
            ->bulkActions([]);
    }
}
