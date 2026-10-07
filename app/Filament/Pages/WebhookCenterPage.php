<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Models\WebhookEvent;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;

class WebhookCenterPage extends Page implements HasTable
{
    use HasRoleAccess;
    use HasTranslatedNav;
    use InteractsWithTable;

    protected static ?string $permissionModule = 'setting';
    protected static ?string $navKey = 'Webhook Center';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path-rounded-square';
    protected static ?string $navigationGroup = 'SYSTEM';
    protected static ?string $navigationLabel = 'Webhook Center';
    protected static ?string $title = 'Webhook Center';
    protected static ?int $navigationSort = 95;

    protected static string $view = 'filament.pages.webhook-center';

    public function table(Table $table): Table
    {
        return $table
            ->query(WebhookEvent::with('provider')->orderByDesc('id'))
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#'),
                Tables\Columns\TextColumn::make('provider.nama')->label('Provider')->placeholder('-'),
                Tables\Columns\TextColumn::make('payment_id')->label('Payment ID')->copyable()->limit(20),
                Tables\Columns\TextColumn::make('order_id')->label('Order')->copyable(),
                Tables\Columns\TextColumn::make('amount')->label('Nominal')->money('IDR'),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn ($s) => match ($s) {
                    'processed' => 'success', 'received' => 'warning', 'failed' => 'danger', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('created_at')->label('Diterima')->since(),
            ])
            ->filters([Tables\Filters\SelectFilter::make('status')->options(['received' => 'Received', 'processed' => 'Processed', 'failed' => 'Failed'])])
            ->actions([
                Tables\Actions\Action::make('replay')
                    ->label('Proses Ulang')->icon('heroicon-o-arrow-path')->requiresConfirmation()
                    ->visible(fn ($r) => $r->status !== 'processed')
                    ->action(function ($record) {
                        $controller = app(\App\Http\Controllers\PaymentWebhookController::class);
                        $req = request()->merge($record->raw ?? []);
                        try {
                            $resp = $controller->handle($req, (string) ($record->provider->kode ?? $record->payment_provider_id));
                            Notification::make()->title('Replay: '.$resp->getData()->status)->success()->send();
                        } catch (\Throwable $e) {
                            $record->update(['status' => 'failed']);
                            Notification::make()->title('Replay gagal: '.$e->getMessage())->danger()->send();
                        }
                    }),
            ])
            ->paginated(20);
    }
}
