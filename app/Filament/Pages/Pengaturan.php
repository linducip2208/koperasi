<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Pengaturan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Pengaturan Sistem';
    protected static ?string $title = 'Pengaturan Sistem';
    protected static ?int $navigationSort = 99;
    protected static string $view = 'filament.pages.pengaturan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'wa_provider'  => Setting::get('wa_provider', 'fonnte', 'notifikasi'),
            'wa_api_key'   => Setting::get('wa_api_key', '', 'notifikasi'),
            'wa_api_url'   => Setting::get('wa_api_url', '', 'notifikasi'),
            'reminder_h'   => Setting::get('reminder_h', '3', 'notifikasi'),
            'shu_jasa_modal'   => Setting::get('shu_jasa_modal', '25', 'shu'),
            'shu_jasa_anggota' => Setting::get('shu_jasa_anggota', '25', 'shu'),
            'shu_cadangan'     => Setting::get('shu_cadangan', '25', 'shu'),
            'shu_pendidikan'   => Setting::get('shu_pendidikan', '5', 'shu'),
            'shu_sosial'       => Setting::get('shu_sosial', '5', 'shu'),
            'shu_pengurus'     => Setting::get('shu_pengurus', '10', 'shu'),
            'shu_karyawan'     => Setting::get('shu_karyawan', '5', 'shu'),
            'pos_diskon_anggota' => Setting::get('pos_diskon_anggota', '5', 'pos'),
            'pos_print_struk'    => Setting::get('pos_print_struk', '1', 'pos'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Notifikasi WhatsApp')->schema([
                Select::make('wa_provider')->label('Provider')->options([
                    'fonnte' => 'Fonnte',
                    'wablas' => 'WAblas',
                ]),
                TextInput::make('wa_api_key')->label('API Key / Token')->password()->revealable(),
                TextInput::make('wa_api_url')->label('API URL (utk WAblas)')->url(),
                TextInput::make('reminder_h')->label('Reminder H- (hari)')->numeric()->default(3),
            ])->columns(2),

            Section::make('Persentase Pembagian SHU (%)')->schema([
                TextInput::make('shu_jasa_modal')->label('Jasa Modal')->numeric()->suffix('%'),
                TextInput::make('shu_jasa_anggota')->label('Jasa Anggota')->numeric()->suffix('%'),
                TextInput::make('shu_cadangan')->label('Dana Cadangan')->numeric()->suffix('%'),
                TextInput::make('shu_pendidikan')->label('Dana Pendidikan')->numeric()->suffix('%'),
                TextInput::make('shu_sosial')->label('Dana Sosial')->numeric()->suffix('%'),
                TextInput::make('shu_pengurus')->label('Dana Pengurus')->numeric()->suffix('%'),
                TextInput::make('shu_karyawan')->label('Dana Karyawan')->numeric()->suffix('%'),
            ])->columns(3),

            Section::make('Toko / POS')->schema([
                TextInput::make('pos_diskon_anggota')->label('Diskon Anggota Default (%)')->numeric()->suffix('%'),
                Toggle::make('pos_print_struk')->label('Auto-print Struk'),
            ])->columns(2),
        ])->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $groups = [
            'notifikasi' => ['wa_provider', 'wa_api_key', 'wa_api_url', 'reminder_h'],
            'shu'        => ['shu_jasa_modal', 'shu_jasa_anggota', 'shu_cadangan', 'shu_pendidikan', 'shu_sosial', 'shu_pengurus', 'shu_karyawan'],
            'pos'        => ['pos_diskon_anggota', 'pos_print_struk'],
        ];

        foreach ($groups as $group => $keys) {
            foreach ($keys as $key) {
                if (array_key_exists($key, $data)) {
                    Setting::set($key, $data[$key], $group);
                }
            }
        }

        Notification::make()->title('Pengaturan tersimpan')->success()->send();
    }
}
