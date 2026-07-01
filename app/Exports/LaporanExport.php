<?php

namespace App\Exports;

use App\Domain\Akuntansi\LaporanKeuanganService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    public function __construct(
        public string $jenis,
        public string $dari,
        public string $sampai,
        public ?int $cabangId = null
    ) {}

    public function array(): array
    {
        return match ($this->jenis) {
            'neraca'    => $this->rowsNeraca(),
            'laba-rugi' => $this->rowsLabaRugi(),
            'arus-kas'  => $this->rowsArusKas(),
            default     => [],
        };
    }

    public function headings(): array
    {
        return ['Kode', 'Akun', 'Saldo (Rp)'];
    }

    public function title(): string
    {
        return ucwords(str_replace('-', ' ', $this->jenis));
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'color' => ['rgb' => '047857']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $e) {
                $sheet = $e->sheet->getDelegate();
                $highest = $sheet->getHighestRow();
                $sheet->getStyle("A2:C{$highest}")->getBorders()->getAllBorders()->setBorderStyle('thin');
                $sheet->getStyle("C2:C{$highest}")->getNumberFormat()->setFormatCode('#,##0');
            },
        ];
    }

    private function rowsNeraca(): array
    {
        $d = LaporanKeuanganService::neraca($this->sampai, $this->cabangId);
        $rows = [];
        foreach (['ASET' => 'aset', 'KEWAJIBAN' => 'kewajiban', 'EKUITAS' => 'ekuitas'] as $label => $key) {
            $rows[] = ["── {$label} ──", '', ''];
            foreach ($d[$key] as $r) {
                $rows[] = [$r['kode'], $r['nama'], $r['saldo']];
            }
            $rows[] = ['', "TOTAL {$label}", collect($d[$key])->sum('saldo')];
            $rows[] = ['', '', ''];
        }
        return $rows;
    }

    private function rowsLabaRugi(): array
    {
        $d = LaporanKeuanganService::labaRugi($this->dari, $this->sampai, $this->cabangId);
        $rows = [['── PENDAPATAN ──', '', '']];
        foreach ($d['pendapatan'] as $r) $rows[] = [$r['kode'], $r['nama'], $r['saldo']];
        $rows[] = ['', 'TOTAL PENDAPATAN', $d['total_pendapatan']];
        $rows[] = ['', '', ''];
        $rows[] = ['── BEBAN ──', '', ''];
        foreach ($d['beban'] as $r) $rows[] = [$r['kode'], $r['nama'], $r['saldo']];
        $rows[] = ['', 'TOTAL BEBAN', $d['total_beban']];
        $rows[] = ['', '', ''];
        $rows[] = ['', 'SISA HASIL USAHA (SHU)', $d['shu']];
        return $rows;
    }

    private function rowsArusKas(): array
    {
        $d = LaporanKeuanganService::arusKas($this->dari, $this->sampai, $this->cabangId);
        return [
            ['', 'Total Penerimaan Kas', $d['masuk']],
            ['', 'Total Pengeluaran Kas', -$d['keluar']],
            ['', 'Kenaikan/Penurunan Kas Bersih', $d['net']],
        ];
    }
}
