<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditFindingResource\Pages;
use App\Models\AuditFinding;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Concerns\HasRoleAccess;

class AuditFindingResource extends Resource
{
    use HasRoleAccess;
    protected static ?string $permissionModule = 'laporan';

    protected static ?string $model = AuditFinding::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'GOVERNANCE';
    protected static ?string $navigationLabel = 'Temuan Audit';
    protected static ?int $navigationSort = 60;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Grid::make(2)->schema([
                Forms\Components\TextInput::make('judul')->label('Judul Temuan')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('deskripsi')->label('Deskripsi')->rows(3)->columnSpanFull(),
                Forms\Components\Select::make('kategori')->label('Kategori')->options([
                    'keuangan' => 'Keuangan', 'keanggotaan' => 'Keanggotaan', 'operasional' => 'Operasional',
                    'kepatuhan' => 'Kepatuhan', 'ti' => 'TI & Sistem',
                ])->default('keuangan'),
                Forms\Components\Select::make('severity')->label('Severity')->options([
                    'low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'critical' => 'Critical',
                ])->default('medium'),
                Forms\Components\Select::make('status')->label('Status')->options([
                    'open' => 'Open', 'progress' => 'Progress', 'resolved' => 'Resolved', 'closed' => 'Closed',
                ])->default('open'),
                Forms\Components\Select::make('owner_id')->label('Penanggung Jawab')->options(User::pluck('name', 'id'))->searchable(),
                Forms\Components\DatePicker::make('due_date')->label('Tenggat'),
                Forms\Components\Textarea::make('tindak_lanjut')->label('Tindak Lanjut')->rows(2)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable()->limit(40),
                Tables\Columns\TextColumn::make('kategori')->badge(),
                Tables\Columns\TextColumn::make('severity')->badge()->color(fn ($s) => match ($s) {
                    'critical' => 'danger', 'high' => 'warning', 'medium' => 'info', default => 'gray',
                }),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('owner.name')->label('Owner')->placeholder('-'),
                Tables\Columns\TextColumn::make('due_date')->label('Due')->date('d M y')->placeholder('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status'),
                Tables\Filters\SelectFilter::make('severity'),
            ])
            ->actions([
                Tables\Actions\Action::make('resolve')->label('Resolve')->color('success')
                    ->visible(fn ($r) => in_array($r->status, ['open', 'progress']))
                    ->requiresConfirmation()
                    ->action(fn ($r) => $r->update(['status' => 'resolved', 'resolved_at' => now()])),
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditFindings::route('/'),
            'create' => Pages\CreateAuditFinding::route('/create'),
            'edit' => Pages\EditAuditFinding::route('/{record}/edit'),
        ];
    }
}
