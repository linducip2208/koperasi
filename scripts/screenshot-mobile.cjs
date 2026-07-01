/**
 * Mobile screenshot capture for KoperasiApp.
 *
 * Captures key pages at iPhone 11 Pro Max viewport (414x896).
 * Saves PNGs to public/marketing/screens-mobile/.
 *
 * Usage:
 *   node scripts/screenshot-mobile.cjs
 */

const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

const BASE = 'http://koperasi.test';
const OUT_DIR = path.join(__dirname, '..', 'public', 'marketing', 'screens-mobile');

const EMAIL = 'admin@koperasi.local';
const PASSWORD = 'admin123';

const pages = [
    { url: '/admin',              file: 'mobile-01-dashboard.png' },
    { url: '/admin/anggotas',     file: 'mobile-02-anggota-list.png' },
    { url: '/admin/pinjamen',     file: 'mobile-03-pinjaman-list.png' },
    { url: '/admin/toko-penjualans', file: 'mobile-04-pos.png' },
    { url: '/admin/laporan-keuangan', file: 'mobile-05-laporan.png' },
];

(async () => {
    if (!fs.existsSync(OUT_DIR)) {
        fs.mkdirSync(OUT_DIR, { recursive: true });
    }

    console.log('Launching mobile browser...');
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({
        viewport: { width: 414, height: 896 },
        deviceScaleFactor: 2,
        isMobile: true,
    });
    const page = await context.newPage();

    console.log(`Logging in as ${EMAIL}...`);
    await page.goto(`${BASE}/admin/login`, { waitUntil: 'networkidle' });
    await page.type('input[type="email"]', EMAIL, { delay: 50 });
    await page.type('input[type="password"]', PASSWORD, { delay: 50 });
    await page.waitForTimeout(500);
    await page.click('button[type="submit"]');
    await page.waitForTimeout(3000);

    const currentUrl = page.url();
    if (currentUrl.includes('/login')) {
        console.error(`Login FAILED. Current URL: ${currentUrl}`);
        await browser.close();
        process.exit(1);
    }
    console.log('Login OK.');

    for (const p of pages) {
        const outPath = path.join(OUT_DIR, p.file);
        console.log(`Capturing: ${p.url} → ${p.file}`);

        try {
            await page.goto(`${BASE}${p.url}`, {
                waitUntil: 'networkidle',
                timeout: 30_000,
            });
            await page.waitForTimeout(1500);
            await page.screenshot({ path: outPath, fullPage: true });
            console.log(`  ✓ ${p.file} saved`);
        } catch (err) {
            console.error(`  ✗ ${p.file} FAILED: ${err.message}`);
        }
    }

    console.log(`\nDone. ${pages.length} mobile screenshots saved to ${OUT_DIR}`);
    await browser.close();
})();
