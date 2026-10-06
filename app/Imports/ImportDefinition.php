<?php

namespace App\Imports;

/**
 * Definisi kolom per tipe import: field, label, required, unique_key?, validasi.
 */
class ImportDefinition
{
    public static function types(): array
    {
        return [
            'members' => 'Anggota',
            'coa' => 'Akun (COA)',
            'produk_simpanan' => 'Produk Simpanan',
            'produk_pinjaman' => 'Produk Pinjaman',
            'simpanan_awal' => 'Saldo Awal Simpanan (finansial)',
            'pinjaman_awal' => 'Outstanding Awal Pinjaman (finansial)',
        ];
    }

    public static function columns(string $type): array
    {
        return match ($type) {
            'members' => [
                ['field' => 'nomor_anggota', 'label' => 'Nomor Anggota', 'required' => false, 'unique' => 'anggota|nomor_anggota'],
                ['field' => 'nama', 'label' => 'Nama Lengkap', 'required' => true, 'max' => 255],
                ['field' => 'nik', 'label' => 'NIK', 'required' => false, 'unique' => 'anggota|nik'],
                ['field' => 'telp', 'label' => 'Telepon', 'required' => false, 'max' => 30],
                ['field' => 'email', 'label' => 'Email', 'required' => false, 'email' => true],
                ['field' => 'alamat', 'label' => 'Alamat', 'required' => false],
                ['field' => 'tanggal_masuk', 'label' => 'Tanggal Masuk (YYYY-MM-DD / DD/MM/YYYY)', 'required' => false, 'date' => true],
                ['field' => 'status', 'label' => 'Status', 'required' => false, 'enum' => ['aktif', 'tidak_aktif', 'keluar', 'meninggal', 'dikeluarkan']],
            ],
            'coa' => [
                ['field' => 'kode', 'label' => 'Kode Akun', 'required' => true, 'unique' => 'coa|kode'],
                ['field' => 'nama', 'label' => 'Nama Akun', 'required' => true, 'max' => 255],
                ['field' => 'tipe', 'label' => 'Tipe (aset/kewajiban/ekuitas/pendapatan/beban)', 'required' => true, 'enum' => ['aset', 'kewajiban', 'ekuitas', 'pendapatan', 'beban']],
                ['field' => 'saldo_normal', 'label' => 'Saldo Normal (debit/kredit)', 'required' => true, 'enum' => ['debit', 'kredit']],
                ['field' => 'is_postable', 'label' => 'Bisa diposting (ya/tidak)', 'required' => false, 'bool' => true],
            ],
            'produk_simpanan' => [
                ['field' => 'kode', 'label' => 'Kode', 'required' => true, 'unique' => 'produk_simpanan|kode'],
                ['field' => 'nama', 'label' => 'Nama Produk', 'required' => true, 'max' => 255],
                ['field' => 'jenis', 'label' => 'Jenis (pokok/wajib/sukarela/berjangka/khusus)', 'required' => true, 'enum' => ['pokok', 'wajib', 'sukarela', 'berjangka', 'khusus', 'wadiah', 'mudharabah']],
                ['field' => 'boleh_tarik', 'label' => 'Boleh ditarik (ya/tidak)', 'required' => false, 'bool' => true],
            ],
            'produk_pinjaman' => [
                ['field' => 'kode', 'label' => 'Kode', 'required' => true, 'unique' => 'produk_pinjaman|kode'],
                ['field' => 'nama', 'label' => 'Nama Produk', 'required' => true, 'max' => 255],
                ['field' => 'akad_type', 'label' => 'Akad (flat/efektif/anuitas/murabahah/…)', 'required' => true],
                ['field' => 'bunga_persen', 'label' => 'Bunga/Margin %', 'required' => false, 'numeric' => true],
            ],
            'simpanan_awal' => [
                ['field' => 'nomor_anggota', 'label' => 'Nomor Anggota (harus ada)', 'required' => true, 'exists' => 'anggota|nomor_anggota'],
                ['field' => 'kode_produk', 'label' => 'Kode Produk Simpanan', 'required' => true, 'exists' => 'produk_simpanan|kode'],
                ['field' => 'nomor_rekening', 'label' => 'Nomor Rekening (kosong = auto)', 'required' => false, 'unique' => 'simpanan|nomor_rekening'],
                ['field' => 'saldo', 'label' => 'Saldo Awal (Rp, format Indonesia OK)', 'required' => true, 'numeric' => true],
                ['field' => 'tanggal_buka', 'label' => 'Tanggal Buka', 'required' => false, 'date' => true],
            ],
            'pinjaman_awal' => [
                ['field' => 'nomor_anggota', 'label' => 'Nomor Anggota (harus ada)', 'required' => true, 'exists' => 'anggota|nomor_anggota'],
                ['field' => 'kode_produk', 'label' => 'Kode Produk Pinjaman', 'required' => true, 'exists' => 'produk_pinjaman|kode'],
                ['field' => 'nomor_akad', 'label' => 'Nomor Akad (kosong = auto)', 'required' => false, 'unique' => 'pinjaman|nomor_akad'],
                ['field' => 'pokok', 'label' => 'Sisa Pokok (Rp)', 'required' => true, 'numeric' => true],
                ['field' => 'tenor', 'label' => 'Tenor (bulan)', 'required' => false, 'numeric' => true],
                ['field' => 'tanggal_pengajuan', 'label' => 'Tanggal Pengajuan', 'required' => false, 'date' => true],
            ],
            default => throw new \InvalidArgumentException("Tipe import '{$type}' tidak dikenal."),
        };
    }

    public static function templateHeaders(string $type): array
    {
        return array_column(self::columns($type), 'label');
    }
}
