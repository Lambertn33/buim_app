<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseDeviceDistributionResource\Pages;
use App\Filament\Resources\WarehouseDeviceDistributionResource\RelationManagers;
use App\Models\PaymentPlan;
use App\Models\Role;
use App\Models\Screening;
use App\Models\WarehouseDevice;
use App\Models\WarehouseDeviceDistribution;
use App\Services\NavigationBadgesServices;
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

    protected static ?string $navigationLabel = 'Device distribution';

    protected static ?string $pluralModelLabel = 'Distribution list';

    protected static ?int $navigationSort = 6;

    protected static function getNavigationBadge(): ?string
    {
        return (new NavigationBadgesServices)->getTotalNumberOfDistributions();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Select::make('warehouse_device_id')
                        ->label('select serial number')
                        ->searchable()
                        ->reactive()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $device = WarehouseDevice::find($get('warehouse_device_id'));
                            if ($device) {
                                $set('device_price', $device->device_price);
                            }
                        })
                        ->options(WarehouseDevice::where('district_id', Auth::user()->leader->district->id)->whereNull('screener_id')->get()->pluck('serial_number', 'id')->toArray()),
                    TextInput::make('device_price')
                        ->disabled(),
                    Select::make('screener_id')
                        ->label('select client')
                        ->searchable()
                        ->required()
                        ->options(Screening::where('district', Auth::user()->leader->district->district)->where('confirmation_status', Screening::PROSPECT)
                            ->get()->pluck('prospect_names', 'id')->toArray()),
                    Select::make('payment_id')
                        ->label('select payment mode')
                        ->reactive()
                        ->searchable()
                        ->required()
                        ->afterStateUpdated(function ($state, $set, $get) {
                            $device = WarehouseDevice::find($get('warehouse_device_id'));
                            if ($device) {
                                $devicePrice = $device->device_price;
                                $paymentPlan = PaymentPlan::find($get('payment_id'));
                                if ($paymentPlan) {
                                    $percentage = $paymentPlan->percentage;
                                    $amountToPay = ($devicePrice * $percentage) / 100;
                                    $downpayment = $amountToPay / $paymentPlan->downpayment;
                                    $set('customer_contribution', $amountToPay);
                                    $set('downpayment_amount', $downpayment);
                                    $set('downpayment_percentage', $paymentPlan->downpayment);
                                    $set('duration', $paymentPlan->duration);
                                }
                            }
                        })
                        ->options(PaymentPlan::get()->pluck('title', 'id')->toArray()),
                    TextInput::make('customer_contribution')
                        ->label('Customer contribution')
                        ->disabled()
                        ->numeric(),
                    TextInput::make('downpayment_percentage')
                        ->label('Downpayment percentage (%)')
                        ->disabled()
                        ->numeric(),
                    TextInput::make('downpayment_amount')
                        ->label('Downpayment amount')
                        ->disabled()
                        ->numeric(),
                    TextInput::make('duration')
                        ->label('Duration (days)')
                        ->disabled()
                        ->numeric(),
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
                    ->searchable(),
                TextColumn::make('warehouseDevice.warehouse.district.district')
                    ->visible(Auth::user()->role->role == Role::ADMIN_ROLE || Auth::user()->role->role == Role::STOCK_MANAGER_ROLE)
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('print contract')
                    ->icon('heroicon-o-printer')
                    ->action(fn (WarehouseDeviceDistribution $record) => null)
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
