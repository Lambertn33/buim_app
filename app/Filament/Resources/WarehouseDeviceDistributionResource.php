<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseDeviceDistributionResource\Pages;
use App\Filament\Resources\WarehouseDeviceDistributionResource\RelationManagers;
use App\Models\PaymentPlan;
use App\Models\Screening;
use App\Models\WarehouseDevice;
use App\Models\WarehouseDeviceDistribution;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class WarehouseDeviceDistributionResource extends Resource
{
    protected static ?string $model = WarehouseDeviceDistribution::class;

    protected static ?string $navigationIcon = 'heroicon-o-share';

    protected static ?string $navigationGroup = 'Customers';

    protected static ?string $navigationLabel = 'Warehouse device distributions';

    protected static ?string $pluralModelLabel = 'District Distributions';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('warehouse_device_id')
                        ->label('select device serial number')
                        ->searchable()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->options(WarehouseDevice::where('district_id', Auth::user()->leader->district->id)->whereNull('screener_id')->get()->pluck('serial_number', 'id')->toArray()),
                    Select::make('screener_id')
                        ->label('select screener')
                        ->searchable()
                        ->required()
                        ->options(Screening::where('district', Auth::user()->leader->district->district)->where('confirmation_status', Screening::PROSPECT)
                            ->get()->pluck('prospect_names', 'id')->toArray()),
                    Select::make('payment_id')
                        ->label('select payment mode')
                        ->searchable()
                        ->required()
                        ->options(PaymentPlan::get()->pluck('title', 'id')->toArray()),
                    TextInput::make('downpayment_amount')
                        ->label('enter downpayment amount')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('contract_id')
                    ->searchable()
                    ->sortable()
                    ->label('Contract ID'),
                TextColumn::make('screener.prospect_names')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('warehouseDevice.device_name')
                    ->label('Device name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('warehouseDevice.serial_number')
                    ->label('Device Serial number')
                    ->sortable()
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('print contract')
                    ->icon('heroicon-o-printer')
                    ->action(fn(WarehouseDeviceDistribution $record) => null)
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWarehouseDeviceDistributions::route('/'),
        ];
    }
}
