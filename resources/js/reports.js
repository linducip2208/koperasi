import Chart from 'chart.js/auto';

/**
 * Auto-init Chart.js dari <canvas data-chart='{"type":"line",...}'>.
 * Data 100% dari server (Blade), tidak ada fetch/CDN.
 */
function initCharts(root = document) {
    root.querySelectorAll('canvas[data-chart]:not([data-chart-init])').forEach((el) => {
        el.dataset.chartInit = '1';
        try {
            const cfg = JSON.parse(el.dataset.chart);
            cfg.options = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
                ...(cfg.options || {}),
            };
            new Chart(el, cfg);
        } catch (e) {
            console.warn('chart init gagal', e);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => initCharts());
document.addEventListener('livewire:navigated', () => initCharts());
window.KopCharts = { init: initCharts };
