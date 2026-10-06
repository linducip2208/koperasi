<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcurementResource\Pages;
use App\Models\Procurement;
use App\Models\TokoSupplier;
use App\Workflow\WorkflowService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class ProcurementResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'procurement';

    protected static ?string $model = Procurement::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'OPERATIONS';
    protected static ?string $navigationLabel = 'Pengadaan';
    protected static ?int $navigationSort = 40;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('nomor')->label('Nomor PR')
                    ->default(fn () => \App\Domain\Numbering\NumberingService::next('procurement', 'PR-', '{prefix}{ymd}-{seq:4}'))
                    ->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('judul')->label('Judul')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('deskripsi')->label('Deskripsi')->rows(2)->columnSpanFull(),
                Forms\Components\Select::make('supplier_id')->label('Supplier')
                    ->options(TokoSupplier::pluck('nama', 'id'))->searchable(),
                Forms\Components\TextInput::make('estimasi')->label('Estimasi (Rp)')->numeric()->default(0),
                Forms\Components\TextInput::make('aktual')->label('Aktual (Rp)')->numeric(),
                Forms\Components\Textarea::make('catatan')->label('Catatan')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')->searchable()->copyable()->weight('bold'),
                Tables\Columns\TextColumn::make('judul')->searchable()->limit(35),
                Tables\Columns\TextColumn::make('estimasi')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($s) => match ($s) {
                    'draft' => 'gray', 'submitted' => 'info', 'review' => 'warning', 'approved' => 'success',
                    'rejected' => 'danger', 'revision' => 'warning', 'executed' => 'success', 'closed' => 'gray', default => 'gray',
                }),
            ])
            ->actions([
                Tables\Actions\Action::make('workflow')
                    ->label('Proses')->icon('heroicon-o-arrow-path')
                    ->form(fn ($record) => [
                        Forms\Components\Select::make('to')->label('Ke status')
                            ->options(array_combine($record->allowedTransitions(), $record->allowedTransitions()))->required(),
                        Forms\Components\Textarea::make('catatan')->label('Catatan')->rows(2),
                    ])
                    ->action(function ($record, array $data) {
                        WorkflowService::transition($record, $data['to'], 'procurement', $data['catatan'] ?? null);
                        Notification::make()->title("Status → {$data['to']}")->success()->send();
                    }),
                Tables\Actions\EditAction::make()->visible(fn ($r) => in_array($r->status, ['draft', 'revision', 'rejected'])),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcurements::route('/'),
            'create' => Pages\CreateProcurement::route('/create'),
            'edit' => Pages\EditProcurement::route('/{record}/edit'),
        ];
    }
}
