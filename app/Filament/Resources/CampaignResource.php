<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CampaignResource\Pages;
use App\Models\Campaign;
use App\Models\District;
use App\Models\Province;
use App\Models\Role;
use App\Services\NavigationBadgesServices;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Closure;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use stdClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CampaignResource extends Resource
{
    protected static ?string $model = Campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-speakerphone';

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfCampaigns();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->required()
                    ->label('Campaign title')
                    ->placeholder('enter title')
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->required()
                    ->label('campaign description')
                    ->columnSpanFull(),
                Select::make('province')
                    ->options(Province::get()->pluck('province', 'id')->toArray())
                    ->placeholder('select province')
                    ->required()
                    ->visibleOn('create')
                    ->reactive(),
                Select::make('district')
                    ->placeholder('select district')
                    ->required()
                    ->visibleOn('create')
                    ->options(function (callable $get) {
                        $province = $get('province');
                        if ($province) {
                            return District::where('province_id', $province)->pluck('district', 'district')->toArray();
                        }
                    }),
                Select::make('status')
                    ->disablePlaceholderSelection()
                    ->hiddenOn('create')
                    ->options(self::$model::CAMPAIGN_STATUS),
                DatePicker::make('from')
                    ->label('starting date')
                    ->minDate(now())
                    ->required()
                    ->placeholder('select the starting date')
                    ->reactive(),
                DatePicker::make('to')
                    ->label('ending date')
                    ->required()
                    ->placeholder('select the ending date')
                    ->minDate(function (callable $get) {
                        $from = $get('from');
                        if ($from) {
                            return $from;
                        }
                    }),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->description(fn (Campaign $record): string => $record->description),
                TextColumn::make('from')
                    ->label('starting date')
                    ->sortable(),
                TextColumn::make('to')
                    ->label('ending date')
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => static fn ($state): bool => $state === self::$model::CREATED,
                        'warning' => static fn ($state): bool => $state === self::$model::ONGOING,
                        'success' => static fn ($state): bool => $state === self::$model::FINISHED,
                        'danger' => static fn ($state): bool => $state === self::$model::STOPPED,
                    ]),
                TextColumn::make('province')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('district')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('screenings_count')
                    ->label('number of screenings')
                    ->counts('screenings'),
                TextColumn::make('manager.user.name')
                    ->label('campaign manager')
                    ->visible(auth()->user()->role->role === Role::ADMIN_ROLE)
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter by status')
                    ->options(self::$model::CAMPAIGN_STATUS)

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCampaigns::route('/'),
            'create' => Pages\CreateCampaign::route('/create'),
            'edit' => Pages\EditCampaign::route('/{record}/edit'),
        ];
    }
}
