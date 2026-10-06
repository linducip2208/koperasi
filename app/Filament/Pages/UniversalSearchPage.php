<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\HasRoleAccess;
use App\Models\Anggota;
use App\Models\Coa;
use App\Models\Jurnal;
use App\Models\MemberDocument;
use App\Models\Pinjaman;
use App\Models\Rat;
use App\Models\Simpanan;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;

class UniversalSearchPage extends Page implements HasForms
{
    use HasRoleAccess;
    use InteractsWithForms;

    protected static ?string $permissionModule = 'anggota';
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationGroup = 'DASHBOARD';
    protected static ?string $navigationLabel = 'Cari Universal';
    protected static ?string $title = 'Pencarian Universal';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.universal-search';

    public ?array $data = ['q' => ''];

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('q')->label('Kata kunci')->placeholder('nama, nomor akad/rekening/jurnal…')->maxLength(100),
        ])->statePath('data');
    }

    public function getViewData(): array
    {
        $q = trim($this->data['q'] ?? request()->query('q', ''));
        if (strlen($q) < 3) return ['q' => $q, 'groups' => []];

        $like = '%'.str_replace(['%', '_'], '', $q).'%';
        $u = auth()->user();
        $groups = [];

        if ($u->can('anggota.view')) {
            $groups['Anggota'] = Anggota::where(fn ($w) => $w->where('nama', 'like', $like)->orWhere('nomor_anggota', 'like', $like))
                ->limit(10)->get()->map(fn ($a) => ['label' => "{$a->nomor_anggota} — {$a->nama}", 'url' => \App\Filament\Resources\AnggotaResource::getUrl('edit', ['record' => $a])])->all();
        }
        if ($u->can('simpanan.view')) {
            $groups['Simpanan'] = Simpanan::where('nomor_rekening', 'like', $like)->limit(10)->get()
                ->map(fn ($s) => ['label' => "{$s->nomor_rekening} — Rp ".number_format($s->saldo, 0, ',', '.'), 'url' => null])->all();
        }
        if ($u->can('pinjaman.view')) {
            $groups['Pinjaman'] = Pinjaman::where(fn ($w) => $w->where('nomor_akad', 'like', $like))->limit(10)->get()
                ->map(fn ($p) => ['label' => "{$p->nomor_akad} — ".($p->anggota->nama ?? ''), 'url' => \App\Filament\Resources\PinjamanResource::getUrl('view', ['record' => $p])])->all();
        }
        if ($u->can('jurnal.view')) {
            $groups['Jurnal'] = Jurnal::where(fn ($w) => $w->where('nomor', 'like', $like)->orWhere('keterangan', 'like', $like))->limit(10)->get()
                ->map(fn ($j) => ['label' => "{$j->nomor} — {$j->keterangan}", 'url' => null])->all();
        }
        if ($u->can('anggota.view')) {
            $groups['Dokumen'] = MemberDocument::where(fn ($w) => $w->where('nama', 'like', $like))->limit(10)->get()
                ->map(fn ($d) => ['label' => "{$d->nama} ({$d->jenis})", 'url' => null])->all();
        }
        if ($u->can('rat.view')) {
            $groups['RAT'] = Rat::where('tahun_buku', 'like', $like)->limit(5)->get()
                ->map(fn ($r) => ['label' => "RAT {$r->tahun_buku} — {$r->status}", 'url' => null])->all();
        }
        if ($u->can('coa.view') || $u->can('jurnal.view')) {
            $groups['Akun'] = Coa::where(fn ($w) => $w->where('nama', 'like', $like)->orWhere('kode', 'like', $like))->limit(10)->get()
                ->map(fn ($c) => ['label' => "{$c->kode} — {$c->nama}", 'url' => null])->all();
        }

        return ['q' => $q, 'groups' => array_filter($groups)];
    }
}
