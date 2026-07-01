/**
 * Screenshot capture for KoperasiApp marketing & docs.
 *
 * Logs in once as super-admin, then captures all key Filament admin pages at
 * 1440x900 viewport. Saves PNGs to public/marketing/screens/.
 *
 * Usage:
 *   node scripts/screenshot.cjs
 */

const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE = 'http://koperasi.test';
const OUT_DIR = path.join(__dirname, '..', 'public', 'marketing', 'screens');

const EMAIL = 'admin@koperasi.local';
const PASSWORD = 'admin123';

const pages = [
    // Public
    { url: '/',                     file: '00-landing.png',          auth: false },
    { url: '/docs',                 file: '00-docs.png',             auth: false },
    { url: '/admin/login',          file: '01-login.png',            auth: false },

    // Dashboard
    { url: '/admin',                file: '02-dashboard.png',        auth: true },

    // Keanggotaan
    { url: '/admin/anggotas',       file: '03-anggota-list.png',     auth: true },

    // Simpan Pinjam
    { url: '/admin/produk-simpanans',  file: '04-produk-simpanan.png',  auth: true },
    { url: '/admin/simpanans',         file: '05-simpanan.png',          auth: true },
    { url: '/admin/produk-pinjamen',   file: '06-produk-pinjaman.png',   auth: true },
    { url: '/admin/pinjamen',          file: '07-pinjaman.png',          auth: true },
    { url: '/admin/tagihans',          file: '08-tagihan.png',           auth: true },

    // Toko & Unit Usaha
    { url: '/admin/toko-barangs',      file: '09-toko-barang.png',       auth: true },
    { url: '/admin/toko-penjualans',   file: '10-toko-penjualan.png',    auth: true },

    // Akuntansi
    { url: '/admin/coas',              file: '11-coa.png',               auth: true },
    { url: '/admin/jurnals',           file: '12-jurnal.png',            auth: true },
    { url: '/admin/kas',               file: '13-kas.png',              auth: true },

    // SHU & RAT
    { url: '/admin/shu-perhitungans',  file: '14-shu.png',              auth: true },

    // HR & Asset
    { url: '/admin/karyawans',         file: '15-karyawan.png',         auth: true },

    // Laporan
    { url: '/admin/laporan-keuangan',  file: '16-laporan-keuangan.png', auth: true },

    // Pengaturan
    { url: '/admin/users',             file: '17-users.png',            auth: true },
];

(async () => {
    if (!fs.existsSync(OUT_DIR)) {
        fs.mkdirSync(OUT_DIR, { recursive: true });
    }

    console.log('Launching browser...');
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 1440, height: 900 },
        deviceScaleFactor: 1.5,
    });
    const page = await context.newPage();

    // Login once for all auth-required pages
    console.log(`Logging in as ${EMAIL}...`);
    await page.goto(`${BASE}/admin/login`, { waitUntil: 'load', timeout: 60000 });
    await page.type('input[type="email"]', EMAIL, { delay: 50 });
    await page.type('input[type="password"]', PASSWORD, { delay: 50 });
    await page.waitForTimeout(500);
    await page.click('button[type="submit"]');
    await page.waitForTimeout(3000);

    // Verify login
    const currentUrl = page.url();
    if (currentUrl.includes('/login')) {
        console.error(`Login FAILED. Current URL: ${currentUrl}`);
        await browser.close();
        process.exit(1);
    }
    console.log('Login OK.');

    // Capture each page
    for (const p of pages) {
        const outPath = path.join(OUT_DIR, p.file);
        console.log(`Capturing: ${p.url} → ${p.file}`);

        try {
            await page.goto(`${BASE}${p.url}`, {
                waitUntil: 'networkidle',
                timeout: 30_000,
            });
            await page.waitForTimeout(1500);
            await page.screenshot({ path: outPath, fullPage: false });
            console.log(`  ✓ ${p.file} saved`);
        } catch (err) {
            console.error(`  ✗ ${p.file} FAILED: ${err.message}`);
        }
    }

    console.log(`\nDone. ${pages.length} screenshots saved to ${OUT_DIR}`);
    await browser.close();
})();
