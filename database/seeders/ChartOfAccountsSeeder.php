<?php

namespace Database\Seeders;

use App\Models\Coa;
use App\Support\Tenant\CurrentTenant;
use Illuminate\Database\Seeder;

class ChartOfAccountsSeeder extends Seeder
{
    public function run(): void
    {
        CurrentTenant::set(1);

        $accounts = [
            // ASET (1.x)
            ['1', 'ASET', 'aset', 'aset_lancar', 'debit', null, false],
            ['1.1', 'Aset Lancar', 'aset', 'aset_lancar', 'debit', '1', false],
            ['1.1.1', 'Kas dan Setara Kas', 'aset', 'aset_lancar', 'debit', '1.1', false],
            ['1.1.1.01', 'Kas Besar', 'aset', 'aset_lancar', 'debit', '1.1.1', true, ['is_kas' => true]],
            ['1.1.1.02', 'Kas Kecil', 'aset', 'aset_lancar', 'debit', '1.1.1', true, ['is_kas' => true]],
            ['1.1.2', 'Bank', 'aset', 'aset_lancar', 'debit', '1.1', false],
            ['1.1.2.01', 'Bank BCA', 'aset', 'aset_lancar', 'debit', '1.1.2', true, ['is_bank' => true]],
            ['1.1.2.02', 'Bank Mandiri', 'aset', 'aset_lancar', 'debit', '1.1.2', true, ['is_bank' => true]],
            ['1.1.3', 'Piutang', 'aset', 'aset_lancar', 'debit', '1.1', false],
            ['1.1.3.01', 'Piutang Pinjaman Anggota', 'aset', 'aset_lancar', 'debit', '1.1.3', true],
            ['1.1.3.02', 'Piutang Murabahah', 'aset', 'aset_lancar', 'debit', '1.1.3', true],
            ['1.1.3.03', 'Piutang Mudharabah', 'aset', 'aset_lancar', 'debit', '1.1.3', true],
            ['1.1.3.04', 'Piutang Musyarakah', 'aset', 'aset_lancar', 'debit', '1.1.3', true],
            ['1.1.3.05', 'Piutang Ijarah', 'aset', 'aset_lancar', 'debit', '1.1.3', true],
            ['1.1.3.06', 'Piutang Dagang', 'aset', 'aset_lancar', 'debit', '1.1.3', true],
            ['1.1.3.99', 'Piutang Lain-lain', 'aset', 'aset_lancar', 'debit', '1.1.3', true],
            ['1.1.4', 'Persediaan Barang Dagang', 'aset', 'aset_lancar', 'debit', '1.1', true],
            ['1.1.5', 'Biaya Dibayar di Muka', 'aset', 'aset_lancar', 'debit', '1.1', true],
            ['1.1.6', 'PPAP (Penyisihan Penghapusan Aktiva Produktif)', 'aset', 'aset_lancar', 'kredit', '1.1', true],
            ['1.2', 'Aset Tetap', 'aset', 'aset_tetap', 'debit', '1', false],
            ['1.2.1', 'Tanah', 'aset', 'aset_tetap', 'debit', '1.2', true],
            ['1.2.2', 'Bangunan', 'aset', 'aset_tetap', 'debit', '1.2', true],
            ['1.2.3', 'Kendaraan', 'aset', 'aset_tetap', 'debit', '1.2', true],
            ['1.2.4', 'Peralatan Kantor', 'aset', 'aset_tetap', 'debit', '1.2', true],
            ['1.2.5', 'Akumulasi Penyusutan Bangunan', 'aset', 'aset_tetap', 'kredit', '1.2', true],
            ['1.2.6', 'Akumulasi Penyusutan Kendaraan', 'aset', 'aset_tetap', 'kredit', '1.2', true],
            ['1.2.7', 'Akumulasi Penyusutan Peralatan', 'aset', 'aset_tetap', 'kredit', '1.2', true],

            // KEWAJIBAN (2.x)
            ['2', 'KEWAJIBAN', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', null, false],
            ['2.1', 'Kewajiban Jangka Pendek', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', '2', false],
            ['2.1.1', 'Hutang Operasional', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', '2.1', false],
            ['2.1.1.01', 'Hutang Premi Asuransi', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', '2.1.1', true],
            ['2.1.1.02', 'Hutang Pajak', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', '2.1.1', true],
            ['2.1.1.03', 'Hutang Gaji', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', '2.1.1', true],
            ['2.1.1.04', 'Hutang Dagang', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', '2.1.1', true],
            ['2.1.2', 'Titipan', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', '2.1', false],
            ['2.1.2.01', 'Titipan Anggota', 'kewajiban', 'kewajiban_jangka_pendek', 'kredit', '2.1.2', true],
            ['2.2', 'Simpanan Anggota', 'kewajiban', 'kewajiban_jangka_panjang', 'kredit', '2', false],
            ['2.2.1', 'Simpanan', 'kewajiban', 'kewajiban_jangka_panjang', 'kredit', '2.2', false],
            ['2.2.1.01', 'Simpanan Wajib', 'kewajiban', 'kewajiban_jangka_panjang', 'kredit', '2.2.1', true],
            ['2.2.1.02', 'Simpanan Sukarela', 'kewajiban', 'kewajiban_jangka_panjang', 'kredit', '2.2.1', true],
            ['2.2.1.03', 'Simpanan Berjangka', 'kewajiban', 'kewajiban_jangka_panjang', 'kredit', '2.2.1', true],
            ['2.2.1.04', 'Simpanan Khusus', 'kewajiban', 'kewajiban_jangka_panjang', 'kredit', '2.2.1', true],
            ['2.2.1.05', 'Simpanan Wadiah', 'kewajiban', 'kewajiban_jangka_panjang', 'kredit', '2.2.1', true],
            ['2.2.1.06', 'Simpanan Mudharabah', 'kewajiban', 'kewajiban_jangka_panjang', 'kredit', '2.2.1', true],

            // EKUITAS (3.x)
            ['3', 'EKUITAS', 'ekuitas', 'modal', 'kredit', null, false],
            ['3.1', 'Simpanan Pokok', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.2', 'Simpanan Wajib (Modal)', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.3', 'Modal Penyertaan', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.4', 'Cadangan', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.5', 'Dana Pendidikan', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.6', 'Dana Sosial', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.7', 'Dana Pengurus', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.8', 'Dana Karyawan', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.9', 'SHU Tahun Berjalan', 'ekuitas', 'modal', 'kredit', '3', true],
            ['3.10', 'SHU Belum Dibagi', 'ekuitas', 'modal', 'kredit', '3', true],

            // PENDAPATAN (4.x)
            ['4', 'PENDAPATAN', 'pendapatan', 'pendapatan_operasional', 'kredit', null, false],
            ['4.1', 'Pendapatan Operasional', 'pendapatan', 'pendapatan_operasional', 'kredit', '4', false],
            ['4.1.1', 'Pendapatan dari Pinjaman', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1', false],
            ['4.1.1.01', 'Pendapatan Bunga Pinjaman', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1.1', true],
            ['4.1.1.02', 'Pendapatan Margin Murabahah', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1.1', true],
            ['4.1.1.03', 'Pendapatan Bagi Hasil Mudharabah', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1.1', true],
            ['4.1.1.04', 'Pendapatan Bagi Hasil Musyarakah', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1.1', true],
            ['4.1.1.05', 'Pendapatan Sewa Ijarah', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1.1', true],
            ['4.1.2', 'Pendapatan Toko', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1', true],
            ['4.1.3', 'Pendapatan Unit Produsen', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1', true],
            ['4.1.4', 'Pendapatan Unit Jasa', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.1', true],
            ['4.2', 'Pendapatan Lain', 'pendapatan', 'pendapatan_operasional', 'kredit', '4', false],
            ['4.2.1', 'Pendapatan Administrasi', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.2', false],
            ['4.2.1.01', 'Pendapatan Biaya Admin Pinjaman', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.2.1', true],
            ['4.2.1.02', 'Pendapatan Provisi', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.2.1', true],
            ['4.2.1.03', 'Pendapatan Denda', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.2.1', true],
            ['4.2.2', 'Pendapatan Bunga Bank', 'pendapatan', 'pendapatan_operasional', 'kredit', '4.2', true],

            // BEBAN (5.x)
            ['5', 'BEBAN', 'beban', 'beban_operasional', 'debit', null, false],
            ['5.1', 'Beban Operasional', 'beban', 'beban_operasional', 'debit', '5', false],
            ['5.1.1', 'Beban Personalia', 'beban', 'beban_operasional', 'debit', '5.1', false],
            ['5.1.1.01', 'Beban Gaji & Tunjangan', 'beban', 'beban_operasional', 'debit', '5.1.1', true],
            ['5.1.1.02', 'Beban BPJS', 'beban', 'beban_operasional', 'debit', '5.1.1', true],
            ['5.1.1.03', 'Beban Lembur', 'beban', 'beban_operasional', 'debit', '5.1.1', true],
            ['5.1.2', 'Beban Bunga Simpanan', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.3', 'Beban Bagi Hasil Simpanan Mudharabah', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.4', 'Beban Penyusutan', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.5', 'Beban PPAP (Cadangan Risiko Kredit)', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.6', 'Beban Sewa', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.7', 'Beban Listrik, Air, Telp', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.8', 'Beban ATK & Cetakan', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.9', 'Beban Transportasi', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.10', 'Beban Pemeliharaan', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.11', 'HPP Toko', 'beban', 'beban_operasional', 'debit', '5.1', true],
            ['5.1.99', 'Beban Lain-lain', 'beban', 'beban_operasional', 'debit', '5.1', true],
        ];

        foreach ($accounts as $row) {
            [$kode, $nama, $tipe, $kelompok, $saldoNormal, $parentKode, $isPostable] = $row + [null, null, null, null, null, null, false];
            $extra = $row[7] ?? [];

            $parentId = null;
            if ($parentKode) {
                $parent = Coa::where('kode', $parentKode)->first();
                $parentId = $parent?->id;
            }

            Coa::firstOrCreate(
                ['kode' => $kode],
                array_merge([
                    'tenant_id'    => 1,
                    'nama'         => $nama,
                    'tipe'         => $tipe,
                    'kelompok'     => $kelompok,
                    'saldo_normal' => $saldoNormal,
                    'parent_id'    => $parentId,
                    'is_postable'  => $isPostable,
                    'is_aktif'     => true,
                ], $extra)
            );
        }
    }
}
