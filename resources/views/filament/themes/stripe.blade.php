<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script>
    (function() {
        try { localStorage.setItem('theme', 'light'); } catch(e) {}
        document.documentElement.classList.remove('dark');
        document.addEventListener('DOMContentLoaded', () => document.documentElement.classList.remove('dark'));
    })();
</script>
<style>
    :root {
        --kop-shadow-soft: 0 1px 2px rgba(15,23,42,0.04), 0 4px 12px rgba(15,23,42,0.04);
        --kop-shadow-card: 0 1px 3px rgba(15,23,42,0.04), 0 8px 24px -8px rgba(15,23,42,0.08);
    }

    body.fi-body {
        background: #f1f5f9;
        -webkit-font-smoothing: antialiased;
    }

    /* === SIDEBAR DARK NAVY (signature Stripe style) === */
    .fi-sidebar {
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
        border-color: rgba(15,23,42,1) !important;
    }
    .fi-sidebar-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255,255,255,0.06) !important;
    }
    .fi-sidebar-nav {
        background: transparent !important;
    }

    /* Brand text di sidebar */
    .fi-sidebar-header .fi-logo,
    .fi-sidebar-header * {
        color: #f8fafc !important;
    }

    /* Sidebar nav items — force semua text di sidebar putih/light */
    .fi-sidebar-item-button,
    .fi-sidebar-item-button *,
    .fi-sidebar-item-label,
    .fi-sidebar-item .fi-sidebar-item-label {
        color: #cbd5e1 !important;
    }
    .fi-sidebar-item-button {
        border-radius: 0.625rem !important;
        margin: 2px 8px !important;
        padding: 0.5rem 0.75rem !important;
        font-weight: 500 !important;
        transition: all 0.15s ease !important;
    }
    .fi-sidebar-item-button:hover,
    .fi-sidebar-item-button:hover *,
    .fi-sidebar-item-button:hover .fi-sidebar-item-label {
        background: rgba(255,255,255,0.05);
        color: #f8fafc !important;
    }
    .fi-sidebar-item-button:hover {
        background: rgba(255,255,255,0.05) !important;
    }
    .fi-sidebar-item-icon,
    .fi-sidebar-item-button svg {
        color: #94a3b8 !important;
    }
    .fi-sidebar-item-button:hover .fi-sidebar-item-icon,
    .fi-sidebar-item-button:hover svg {
        color: #f8fafc !important;
    }

    /* Active item — emerald gradient */
    .fi-sidebar-item-active .fi-sidebar-item-button,
    .fi-sidebar-item-active .fi-sidebar-item-button *,
    .fi-sidebar-item-active .fi-sidebar-item-label {
        color: #ffffff !important;
    }
    .fi-sidebar-item-active .fi-sidebar-item-button {
        background: linear-gradient(135deg, #059669, #0d9488) !important;
        box-shadow: 0 4px 12px -4px rgba(5,150,105,0.5) !important;
        font-weight: 600 !important;
    }
    .fi-sidebar-item-active .fi-sidebar-item-icon,
    .fi-sidebar-item-active .fi-sidebar-item-button svg {
        color: #ffffff !important;
    }

    /* Group button (collapsible header) — text putih juga */
    .fi-sidebar-group-button,
    .fi-sidebar-group-button *,
    .fi-sidebar-group-label {
        color: #94a3b8 !important;
    }
    .fi-sidebar-group-button:hover,
    .fi-sidebar-group-button:hover * {
        color: #e2e8f0 !important;
    }

    /* Group labels */
    .fi-sidebar-group-label {
        color: #64748b !important;
        font-size: 10px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.12em !important;
        padding: 1.25rem 1rem 0.5rem !important;
    }

    /* Group collapse button */
    .fi-sidebar-group-collapse-button {
        color: #64748b !important;
    }
    .fi-sidebar-group-collapse-button:hover {
        color: #cbd5e1 !important;
    }

    /* === TOPBAR === */
    .fi-topbar {
        background: rgba(255,255,255,0.85) !important;
        backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(226, 232, 240, 1) !important;
        box-shadow: 0 1px 0 rgba(15,23,42,0.04);
    }

    /* === CONTENT === */
    .fi-main {
        background: #f1f5f9 !important;
    }

    .fi-header-heading {
        font-weight: 800 !important;
        letter-spacing: -0.025em !important;
        color: #0f172a !important;
    }
    .fi-header-subheading {
        color: #64748b !important;
    }

    /* Sections / cards */
    .fi-section {
        border-radius: 0.875rem !important;
        border-color: rgba(226, 232, 240, 1) !important;
        background: #ffffff !important;
        box-shadow: var(--kop-shadow-soft) !important;
    }

    /* Tables */
    .fi-ta {
        border-radius: 0.875rem !important;
        box-shadow: var(--kop-shadow-card) !important;
        border-color: rgba(226, 232, 240, 1) !important;
        overflow: hidden;
    }
    .fi-ta-header {
        background: #f8fafc !important;
        border-bottom: 1px solid rgba(226, 232, 240, 1) !important;
    }
    .fi-ta-row:hover {
        background: rgba(16, 185, 129, 0.04) !important;
    }

    /* Primary buttons — emerald gradient */
    .fi-btn-color-primary {
        background: linear-gradient(135deg, #059669, #0d9488) !important;
        box-shadow: 0 4px 12px -4px rgba(5,150,105,0.4) !important;
        font-weight: 600 !important;
    }
    .fi-btn-color-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px -4px rgba(5,150,105,0.55) !important;
    }

    /* Inputs */
    .fi-input, .fi-select-input, .fi-textarea-input {
        border-radius: 0.625rem !important;
    }
    .fi-input:focus, .fi-select-input:focus, .fi-textarea-input:focus {
        box-shadow: 0 0 0 3px rgba(16,185,129,0.15) !important;
        border-color: #10b981 !important;
    }

    /* Badges */
    .fi-badge {
        border-radius: 0.4rem !important;
        font-weight: 600 !important;
    }

    /* Tabs */
    .fi-tabs-item-active {
        color: #047857 !important;
        border-color: #10b981 !important;
    }

    /* Scrollbar */
    *::-webkit-scrollbar { width: 8px; height: 8px; }
    *::-webkit-scrollbar-thumb { background: rgb(203 213 225); border-radius: 4px; }
    *::-webkit-scrollbar-thumb:hover { background: rgb(148 163 184); }

    /* Sidebar scrollbar */
    .fi-sidebar *::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
    .fi-sidebar *::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
</style>
