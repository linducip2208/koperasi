-- ============================================================
--  KOPERASI APP - Production Install SQL
--  Laravel 12 + Filament 3.3 | Database: MySQL 8.0+
--
--  PETUNJUK PENGGUNAAN:
--  1. Buat database baru:  CREATE DATABASE koperasi CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
--  2. Import file ini:     mysql -u root -p koperasi < koperasi_production_install.sql
--  3. Login admin:         admin@koperasi.local / admin123
--  4. Ganti password admin segera setelah login pertama!
--
--  File ini berisi: Schema + Admin User + Default Tenant + Roles & Permissions
--  Tidak berisi data demo. Untuk demo, gunakan koperasi_full_with_demo.sql
-- ============================================================

SET FOREIGN_KEY_CHECKS=0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `activity_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint unsigned DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `ahli_waris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `ahli_waris` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hubungan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `persentase` decimal(5,2) NOT NULL DEFAULT '100.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ahli_waris_tenant_id_foreign` (`tenant_id`),
  KEY `ahli_waris_anggota_id_foreign` (`anggota_id`),
  CONSTRAINT `ahli_waris_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ahli_waris_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `anggaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `anggaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `coa_id` bigint unsigned NOT NULL,
  `jan` bigint NOT NULL DEFAULT '0',
  `feb` bigint NOT NULL DEFAULT '0',
  `mar` bigint NOT NULL DEFAULT '0',
  `apr` bigint NOT NULL DEFAULT '0',
  `mei` bigint NOT NULL DEFAULT '0',
  `jun` bigint NOT NULL DEFAULT '0',
  `jul` bigint NOT NULL DEFAULT '0',
  `agu` bigint NOT NULL DEFAULT '0',
  `sep` bigint NOT NULL DEFAULT '0',
  `okt` bigint NOT NULL DEFAULT '0',
  `nov` bigint NOT NULL DEFAULT '0',
  `des` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anggaran_tenant_id_tahun_coa_id_unique` (`tenant_id`,`tahun`,`coa_id`),
  KEY `anggaran_coa_id_foreign` (`coa_id`),
  CONSTRAINT `anggaran_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `anggaran_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `anggota` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `nomor_anggota` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_perkawinan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rt` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rw` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelurahan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kota` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provinsi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_pos` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npwp` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_perusahaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `penghasilan_bulanan` bigint DEFAULT NULL,
  `sumber_dana` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ktp_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kk_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'biasa',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `alasan_keluar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `anggota_nomor_anggota_unique` (`nomor_anggota`),
  KEY `anggota_cabang_id_foreign` (`cabang_id`),
  KEY `anggota_user_id_foreign` (`user_id`),
  KEY `anggota_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `anggota_tenant_id_nama_index` (`tenant_id`,`nama`),
  KEY `anggota_nik_index` (`nik`),
  CONSTRAINT `anggota_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `anggota_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anggota_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `anggota_status_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `anggota_status_log` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `dari_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ke_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `anggota_status_log_tenant_id_foreign` (`tenant_id`),
  KEY `anggota_status_log_anggota_id_foreign` (`anggota_id`),
  KEY `anggota_status_log_user_id_foreign` (`user_id`),
  CONSTRAINT `anggota_status_log_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anggota_status_log_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `anggota_status_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `asset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `asset` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `kode` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_perolehan` date NOT NULL,
  `harga_perolehan` bigint NOT NULL,
  `nilai_residu` bigint NOT NULL DEFAULT '0',
  `umur_ekonomis_bulan` int NOT NULL,
  `metode_susut` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'garis_lurus',
  `akumulasi_susut` bigint NOT NULL DEFAULT '0',
  `nilai_buku` bigint NOT NULL DEFAULT '0',
  `coa_aset_id` bigint unsigned DEFAULT NULL,
  `coa_susut_id` bigint unsigned DEFAULT NULL,
  `coa_akumulasi_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `tanggal_dilepas` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asset_kode_unique` (`kode`),
  KEY `asset_tenant_id_foreign` (`tenant_id`),
  KEY `asset_cabang_id_foreign` (`cabang_id`),
  KEY `asset_coa_aset_id_foreign` (`coa_aset_id`),
  KEY `asset_coa_susut_id_foreign` (`coa_susut_id`),
  KEY `asset_coa_akumulasi_id_foreign` (`coa_akumulasi_id`),
  CONSTRAINT `asset_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `asset_coa_akumulasi_id_foreign` FOREIGN KEY (`coa_akumulasi_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `asset_coa_aset_id_foreign` FOREIGN KEY (`coa_aset_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `asset_coa_susut_id_foreign` FOREIGN KEY (`coa_susut_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `asset_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `asset_penyusutan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `asset_penyusutan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `asset_id` bigint unsigned NOT NULL,
  `periode` date NOT NULL,
  `jumlah` bigint NOT NULL,
  `akumulasi` bigint NOT NULL,
  `nilai_buku` bigint NOT NULL,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asset_penyusutan_tenant_id_foreign` (`tenant_id`),
  KEY `asset_penyusutan_asset_id_foreign` (`asset_id`),
  KEY `asset_penyusutan_jurnal_id_foreign` (`jurnal_id`),
  CONSTRAINT `asset_penyusutan_asset_id_foreign` FOREIGN KEY (`asset_id`) REFERENCES `asset` (`id`) ON DELETE CASCADE,
  CONSTRAINT `asset_penyusutan_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `asset_penyusutan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `asuransi_klaim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `asuransi_klaim` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `polis_id` bigint unsigned NOT NULL,
  `tanggal_kejadian` date NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `nilai_klaim` bigint NOT NULL,
  `uraian` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengajuan',
  `nilai_diterima` bigint NOT NULL DEFAULT '0',
  `tanggal_diterima` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asuransi_klaim_tenant_id_foreign` (`tenant_id`),
  KEY `asuransi_klaim_polis_id_foreign` (`polis_id`),
  CONSTRAINT `asuransi_klaim_polis_id_foreign` FOREIGN KEY (`polis_id`) REFERENCES `asuransi_polis` (`id`),
  CONSTRAINT `asuransi_klaim_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `asuransi_polis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `asuransi_polis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `pinjaman_id` bigint unsigned DEFAULT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `nomor_polis` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nilai_pertanggungan` bigint NOT NULL,
  `premi` bigint NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_akhir` date NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `asuransi_polis_nomor_polis_unique` (`nomor_polis`),
  KEY `asuransi_polis_tenant_id_foreign` (`tenant_id`),
  KEY `asuransi_polis_produk_id_foreign` (`produk_id`),
  KEY `asuransi_polis_pinjaman_id_foreign` (`pinjaman_id`),
  KEY `asuransi_polis_anggota_id_foreign` (`anggota_id`),
  CONSTRAINT `asuransi_polis_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  CONSTRAINT `asuransi_polis_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`) ON DELETE SET NULL,
  CONSTRAINT `asuransi_polis_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `asuransi_produk` (`id`),
  CONSTRAINT `asuransi_polis_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `asuransi_produk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `asuransi_produk` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `penanggung` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_premi` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `asuransi_produk_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `asuransi_produk_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `cabang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `cabang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kantor_pusat',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cabang_kode_unique` (`kode`),
  KEY `cabang_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `cabang_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `coa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `coa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelompok` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saldo_normal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `is_kas` tinyint(1) NOT NULL DEFAULT '0',
  `is_bank` tinyint(1) NOT NULL DEFAULT '0',
  `is_postable` tinyint(1) NOT NULL DEFAULT '1',
  `is_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `saldo_awal` bigint NOT NULL DEFAULT '0',
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coa_tenant_id_kode_unique` (`tenant_id`,`kode`),
  KEY `coa_parent_id_foreign` (`parent_id`),
  KEY `coa_tenant_id_tipe_index` (`tenant_id`,`tipe`),
  CONSTRAINT `coa_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `coa` (`id`) ON DELETE SET NULL,
  CONSTRAINT `coa_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `gaji`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `gaji` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `karyawan_id` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `bulan` int NOT NULL,
  `gaji_pokok` bigint NOT NULL,
  `total_tunjangan` bigint NOT NULL DEFAULT '0',
  `lembur` bigint NOT NULL DEFAULT '0',
  `total_potongan` bigint NOT NULL DEFAULT '0',
  `pph21` bigint NOT NULL DEFAULT '0',
  `bpjs_potongan` bigint NOT NULL DEFAULT '0',
  `total_bruto` bigint NOT NULL,
  `total_netto` bigint NOT NULL,
  `detail` json DEFAULT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gaji_tenant_id_karyawan_id_tahun_bulan_unique` (`tenant_id`,`karyawan_id`,`tahun`,`bulan`),
  KEY `gaji_karyawan_id_foreign` (`karyawan_id`),
  KEY `gaji_jurnal_id_foreign` (`jurnal_id`),
  CONSTRAINT `gaji_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `gaji_karyawan_id_foreign` FOREIGN KEY (`karyawan_id`) REFERENCES `karyawan` (`id`),
  CONSTRAINT `gaji_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `jurnal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `jurnal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `nomor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum',
  `referensi_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi_id` bigint unsigned DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_debit` bigint NOT NULL DEFAULT '0',
  `total_kredit` bigint NOT NULL DEFAULT '0',
  `is_posted` tinyint(1) NOT NULL DEFAULT '0',
  `posted_at` timestamp NULL DEFAULT NULL,
  `posted_by` bigint unsigned DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jurnal_nomor_unique` (`nomor`),
  KEY `jurnal_cabang_id_foreign` (`cabang_id`),
  KEY `jurnal_posted_by_foreign` (`posted_by`),
  KEY `jurnal_created_by_foreign` (`created_by`),
  KEY `jurnal_tenant_id_tanggal_index` (`tenant_id`,`tanggal`),
  KEY `jurnal_referensi_type_referensi_id_index` (`referensi_type`,`referensi_id`),
  CONSTRAINT `jurnal_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `jurnal_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `jurnal_posted_by_foreign` FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `jurnal_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=801 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `jurnal_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `jurnal_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `jurnal_id` bigint unsigned NOT NULL,
  `coa_id` bigint unsigned NOT NULL,
  `debit` bigint NOT NULL DEFAULT '0',
  `kredit` bigint NOT NULL DEFAULT '0',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnal_detail_jurnal_id_foreign` (`jurnal_id`),
  KEY `jurnal_detail_coa_id_foreign` (`coa_id`),
  KEY `jurnal_detail_tenant_id_coa_id_index` (`tenant_id`,`coa_id`),
  CONSTRAINT `jurnal_detail_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `jurnal_detail_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `jurnal_detail_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `karyawan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `nip` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departemen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `tanggal_keluar` date DEFAULT NULL,
  `gaji_pokok` bigint NOT NULL DEFAULT '0',
  `tunjangan` json DEFAULT NULL,
  `npwp` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_bpjs_kesehatan` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_bpjs_naker` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rekening_bank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nomor_rekening` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `karyawan_nip_unique` (`nip`),
  KEY `karyawan_tenant_id_foreign` (`tenant_id`),
  KEY `karyawan_cabang_id_foreign` (`cabang_id`),
  KEY `karyawan_user_id_foreign` (`user_id`),
  CONSTRAINT `karyawan_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `karyawan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `karyawan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `kas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `kas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kas',
  `nomor_rekening` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_bank` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `atas_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coa_id` bigint unsigned NOT NULL,
  `saldo_awal` bigint NOT NULL DEFAULT '0',
  `saldo` bigint NOT NULL DEFAULT '0',
  `limit_minimum` bigint NOT NULL DEFAULT '0',
  `user_id` bigint unsigned DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kas_tenant_id_kode_unique` (`tenant_id`,`kode`),
  KEY `kas_cabang_id_foreign` (`cabang_id`),
  KEY `kas_coa_id_foreign` (`coa_id`),
  KEY `kas_user_id_foreign` (`user_id`),
  CONSTRAINT `kas_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kas_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `kas_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `kas_opname`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `kas_opname` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kas_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `saldo_sistem` bigint NOT NULL,
  `saldo_fisik` bigint NOT NULL,
  `selisih` bigint NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kas_opname_tenant_id_foreign` (`tenant_id`),
  KEY `kas_opname_kas_id_foreign` (`kas_id`),
  KEY `kas_opname_user_id_foreign` (`user_id`),
  CONSTRAINT `kas_opname_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `kas_opname_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kas_opname_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `kas_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `kas_transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kas_id` bigint unsigned NOT NULL,
  `nomor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` bigint NOT NULL,
  `coa_id` bigint unsigned DEFAULT NULL,
  `kas_tujuan_id` bigint unsigned DEFAULT NULL,
  `referensi_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi_id` bigint unsigned DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kas_transaksi_nomor_unique` (`nomor`),
  KEY `kas_transaksi_kas_id_foreign` (`kas_id`),
  KEY `kas_transaksi_coa_id_foreign` (`coa_id`),
  KEY `kas_transaksi_kas_tujuan_id_foreign` (`kas_tujuan_id`),
  KEY `kas_transaksi_jurnal_id_foreign` (`jurnal_id`),
  KEY `kas_transaksi_user_id_foreign` (`user_id`),
  KEY `kas_transaksi_tenant_id_tanggal_index` (`tenant_id`,`tanggal`),
  CONSTRAINT `kas_transaksi_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `kas_transaksi_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kas_transaksi_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `kas_transaksi_kas_tujuan_id_foreign` FOREIGN KEY (`kas_tujuan_id`) REFERENCES `kas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `kas_transaksi_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `kas_transaksi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversions_disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint unsigned NOT NULL,
  `manipulations` json NOT NULL,
  `custom_properties` json NOT NULL,
  `generated_conversions` json NOT NULL,
  `responsive_images` json NOT NULL,
  `order_column` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_uuid_unique` (`uuid`),
  KEY `media_model_type_model_id_index` (`model_type`,`model_id`),
  KEY `media_order_column_index` (`order_column`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `notifikasi_template`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `notifikasi_template` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notifikasi_template_tenant_id_kode_channel_unique` (`tenant_id`,`kode`,`channel`),
  CONSTRAINT `notifikasi_template_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `numbering_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `numbering_setting` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{prefix}{ymd}{seq:5}',
  `panjang_seq` int NOT NULL DEFAULT '5',
  `reset_period` int NOT NULL DEFAULT '0',
  `next_number` bigint NOT NULL DEFAULT '1',
  `last_reset_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numbering_setting_tenant_id_kode_unique` (`tenant_id`,`kode`),
  CONSTRAINT `numbering_setting_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `payment_providers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `payment_providers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_format` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `base_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `session_endpoint` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key_encrypted` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `merchant_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extra_headers` json DEFAULT NULL,
  `is_sandbox` tinyint(1) NOT NULL DEFAULT '1',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `periode_akuntansi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `periode_akuntansi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `bulan` int NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_akhir` date NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `closed_at` timestamp NULL DEFAULT NULL,
  `closed_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `periode_akuntansi_tenant_id_tahun_bulan_unique` (`tenant_id`,`tahun`,`bulan`),
  KEY `periode_akuntansi_closed_by_foreign` (`closed_by`),
  CONSTRAINT `periode_akuntansi_closed_by_foreign` FOREIGN KEY (`closed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `periode_akuntansi_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `pinjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `pinjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `nomor_akad` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_akad` date DEFAULT NULL,
  `tanggal_pencairan` date DEFAULT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `plafon` bigint NOT NULL,
  `pokok` bigint NOT NULL,
  `margin_total` bigint NOT NULL DEFAULT '0',
  `total_bayar` bigint NOT NULL DEFAULT '0',
  `tenor` int NOT NULL,
  `bunga_persen` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `margin_persen` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `nisbah_anggota` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nisbah_koperasi` decimal(5,2) NOT NULL DEFAULT '0.00',
  `biaya_admin` bigint NOT NULL DEFAULT '0',
  `biaya_provisi` bigint NOT NULL DEFAULT '0',
  `biaya_asuransi` bigint NOT NULL DEFAULT '0',
  `biaya_materai` bigint NOT NULL DEFAULT '0',
  `total_biaya` bigint NOT NULL DEFAULT '0',
  `pencairan_bersih` bigint NOT NULL DEFAULT '0',
  `saldo_pokok` bigint NOT NULL DEFAULT '0',
  `saldo_margin` bigint NOT NULL DEFAULT '0',
  `tujuan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `analisa_kredit` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `survey_data` json DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pengajuan',
  `kolektabilitas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lancar',
  `tunggakan_hari` int NOT NULL DEFAULT '0',
  `tunggakan_pokok` bigint NOT NULL DEFAULT '0',
  `tunggakan_margin` bigint NOT NULL DEFAULT '0',
  `tunggakan_denda` bigint NOT NULL DEFAULT '0',
  `ao_id` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_by` bigint unsigned DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `alasan_tolak` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pinjaman_nomor_akad_unique` (`nomor_akad`),
  KEY `pinjaman_cabang_id_foreign` (`cabang_id`),
  KEY `pinjaman_anggota_id_foreign` (`anggota_id`),
  KEY `pinjaman_produk_id_foreign` (`produk_id`),
  KEY `pinjaman_ao_id_foreign` (`ao_id`),
  KEY `pinjaman_approved_by_foreign` (`approved_by`),
  KEY `pinjaman_rejected_by_foreign` (`rejected_by`),
  KEY `pinjaman_tenant_id_status_index` (`tenant_id`,`status`),
  KEY `pinjaman_tenant_id_kolektabilitas_index` (`tenant_id`,`kolektabilitas`),
  KEY `pinjaman_tenant_id_anggota_id_index` (`tenant_id`,`anggota_id`),
  CONSTRAINT `pinjaman_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  CONSTRAINT `pinjaman_ao_id_foreign` FOREIGN KEY (`ao_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pinjaman_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pinjaman_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pinjaman_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk_pinjaman` (`id`),
  CONSTRAINT `pinjaman_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pinjaman_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `pinjaman_approval`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `pinjaman_approval` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `pinjaman_id` bigint unsigned NOT NULL,
  `level` int NOT NULL,
  `jabatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `keputusan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `decided_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pinjaman_approval_tenant_id_foreign` (`tenant_id`),
  KEY `pinjaman_approval_pinjaman_id_foreign` (`pinjaman_id`),
  KEY `pinjaman_approval_user_id_foreign` (`user_id`),
  CONSTRAINT `pinjaman_approval_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pinjaman_approval_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pinjaman_approval_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `pinjaman_jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `pinjaman_jadwal` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `pinjaman_id` bigint unsigned NOT NULL,
  `angsuran_ke` int NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `pokok` bigint NOT NULL,
  `margin` bigint NOT NULL,
  `total_angsuran` bigint NOT NULL,
  `saldo_pokok` bigint NOT NULL,
  `terbayar_pokok` bigint NOT NULL DEFAULT '0',
  `terbayar_margin` bigint NOT NULL DEFAULT '0',
  `denda` bigint NOT NULL DEFAULT '0',
  `terbayar_denda` bigint NOT NULL DEFAULT '0',
  `tanggal_bayar` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_jatuh_tempo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pinjaman_jadwal_pinjaman_id_foreign` (`pinjaman_id`),
  KEY `pinjaman_jadwal_tenant_id_pinjaman_id_angsuran_ke_index` (`tenant_id`,`pinjaman_id`,`angsuran_ke`),
  CONSTRAINT `pinjaman_jadwal_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pinjaman_jadwal_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=154831 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `pinjaman_jaminan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `pinjaman_jaminan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `pinjaman_id` bigint unsigned NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nomor_dokumen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `atas_nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nilai_taksiran` bigint NOT NULL,
  `nilai_pasar` bigint DEFAULT NULL,
  `ltv` decimal(5,2) DEFAULT NULL,
  `foto_path` json DEFAULT NULL,
  `dokumen_path` json DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `tanggal_lepas` date DEFAULT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pinjaman_jaminan_tenant_id_foreign` (`tenant_id`),
  KEY `pinjaman_jaminan_pinjaman_id_foreign` (`pinjaman_id`),
  CONSTRAINT `pinjaman_jaminan_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pinjaman_jaminan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `pinjaman_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `pinjaman_pembayaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `pinjaman_id` bigint unsigned NOT NULL,
  `nomor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'angsuran',
  `total_bayar` bigint NOT NULL,
  `alokasi_pokok` bigint NOT NULL DEFAULT '0',
  `alokasi_margin` bigint NOT NULL DEFAULT '0',
  `alokasi_denda` bigint NOT NULL DEFAULT '0',
  `alokasi_admin` bigint NOT NULL DEFAULT '0',
  `alokasi_titipan` bigint NOT NULL DEFAULT '0',
  `kas_id` bigint unsigned DEFAULT NULL,
  `metode_bayar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pinjaman_pembayaran_nomor_unique` (`nomor`),
  KEY `pinjaman_pembayaran_pinjaman_id_foreign` (`pinjaman_id`),
  KEY `pinjaman_pembayaran_kas_id_foreign` (`kas_id`),
  KEY `pinjaman_pembayaran_jurnal_id_foreign` (`jurnal_id`),
  KEY `pinjaman_pembayaran_user_id_foreign` (`user_id`),
  KEY `pinjaman_pembayaran_tenant_id_tanggal_index` (`tenant_id`,`tanggal`),
  CONSTRAINT `pinjaman_pembayaran_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pinjaman_pembayaran_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `pinjaman_pembayaran_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`),
  CONSTRAINT `pinjaman_pembayaran_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pinjaman_pembayaran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4802 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `pinjaman_restrukturisasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `pinjaman_restrukturisasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `pinjaman_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sebelum` json NOT NULL,
  `sesudah` json NOT NULL,
  `alasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pinjaman_restrukturisasi_tenant_id_foreign` (`tenant_id`),
  KEY `pinjaman_restrukturisasi_pinjaman_id_foreign` (`pinjaman_id`),
  KEY `pinjaman_restrukturisasi_user_id_foreign` (`user_id`),
  CONSTRAINT `pinjaman_restrukturisasi_pinjaman_id_foreign` FOREIGN KEY (`pinjaman_id`) REFERENCES `pinjaman` (`id`),
  CONSTRAINT `pinjaman_restrukturisasi_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pinjaman_restrukturisasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `produk_pinjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `produk_pinjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'produktif',
  `akad_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'konvensional',
  `metode_perhitungan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `plafon_minimum` bigint NOT NULL DEFAULT '0',
  `plafon_maksimum` bigint NOT NULL DEFAULT '0',
  `tenor_minimum` int NOT NULL DEFAULT '1',
  `tenor_maksimum` int NOT NULL DEFAULT '60',
  `bunga_persen` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `margin_persen` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `nisbah_anggota` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nisbah_koperasi` decimal(5,2) NOT NULL DEFAULT '0.00',
  `biaya_admin_persen` decimal(5,2) NOT NULL DEFAULT '0.00',
  `biaya_admin_flat` bigint NOT NULL DEFAULT '0',
  `biaya_provisi_persen` decimal(5,2) NOT NULL DEFAULT '0.00',
  `biaya_asuransi_persen` decimal(5,2) NOT NULL DEFAULT '0.00',
  `biaya_materai` bigint NOT NULL DEFAULT '0',
  `denda_persen_per_hari` decimal(5,4) NOT NULL DEFAULT '0.0000',
  `denda_flat_per_hari` bigint NOT NULL DEFAULT '0',
  `butuh_jaminan` tinyint(1) NOT NULL DEFAULT '0',
  `butuh_simpanan_blokir` tinyint(1) NOT NULL DEFAULT '0',
  `rasio_simpanan_blokir` decimal(5,2) NOT NULL DEFAULT '0.00',
  `coa_pokok_id` bigint unsigned DEFAULT NULL,
  `coa_bunga_id` bigint unsigned DEFAULT NULL,
  `coa_denda_id` bigint unsigned DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `produk_pinjaman_tenant_id_kode_unique` (`tenant_id`,`kode`),
  KEY `produk_pinjaman_coa_pokok_id_foreign` (`coa_pokok_id`),
  KEY `produk_pinjaman_coa_bunga_id_foreign` (`coa_bunga_id`),
  KEY `produk_pinjaman_coa_denda_id_foreign` (`coa_denda_id`),
  CONSTRAINT `produk_pinjaman_coa_bunga_id_foreign` FOREIGN KEY (`coa_bunga_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `produk_pinjaman_coa_denda_id_foreign` FOREIGN KEY (`coa_denda_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `produk_pinjaman_coa_pokok_id_foreign` FOREIGN KEY (`coa_pokok_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `produk_pinjaman_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `produk_simpanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `produk_simpanan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `akad_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'konvensional',
  `setoran_minimum` bigint NOT NULL DEFAULT '0',
  `setoran_wajib` bigint NOT NULL DEFAULT '0',
  `saldo_minimum` bigint NOT NULL DEFAULT '0',
  `bunga_persen_tahun` decimal(8,4) NOT NULL DEFAULT '0.0000',
  `metode_bunga` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nisbah_anggota` decimal(5,2) NOT NULL DEFAULT '0.00',
  `nisbah_koperasi` decimal(5,2) NOT NULL DEFAULT '0.00',
  `boleh_tarik` tinyint(1) NOT NULL DEFAULT '1',
  `berjangka` tinyint(1) NOT NULL DEFAULT '0',
  `tenor_bulan` int DEFAULT NULL,
  `auto_potong_shu` tinyint(1) NOT NULL DEFAULT '0',
  `coa_simpanan_id` bigint unsigned DEFAULT NULL,
  `coa_bunga_id` bigint unsigned DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `produk_simpanan_tenant_id_kode_unique` (`tenant_id`,`kode`),
  KEY `produk_simpanan_coa_simpanan_id_foreign` (`coa_simpanan_id`),
  KEY `produk_simpanan_coa_bunga_id_foreign` (`coa_bunga_id`),
  CONSTRAINT `produk_simpanan_coa_bunga_id_foreign` FOREIGN KEY (`coa_bunga_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `produk_simpanan_coa_simpanan_id_foreign` FOREIGN KEY (`coa_simpanan_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `produk_simpanan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `rat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `rat` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `tahun_buku` int NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agenda` json DEFAULT NULL,
  `jumlah_anggota_terdaftar` int NOT NULL DEFAULT '0',
  `jumlah_hadir` int NOT NULL DEFAULT '0',
  `quorum_persen` int NOT NULL DEFAULT '50',
  `quorum_tercapai` tinyint(1) NOT NULL DEFAULT '0',
  `notulen` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keputusan` json DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'rencana',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rat_tenant_id_tahun_buku_unique` (`tenant_id`,`tahun_buku`),
  CONSTRAINT `rat_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=301 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `rat_kehadiran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `rat_kehadiran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `rat_id` bigint unsigned NOT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `checkin_at` timestamp NULL DEFAULT NULL,
  `metode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rat_kehadiran_rat_id_anggota_id_unique` (`rat_id`,`anggota_id`),
  KEY `rat_kehadiran_tenant_id_foreign` (`tenant_id`),
  KEY `rat_kehadiran_anggota_id_foreign` (`anggota_id`),
  CONSTRAINT `rat_kehadiran_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  CONSTRAINT `rat_kehadiran_rat_id_foreign` FOREIGN KEY (`rat_id`) REFERENCES `rat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rat_kehadiran_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `rekonsiliasi_bank`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `rekonsiliasi_bank` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kas_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `periode_akhir` date NOT NULL,
  `saldo_buku` bigint NOT NULL,
  `saldo_bank` bigint NOT NULL,
  `selisih` bigint NOT NULL,
  `rincian` json DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rekonsiliasi_bank_tenant_id_foreign` (`tenant_id`),
  KEY `rekonsiliasi_bank_kas_id_foreign` (`kas_id`),
  KEY `rekonsiliasi_bank_user_id_foreign` (`user_id`),
  CONSTRAINT `rekonsiliasi_bank_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `rekonsiliasi_bank_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `rekonsiliasi_bank_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `group` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_tenant_id_group_key_unique` (`tenant_id`,`group`,`key`),
  CONSTRAINT `settings_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `shu_distribusi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `shu_distribusi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `shu_perhitungan_id` bigint unsigned NOT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `total_simpanan` bigint NOT NULL,
  `total_transaksi` bigint NOT NULL,
  `jasa_modal` bigint NOT NULL,
  `jasa_anggota` bigint NOT NULL,
  `total_shu` bigint NOT NULL,
  `metode_distribusi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'simpanan_sukarela',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dibagikan',
  `distributed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shu_distribusi_shu_perhitungan_id_foreign` (`shu_perhitungan_id`),
  KEY `shu_distribusi_anggota_id_foreign` (`anggota_id`),
  KEY `shu_distribusi_tenant_id_anggota_id_index` (`tenant_id`,`anggota_id`),
  CONSTRAINT `shu_distribusi_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  CONSTRAINT `shu_distribusi_shu_perhitungan_id_foreign` FOREIGN KEY (`shu_perhitungan_id`) REFERENCES `shu_perhitungan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `shu_distribusi_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `shu_komponen_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `shu_komponen_setting` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `nama_komponen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `basis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `persen` decimal(5,2) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shu_komponen_setting_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `shu_komponen_setting_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `shu_perhitungan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `shu_perhitungan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `tahun` int NOT NULL,
  `shu_total` bigint NOT NULL,
  `persen_jasa_modal` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persen_jasa_anggota` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persen_dana_cadangan` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persen_dana_pendidikan` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persen_dana_sosial` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persen_dana_pengurus` decimal(5,2) NOT NULL DEFAULT '0.00',
  `persen_dana_karyawan` decimal(5,2) NOT NULL DEFAULT '0.00',
  `jumlah_jasa_modal` bigint NOT NULL DEFAULT '0',
  `jumlah_jasa_anggota` bigint NOT NULL DEFAULT '0',
  `jumlah_dana_cadangan` bigint NOT NULL DEFAULT '0',
  `jumlah_dana_pendidikan` bigint NOT NULL DEFAULT '0',
  `jumlah_dana_sosial` bigint NOT NULL DEFAULT '0',
  `jumlah_dana_pengurus` bigint NOT NULL DEFAULT '0',
  `jumlah_dana_karyawan` bigint NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `approved_at` timestamp NULL DEFAULT NULL,
  `distributed_at` timestamp NULL DEFAULT NULL,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shu_perhitungan_tenant_id_tahun_unique` (`tenant_id`,`tahun`),
  KEY `shu_perhitungan_jurnal_id_foreign` (`jurnal_id`),
  CONSTRAINT `shu_perhitungan_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `shu_perhitungan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `simpanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `simpanan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `produk_id` bigint unsigned NOT NULL,
  `nomor_rekening` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `saldo` bigint NOT NULL DEFAULT '0',
  `saldo_blokir` bigint NOT NULL DEFAULT '0',
  `setoran_pokok` bigint NOT NULL DEFAULT '0',
  `tanggal_buka` date NOT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `tanggal_tutup` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `simpanan_nomor_rekening_unique` (`nomor_rekening`),
  KEY `simpanan_cabang_id_foreign` (`cabang_id`),
  KEY `simpanan_anggota_id_foreign` (`anggota_id`),
  KEY `simpanan_produk_id_foreign` (`produk_id`),
  KEY `simpanan_tenant_id_anggota_id_index` (`tenant_id`,`anggota_id`),
  KEY `simpanan_tenant_id_status_index` (`tenant_id`,`status`),
  CONSTRAINT `simpanan_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  CONSTRAINT `simpanan_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `simpanan_produk_id_foreign` FOREIGN KEY (`produk_id`) REFERENCES `produk_simpanan` (`id`),
  CONSTRAINT `simpanan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `simpanan_blokir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `simpanan_blokir` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `simpanan_id` bigint unsigned NOT NULL,
  `jumlah` bigint NOT NULL,
  `alasan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `referensi_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi_id` bigint unsigned DEFAULT NULL,
  `tanggal_blokir` date NOT NULL,
  `tanggal_lepas` date DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `simpanan_blokir_tenant_id_foreign` (`tenant_id`),
  KEY `simpanan_blokir_simpanan_id_foreign` (`simpanan_id`),
  CONSTRAINT `simpanan_blokir_simpanan_id_foreign` FOREIGN KEY (`simpanan_id`) REFERENCES `simpanan` (`id`),
  CONSTRAINT `simpanan_blokir_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `simpanan_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `simpanan_transaksi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `simpanan_id` bigint unsigned NOT NULL,
  `nomor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` bigint NOT NULL,
  `saldo_sebelum` bigint NOT NULL,
  `saldo_sesudah` bigint NOT NULL,
  `kas_id` bigint unsigned DEFAULT NULL,
  `metode_bayar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `simpanan_transaksi_nomor_unique` (`nomor`),
  KEY `simpanan_transaksi_simpanan_id_foreign` (`simpanan_id`),
  KEY `simpanan_transaksi_kas_id_foreign` (`kas_id`),
  KEY `simpanan_transaksi_jurnal_id_foreign` (`jurnal_id`),
  KEY `simpanan_transaksi_user_id_foreign` (`user_id`),
  KEY `simpanan_transaksi_tenant_id_tanggal_index` (`tenant_id`,`tanggal`),
  CONSTRAINT `simpanan_transaksi_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `simpanan_transaksi_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `simpanan_transaksi_simpanan_id_foreign` FOREIGN KEY (`simpanan_id`) REFERENCES `simpanan` (`id`),
  CONSTRAINT `simpanan_transaksi_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `simpanan_transaksi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=45002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `tagihan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `tagihan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `master_id` bigint unsigned NOT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `nomor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` date NOT NULL,
  `jatuh_tempo` date NOT NULL,
  `nominal` bigint NOT NULL,
  `terbayar` bigint NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar',
  `tanggal_bayar` date DEFAULT NULL,
  `kas_id` bigint unsigned DEFAULT NULL,
  `simpanan_id` bigint unsigned DEFAULT NULL,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tagihan_nomor_unique` (`nomor`),
  KEY `tagihan_master_id_foreign` (`master_id`),
  KEY `tagihan_anggota_id_foreign` (`anggota_id`),
  KEY `tagihan_kas_id_foreign` (`kas_id`),
  KEY `tagihan_simpanan_id_foreign` (`simpanan_id`),
  KEY `tagihan_jurnal_id_foreign` (`jurnal_id`),
  KEY `tagihan_tenant_id_status_index` (`tenant_id`,`status`),
  CONSTRAINT `tagihan_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  CONSTRAINT `tagihan_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tagihan_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `tagihan_master_id_foreign` FOREIGN KEY (`master_id`) REFERENCES `tagihan_master` (`id`),
  CONSTRAINT `tagihan_simpanan_id_foreign` FOREIGN KEY (`simpanan_id`) REFERENCES `simpanan` (`id`),
  CONSTRAINT `tagihan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `tagihan_master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `tagihan_master` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` bigint NOT NULL,
  `siklus` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bulanan',
  `coa_id` bigint unsigned DEFAULT NULL,
  `auto_potong_simpanan` tinyint(1) NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tagihan_master_tenant_id_foreign` (`tenant_id`),
  KEY `tagihan_master_coa_id_foreign` (`coa_id`),
  CONSTRAINT `tagihan_master_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `tagihan_master_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `badan_hukum` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik_koperasi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `npwp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `akta_pendirian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operation_mode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'konvensional',
  `mata_uang` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'IDR',
  `tahun_buku` int NOT NULL DEFAULT '2026',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `subscription_until` date DEFAULT NULL,
  `plan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'basic',
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_barang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_barang` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `sku` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori_id` bigint unsigned DEFAULT NULL,
  `satuan_id` bigint unsigned DEFAULT NULL,
  `brand` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `harga_beli` bigint NOT NULL DEFAULT '0',
  `harga_jual_umum` bigint NOT NULL DEFAULT '0',
  `harga_jual_anggota` bigint NOT NULL DEFAULT '0',
  `harga_jual_grosir` bigint NOT NULL DEFAULT '0',
  `stok` int NOT NULL DEFAULT '0',
  `stok_minimum` int NOT NULL DEFAULT '0',
  `stok_maksimum` int NOT NULL DEFAULT '0',
  `foto_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metode_hpp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'average',
  `is_jasa` tinyint(1) NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `meta` json DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `toko_barang_tenant_id_sku_unique` (`tenant_id`,`sku`),
  KEY `toko_barang_kategori_id_foreign` (`kategori_id`),
  KEY `toko_barang_satuan_id_foreign` (`satuan_id`),
  KEY `toko_barang_barcode_index` (`barcode`),
  CONSTRAINT `toko_barang_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `toko_kategori` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_barang_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `toko_satuan` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_barang_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_barang_satuan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_barang_satuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `satuan_id` bigint unsigned NOT NULL,
  `konversi` int NOT NULL,
  `harga_jual_umum` bigint NOT NULL DEFAULT '0',
  `harga_jual_anggota` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `toko_barang_satuan_tenant_id_foreign` (`tenant_id`),
  KEY `toko_barang_satuan_barang_id_foreign` (`barang_id`),
  KEY `toko_barang_satuan_satuan_id_foreign` (`satuan_id`),
  CONSTRAINT `toko_barang_satuan_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `toko_barang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `toko_barang_satuan_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `toko_satuan` (`id`),
  CONSTRAINT `toko_barang_satuan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_kategori` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `toko_kategori_tenant_id_foreign` (`tenant_id`),
  KEY `toko_kategori_parent_id_foreign` (`parent_id`),
  CONSTRAINT `toko_kategori_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `toko_kategori` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_kategori_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_pembelian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_pembelian` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `nomor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `subtotal` bigint NOT NULL,
  `diskon` bigint NOT NULL DEFAULT '0',
  `pajak` bigint NOT NULL DEFAULT '0',
  `biaya_lain` bigint NOT NULL DEFAULT '0',
  `total` bigint NOT NULL,
  `terbayar` bigint NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `toko_pembelian_nomor_unique` (`nomor`),
  KEY `toko_pembelian_tenant_id_foreign` (`tenant_id`),
  KEY `toko_pembelian_cabang_id_foreign` (`cabang_id`),
  KEY `toko_pembelian_supplier_id_foreign` (`supplier_id`),
  KEY `toko_pembelian_jurnal_id_foreign` (`jurnal_id`),
  KEY `toko_pembelian_user_id_foreign` (`user_id`),
  CONSTRAINT `toko_pembelian_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_pembelian_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_pembelian_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `toko_supplier` (`id`),
  CONSTRAINT `toko_pembelian_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `toko_pembelian_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_pembelian_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_pembelian_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `pembelian_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` bigint NOT NULL,
  `diskon` bigint NOT NULL DEFAULT '0',
  `subtotal` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `toko_pembelian_detail_tenant_id_foreign` (`tenant_id`),
  KEY `toko_pembelian_detail_pembelian_id_foreign` (`pembelian_id`),
  KEY `toko_pembelian_detail_barang_id_foreign` (`barang_id`),
  CONSTRAINT `toko_pembelian_detail_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `toko_barang` (`id`),
  CONSTRAINT `toko_pembelian_detail_pembelian_id_foreign` FOREIGN KEY (`pembelian_id`) REFERENCES `toko_pembelian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `toko_pembelian_detail_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_penjualan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_penjualan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `anggota_id` bigint unsigned DEFAULT NULL,
  `nomor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `subtotal` bigint NOT NULL,
  `diskon` bigint NOT NULL DEFAULT '0',
  `pajak` bigint NOT NULL DEFAULT '0',
  `total` bigint NOT NULL,
  `bayar` bigint NOT NULL DEFAULT '0',
  `kembali` bigint NOT NULL DEFAULT '0',
  `metode_bayar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `kas_id` bigint unsigned DEFAULT NULL,
  `simpanan_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lunas',
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `toko_penjualan_nomor_unique` (`nomor`),
  KEY `toko_penjualan_cabang_id_foreign` (`cabang_id`),
  KEY `toko_penjualan_anggota_id_foreign` (`anggota_id`),
  KEY `toko_penjualan_kas_id_foreign` (`kas_id`),
  KEY `toko_penjualan_simpanan_id_foreign` (`simpanan_id`),
  KEY `toko_penjualan_jurnal_id_foreign` (`jurnal_id`),
  KEY `toko_penjualan_user_id_foreign` (`user_id`),
  KEY `toko_penjualan_tenant_id_tanggal_index` (`tenant_id`,`tanggal`),
  CONSTRAINT `toko_penjualan_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_penjualan_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_penjualan_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_penjualan_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `toko_penjualan_simpanan_id_foreign` FOREIGN KEY (`simpanan_id`) REFERENCES `simpanan` (`id`),
  CONSTRAINT `toko_penjualan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `toko_penjualan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_penjualan_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_penjualan_detail` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `penjualan_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` bigint NOT NULL,
  `diskon` bigint NOT NULL DEFAULT '0',
  `subtotal` bigint NOT NULL,
  `hpp` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `toko_penjualan_detail_tenant_id_foreign` (`tenant_id`),
  KEY `toko_penjualan_detail_penjualan_id_foreign` (`penjualan_id`),
  KEY `toko_penjualan_detail_barang_id_foreign` (`barang_id`),
  CONSTRAINT `toko_penjualan_detail_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `toko_barang` (`id`),
  CONSTRAINT `toko_penjualan_detail_penjualan_id_foreign` FOREIGN KEY (`penjualan_id`) REFERENCES `toko_penjualan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `toko_penjualan_detail_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_satuan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_satuan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `toko_satuan_tenant_id_kode_unique` (`tenant_id`,`kode`),
  CONSTRAINT `toko_satuan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_stok_mutasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_stok_mutasi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `barang_id` bigint unsigned NOT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `tanggal` date NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` bigint NOT NULL DEFAULT '0',
  `stok_sebelum` int NOT NULL,
  `stok_sesudah` int NOT NULL,
  `referensi_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referensi_id` bigint unsigned DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `toko_stok_mutasi_cabang_id_foreign` (`cabang_id`),
  KEY `toko_stok_mutasi_user_id_foreign` (`user_id`),
  KEY `toko_stok_mutasi_tenant_id_tanggal_index` (`tenant_id`,`tanggal`),
  KEY `toko_stok_mutasi_barang_id_tanggal_index` (`barang_id`,`tanggal`),
  CONSTRAINT `toko_stok_mutasi_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `toko_barang` (`id`),
  CONSTRAINT `toko_stok_mutasi_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `toko_stok_mutasi_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `toko_stok_mutasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `toko_supplier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `toko_supplier` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `npwp` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `saldo_hutang` bigint NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `toko_supplier_tenant_id_kode_unique` (`tenant_id`,`kode`),
  CONSTRAINT `toko_supplier_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `unit_jasa_layanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `unit_jasa_layanan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tarif` bigint NOT NULL DEFAULT '0',
  `satuan_tarif` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_jasa_layanan_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `unit_jasa_layanan_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `unit_jasa_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `unit_jasa_order` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `layanan_id` bigint unsigned NOT NULL,
  `anggota_id` bigint unsigned DEFAULT NULL,
  `nomor` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `nama_pelanggan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` bigint NOT NULL,
  `komisi_anggota` bigint NOT NULL DEFAULT '0',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'booking',
  `bayar` bigint NOT NULL DEFAULT '0',
  `kas_id` bigint unsigned DEFAULT NULL,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unit_jasa_order_nomor_unique` (`nomor`),
  KEY `unit_jasa_order_tenant_id_foreign` (`tenant_id`),
  KEY `unit_jasa_order_layanan_id_foreign` (`layanan_id`),
  KEY `unit_jasa_order_anggota_id_foreign` (`anggota_id`),
  KEY `unit_jasa_order_kas_id_foreign` (`kas_id`),
  KEY `unit_jasa_order_jurnal_id_foreign` (`jurnal_id`),
  CONSTRAINT `unit_jasa_order_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`) ON DELETE SET NULL,
  CONSTRAINT `unit_jasa_order_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `unit_jasa_order_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `unit_jasa_order_layanan_id_foreign` FOREIGN KEY (`layanan_id`) REFERENCES `unit_jasa_layanan` (`id`),
  CONSTRAINT `unit_jasa_order_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `unit_produsen_komoditi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `unit_produsen_komoditi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `harga_beli_default` bigint NOT NULL DEFAULT '0',
  `harga_jual_default` bigint NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_produsen_komoditi_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `unit_produsen_komoditi_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `unit_produsen_setoran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `unit_produsen_setoran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `anggota_id` bigint unsigned NOT NULL,
  `komoditi_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah` decimal(14,3) NOT NULL,
  `harga_satuan` bigint NOT NULL,
  `total` bigint NOT NULL,
  `kualitas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `terbayar` bigint NOT NULL DEFAULT '0',
  `kas_id` bigint unsigned DEFAULT NULL,
  `jurnal_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_produsen_setoran_tenant_id_foreign` (`tenant_id`),
  KEY `unit_produsen_setoran_anggota_id_foreign` (`anggota_id`),
  KEY `unit_produsen_setoran_komoditi_id_foreign` (`komoditi_id`),
  KEY `unit_produsen_setoran_kas_id_foreign` (`kas_id`),
  KEY `unit_produsen_setoran_jurnal_id_foreign` (`jurnal_id`),
  KEY `unit_produsen_setoran_user_id_foreign` (`user_id`),
  CONSTRAINT `unit_produsen_setoran_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggota` (`id`),
  CONSTRAINT `unit_produsen_setoran_jurnal_id_foreign` FOREIGN KEY (`jurnal_id`) REFERENCES `jurnal` (`id`) ON DELETE SET NULL,
  CONSTRAINT `unit_produsen_setoran_kas_id_foreign` FOREIGN KEY (`kas_id`) REFERENCES `kas` (`id`),
  CONSTRAINT `unit_produsen_setoran_komoditi_id_foreign` FOREIGN KEY (`komoditi_id`) REFERENCES `unit_produsen_komoditi` (`id`),
  CONSTRAINT `unit_produsen_setoran_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unit_produsen_setoran_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned DEFAULT NULL,
  `cabang_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `telp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `two_factor_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_tenant_id_foreign` (`tenant_id`),
  KEY `users_cabang_id_foreign` (`cabang_id`),
  CONSTRAINT `users_cabang_id_foreign` FOREIGN KEY (`cabang_id`) REFERENCES `cabang` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;


-- ============================================================
-- DEFAULT TENANT
-- ============================================================
INSERT INTO `tenants` VALUES (1,'Koperasi Default',NULL,NULL,NULL,NULL,NULL,'Jl. Contoh No. 1, Jakarta','021-1234567','admin@koperasi.local',NULL,'dual','IDR',2026,'aktif',NULL,'enterprise',NULL,NULL,'2026-04-27 11:43:32','2026-04-27 11:43:32');

-- ============================================================
-- ADMIN USER (Password: admin123)
-- ============================================================
INSERT INTO `users` VALUES (1,1,NULL,'Super Admin','ADM001','admin@koperasi.local',NULL,NULL,1,NULL,'$2y$12$3z7tlUNkfgk2aAoifrEu/.M4QYi6lrX70cHD1TgN4pyKq50sWTYti',NULL,'2026-04-27 11:43:33','2026-04-27 11:43:33',NULL,NULL,NULL,NULL,NULL);

-- ============================================================
-- ROLES & PERMISSIONS
-- ============================================================
INSERT INTO `roles` VALUES (1,'super-admin','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(2,'admin','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(3,'manajer','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(4,'kasir','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(5,'ao','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(6,'kolektor','web','2026-04-27 11:43:33','2026-04-27 11:43:33'),(7,'pengawas','web','2026-04-27 11:43:33','2026-04-27 11:43:33'),(8,'akuntan','web','2026-04-27 11:43:33','2026-04-27 11:43:33'),(9,'anggota','web','2026-04-27 11:43:33','2026-04-27 11:43:33');
INSERT INTO `permissions` VALUES (1,'anggota.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(2,'anggota.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(3,'anggota.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(4,'anggota.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(5,'anggota.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(6,'anggota.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(7,'anggota.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(8,'simpanan.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(9,'simpanan.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(10,'simpanan.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(11,'simpanan.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(12,'simpanan.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(13,'simpanan.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(14,'simpanan.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(15,'pinjaman.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(16,'pinjaman.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(17,'pinjaman.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(18,'pinjaman.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(19,'pinjaman.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(20,'pinjaman.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(21,'pinjaman.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(22,'kas.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(23,'kas.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(24,'kas.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(25,'kas.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(26,'kas.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(27,'kas.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(28,'kas.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(29,'bank.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(30,'bank.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(31,'bank.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(32,'bank.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(33,'bank.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(34,'bank.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(35,'bank.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(36,'jurnal.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(37,'jurnal.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(38,'jurnal.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(39,'jurnal.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(40,'jurnal.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(41,'jurnal.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(42,'jurnal.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(43,'coa.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(44,'coa.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(45,'coa.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(46,'coa.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(47,'coa.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(48,'coa.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(49,'coa.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(50,'shu.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(51,'shu.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(52,'shu.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(53,'shu.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(54,'shu.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(55,'shu.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(56,'shu.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(57,'pos.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(58,'pos.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(59,'pos.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(60,'pos.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(61,'pos.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(62,'pos.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(63,'pos.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(64,'produsen.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(65,'produsen.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(66,'produsen.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(67,'produsen.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(68,'produsen.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(69,'produsen.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(70,'produsen.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(71,'jasa.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(72,'jasa.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(73,'jasa.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(74,'jasa.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(75,'jasa.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(76,'jasa.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(77,'jasa.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(78,'tagihan.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(79,'tagihan.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(80,'tagihan.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(81,'tagihan.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(82,'tagihan.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(83,'tagihan.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(84,'tagihan.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(85,'asuransi.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(86,'asuransi.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(87,'asuransi.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(88,'asuransi.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(89,'asuransi.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(90,'asuransi.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(91,'asuransi.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(92,'karyawan.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(93,'karyawan.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(94,'karyawan.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(95,'karyawan.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(96,'karyawan.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(97,'karyawan.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(98,'karyawan.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(99,'gaji.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(100,'gaji.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(101,'gaji.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(102,'gaji.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(103,'gaji.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(104,'gaji.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(105,'gaji.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(106,'asset.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(107,'asset.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(108,'asset.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(109,'asset.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(110,'asset.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(111,'asset.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(112,'asset.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(113,'rat.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(114,'rat.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(115,'rat.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(116,'rat.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(117,'rat.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(118,'rat.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(119,'rat.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(120,'laporan.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(121,'laporan.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(122,'laporan.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(123,'laporan.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(124,'laporan.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(125,'laporan.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(126,'laporan.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(127,'setting.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(128,'setting.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(129,'setting.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(130,'setting.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(131,'setting.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(132,'setting.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(133,'setting.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(134,'user.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(135,'user.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(136,'user.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(137,'user.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(138,'user.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(139,'user.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(140,'user.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(141,'role.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(142,'role.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(143,'role.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(144,'role.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(145,'role.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(146,'role.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(147,'role.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(148,'tenant.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(149,'tenant.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(150,'tenant.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(151,'tenant.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(152,'tenant.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(153,'tenant.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(154,'tenant.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(155,'license.view','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(156,'license.create','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(157,'license.update','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(158,'license.delete','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(159,'license.approve','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(160,'license.export','web','2026-04-27 11:43:32','2026-04-27 11:43:32'),(161,'license.print','web','2026-04-27 11:43:32','2026-04-27 11:43:32');
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(25,1),(26,1),(27,1),(28,1),(29,1),(30,1),(31,1),(32,1),(33,1),(34,1),(35,1),(36,1),(37,1),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,1),(56,1),(57,1),(58,1),(59,1),(60,1),(61,1),(62,1),(63,1),(64,1),(65,1),(66,1),(67,1),(68,1),(69,1),(70,1),(71,1),(72,1),(73,1),(74,1),(75,1),(76,1),(77,1),(78,1),(79,1),(80,1),(81,1),(82,1),(83,1),(84,1),(85,1),(86,1),(87,1),(88,1),(89,1),(90,1),(91,1),(92,1),(93,1),(94,1),(95,1),(96,1),(97,1),(98,1),(99,1),(100,1),(101,1),(102,1),(103,1),(104,1),(105,1),(106,1),(107,1),(108,1),(109,1),(110,1),(111,1),(112,1),(113,1),(114,1),(115,1),(116,1),(117,1),(118,1),(119,1),(120,1),(121,1),(122,1),(123,1),(124,1),(125,1),(126,1),(127,1),(128,1),(129,1),(130,1),(131,1),(132,1),(133,1),(134,1),(135,1),(136,1),(137,1),(138,1),(139,1),(140,1),(141,1),(142,1),(143,1),(144,1),(145,1),(146,1),(147,1),(148,1),(149,1),(150,1),(151,1),(152,1),(153,1),(154,1),(155,1),(156,1),(157,1),(158,1),(159,1),(160,1),(161,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(13,2),(14,2),(15,2),(16,2),(17,2),(18,2),(19,2),(20,2),(21,2),(22,2),(23,2),(24,2),(25,2),(26,2),(27,2),(28,2),(29,2),(30,2),(31,2),(32,2),(33,2),(34,2),(35,2),(36,2),(37,2),(38,2),(39,2),(40,2),(41,2),(42,2),(43,2),(44,2),(45,2),(46,2),(47,2),(48,2),(49,2),(50,2),(51,2),(52,2),(53,2),(54,2),(55,2),(56,2),(57,2),(58,2),(59,2),(60,2),(61,2),(62,2),(63,2),(64,2),(65,2),(66,2),(67,2),(68,2),(69,2),(70,2),(71,2),(72,2),(73,2),(74,2),(75,2),(76,2),(77,2),(78,2),(79,2),(80,2),(81,2),(82,2),(83,2),(84,2),(85,2),(86,2),(87,2),(88,2),(89,2),(90,2),(91,2),(92,2),(93,2),(94,2),(95,2),(96,2),(97,2),(98,2),(99,2),(100,2),(101,2),(102,2),(103,2),(104,2),(105,2),(106,2),(107,2),(108,2),(109,2),(110,2),(111,2),(112,2),(113,2),(114,2),(115,2),(116,2),(117,2),(118,2),(119,2),(120,2),(121,2),(122,2),(123,2),(124,2),(125,2),(126,2),(127,2),(128,2),(129,2),(130,2),(131,2),(132,2),(133,2),(134,2),(135,2),(136,2),(137,2),(138,2),(139,2),(140,2),(141,2),(142,2),(143,2),(144,2),(145,2),(146,2),(147,2),(1,3),(5,3),(6,3),(7,3),(8,3),(12,3),(13,3),(14,3),(15,3),(17,3),(19,3),(20,3),(21,3),(22,3),(26,3),(27,3),(28,3),(29,3),(33,3),(34,3),(35,3),(36,3),(40,3),(41,3),(42,3),(43,3),(47,3),(48,3),(49,3),(50,3),(54,3),(55,3),(56,3),(57,3),(61,3),(62,3),(63,3),(64,3),(68,3),(69,3),(70,3),(71,3),(75,3),(76,3),(77,3),(78,3),(82,3),(83,3),(84,3),(85,3),(89,3),(90,3),(91,3),(92,3),(96,3),(97,3),(98,3),(99,3),(103,3),(104,3),(105,3),(106,3),(110,3),(111,3),(112,3),(113,3),(117,3),(118,3),(119,3),(120,3),(124,3),(125,3),(126,3),(127,3),(131,3),(132,3),(133,3),(134,3),(138,3),(139,3),(140,3),(141,3),(145,3),(146,3),(147,3),(148,3),(152,3),(153,3),(154,3),(155,3),(159,3),(160,3),(161,3),(1,4),(8,4),(9,4),(10,4),(11,4),(12,4),(13,4),(14,4),(15,4),(17,4),(22,4),(23,4),(24,4),(25,4),(26,4),(27,4),(28,4),(57,4),(58,4),(59,4),(60,4),(61,4),(62,4),(63,4),(78,4),(79,4),(80,4),(81,4),(82,4),(83,4),(84,4),(1,5),(8,5),(15,5),(16,5),(17,5),(18,5),(19,5),(20,5),(21,5),(36,5),(1,6),(15,6),(17,6),(1,7),(8,7),(15,7),(22,7),(29,7),(36,7),(43,7),(50,7),(57,7),(64,7),(71,7),(78,7),(85,7),(92,7),(99,7),(106,7),(113,7),(120,7),(121,7),(122,7),(123,7),(124,7),(125,7),(126,7),(127,7),(134,7),(141,7),(148,7),(155,7),(36,8),(37,8),(38,8),(39,8),(40,8),(41,8),(42,8),(43,8),(44,8),(45,8),(46,8),(47,8),(48,8),(49,8),(120,8),(121,8),(122,8),(123,8),(124,8),(125,8),(126,8),(127,8);
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1);

SET FOREIGN_KEY_CHECKS=1;
-- ============================================================
-- Install selesai. Selamat menggunakan Koperasi App!
-- ============================================================
