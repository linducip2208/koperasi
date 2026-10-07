<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Filament\Concerns\HasTranslatedNav;
use App\Models\Setting;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

/**
 * Compliance Center: checklist kepatuhan configurable (tersimpan di settings).
 * Tidak mengklaim memenuhi regulasi otomatis — alat bantu verifikasi manual.
 */
class CompliancePage extends Page implements HasForms
{
    use HasRoleAccess;
    use HasTranslatedNav;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'laporan';
    protected static ?string $navKey = 'Compliance Center';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'GOVERNANCE';
    protected static ?string $navigationLabel = 'Compliance';
    protected static ?string $title = 'Compliance Center';
    protected static ?int $navigationSort = 61;

    protected static string $view = 'filament.pages.compliance';

    public ?array $data = [];

    public static function defaultItems(): array
    {
        return [
            ['area' => 'Administratif', 'item' => 'Badan hukum & NIK koperasi valid dan terdokumentasi', 'wajib' => true, 'status' => 'belum'],
            ['area' => 'Administratif', 'item' => 'AD/ART terbaru diarsipkan', 'wajib' => true, 'status' => 'belum'],
            ['area' => 'Keuangan', 'item' => 'Laporan SAK EP periode berjalan selesai', 'wajib' => true, 'status' => 'belum'],
            ['area' => 'Keuangan', 'item' => 'Rekonsiliasi bank bulan berjalan cocok', 'wajib' => true, 'status' => 'belum'],
            ['area' => 'Keanggotaan', 'item' => 'Data anggota lengkap (NIK + dokumen)', 'wajib' => false, 'status' => 'belum'],
            ['area' => 'RAT', 'item' => 'RAT tahun buku terakhir terlaksana + quorum', 'wajib' => true, 'status' => 'belum'],
            ['area' => 'Akuntansi', 'item' => 'Tidak ada jurnal tidak balance', 'wajib' => true, 'status' => 'belum'],
            ['area' => 'Audit', 'item' => 'Temuan audit critical = 0 open', 'wajib' => true, 'status' => 'belum'],
            ['area' => 'Dokumen', 'item' => 'Dokumen kedaluwarsa ditindaklanjuti', 'wajib' => false, 'status' => 'belum'],
        ];
    }

    public function mount(): void
    {
        $saved = Setting::get('compliance_checklist', null, 'kepatuhan');
        $this->form->fill(['items' => $saved ?: self::defaultItems()]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Repeater::make('items')->label('Checklist Kepatuhan')->schema([
                Select::make('area')->label('Area')->options([
                    'Administratif' => 'Administratif', 'Keuangan' => 'Keuangan', 'Keanggotaan' => 'Keanggotaan',
                    'RAT' => 'RAT', 'Akuntansi' => 'Akuntansi', 'Audit' => 'Audit', 'Dokumen' => 'Dokumen', 'Kebijakan' => 'Kebijakan',
                ]),
                TextInput::make('item')->label('Item')->required()->columnSpanFull(),
                Toggle::make('wajib')->label('Wajib'),
                Select::make('status')->label('Status')->options(['belum' => 'Belum', 'proses' => 'Proses', 'ok' => 'OK'])->default('belum'),
                Textarea::make('catatan')->label('Catatan')->rows(1)->columnSpanFull(),
            ])->columns(3)->columnSpanFull(),
        ])->statePath('data');
    }

    public function save(): void
    {
        Setting::set('compliance_checklist', $this->form->getState()['items'] ?? [], 'kepatuhan', 'json');
        Notification::make()->title('Checklist tersimpan.')->success()->send();
    }

    public function getViewData(): array
    {
        $items = $this->data['items'] ?? [];
        $wajib = array_filter($items, fn ($i) => ! empty($i['wajib']));
        $ok = array_filter($wajib, fn ($i) => ($i['status'] ?? '') === 'ok');
        return ['total' => count($items), 'wajibOk' => count($ok), 'wajibTotal' => count($wajib)];
    }
}
