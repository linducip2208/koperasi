<?php

namespace Database\Seeders;

use App\Models\PpobProduk;
use Illuminate\Database\Seeder;

class PpobSeeder extends Seeder
{
    public function run(): void
    {
        $produk = [
            // Pulsa
            ['kategori' => 'pulsa', 'kode' => 'P5', 'nama' => 'Pulsa 5.000', 'nominal' => '5000', 'harga_jual' => 6000, 'harga_beli' => 5400],
            ['kategori' => 'pulsa', 'kode' => 'P10', 'nama' => 'Pulsa 10.000', 'nominal' => '10000', 'harga_jual' => 11000, 'harga_beli' => 10200],
            ['kategori' => 'pulsa', 'kode' => 'P20', 'nama' => 'Pulsa 20.000', 'nominal' => '20000', 'harga_jual' => 21000, 'harga_beli' => 19700],
            ['kategori' => 'pulsa', 'kode' => 'P50', 'nama' => 'Pulsa 50.000', 'nominal' => '50000', 'harga_jual' => 51000, 'harga_beli' => 48700],
            ['kategori' => 'pulsa', 'kode' => 'P100', 'nama' => 'Pulsa 100.000', 'nominal' => '100000', 'harga_jual' => 101000, 'harga_beli' => 97200],

            // PLN
            ['kategori' => 'pln', 'kode' => 'PLN20', 'nama' => 'Token Listrik 20.000', 'nominal' => '20000', 'harga_jual' => 21500, 'harga_beli' => 20200],
            ['kategori' => 'pln', 'kode' => 'PLN50', 'nama' => 'Token Listrik 50.000', 'nominal' => '50000', 'harga_jual' => 51500, 'harga_beli' => 50200],
            ['kategori' => 'pln', 'kode' => 'PLN100', 'nama' => 'Token Listrik 100.000', 'nominal' => '100000', 'harga_jual' => 101500, 'harga_beli' => 100000],

            // BPJS
            ['kategori' => 'bpjs', 'kode' => 'BPJS1', 'nama' => 'BPJS Kesehatan Kelas 1', 'nominal' => '150000', 'harga_jual' => 152000, 'harga_beli' => 150000],
            ['kategori' => 'bpjs', 'kode' => 'BPJS2', 'nama' => 'BPJS Kesehatan Kelas 2', 'nominal' => '100000', 'harga_jual' => 102000, 'harga_beli' => 100000],
            ['kategori' => 'bpjs', 'kode' => 'BPJS3', 'nama' => 'BPJS Kesehatan Kelas 3', 'nominal' => '42000', 'harga_jual' => 44000, 'harga_beli' => 42000],

            // E-Wallet
            ['kategori' => 'ewallet', 'kode' => 'GOPAY20', 'nama' => 'GoPay 20.000', 'nominal' => '20000', 'harga_jual' => 21000, 'harga_beli' => 19500],
            ['kategori' => 'ewallet', 'kode' => 'OVO20', 'nama' => 'OVO 20.000', 'nominal' => '20000', 'harga_jual' => 21000, 'harga_beli' => 19500],
            ['kategori' => 'ewallet', 'kode' => 'DANA20', 'nama' => 'Dana 20.000', 'nominal' => '20000', 'harga_jual' => 21000, 'harga_beli' => 19500],

            // PDAM
            ['kategori' => 'pdam', 'kode' => 'PDAM', 'nama' => 'Bayar PDAM', 'nominal' => 'input', 'harga_jual' => 0, 'harga_beli' => 0],
            ['kategori' => 'internet', 'kode' => 'INET', 'nama' => 'Bayar Internet', 'nominal' => 'input', 'harga_jual' => 0, 'harga_beli' => 0],
        ];

        foreach ($produk as $p) {
            PpobProduk::firstOrCreate(['kode' => $p['kode']], $p + ['aktif' => true]);
        }
    }
}
