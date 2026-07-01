<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script>
    /* Force Filament dark mode untuk tema dark — pakai semua CSS var dark Filament */
    (function() {
        try { localStorage.setItem('theme', 'dark'); } catch(e) {}
        document.documentElement.classList.add('dark');
        document.addEventListener('DOMContentLoaded', () => document.documentElement.classList.add('dark'));
    })();
</script>
<style>
    /* === FORCE FULL DARK MODE === */
    body.fi-body {
        background: #0a0a0f !important;
        color: #e4e4e7 !important;
        font-family: 'Inter', sans-serif !important;
        -webkit-font-smoothing: antialiased;
    }

    /* === SIDEBAR === */
    .fi-sidebar {
        background: #111118 !important;
        border-right: 1px solid rgba(255,255,255,0.05) !important;
    }
    .fi-sidebar-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
    }
    .fi-sidebar-header * {
        color: #f4f4f5 !important;
    }

    /* Sidebar nav items */
    .fi-sidebar-item-button {
        border-radius: 0.5rem !important;
        margin: 1px 8px !important;
        padding: 0.5rem 0.75rem !important;
        color: #a1a1aa !important;
        font-weight: 500 !important;
        font-size: 13.5px !important;
        transition: all 0.12s !important;
    }
    .fi-sidebar-item-button:hover {
        background: rgba(255,255,255,0.04) !important;
        color: #f4f4f5 !important;
    }
    .fi-sidebar-item-icon {
        color: #71717a !important;
    }
    .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
        color: #d4d4d8 !important;
    }

    /* Active — violet/indigo accent */
    .fi-sidebar-item-active .fi-sidebar-item-button {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.18), rgba(99, 102, 241, 0.12)) !important;
        color: #c4b5fd !important;
        position: relative;
    }
    .fi-sidebar-item-active .fi-sidebar-item-button::before {
        content: ""; position: absolute;
        left: -8px; top: 20%; bottom: 20%; width: 3px;
        background: linear-gradient(180deg, #8b5cf6, #6366f1);
        border-radius: 0 3px 3px 0;
        box-shadow: 0 0 12px rgba(139, 92, 246, 0.6);
    }
    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #a78bfa !important;
    }

    /* Group labels */
    .fi-sidebar-group-label {
        color: #52525b !important;
        font-size: 10.5px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        padding: 1.25rem 1rem 0.5rem !important;
    }

    /* === TOPBAR === */
    .fi-topbar {
        background: rgba(17,17,24,0.85) !important;
        backdrop-filter: blur(16px);
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
    }
    .fi-topbar * {
        color: #e4e4e7 !important;
    }

    /* === MAIN CONTENT === */
    .fi-main {
        background: #0a0a0f !important;
    }
    .fi-page {
        color: #e4e4e7 !important;
    }

    .fi-header-heading {
        color: #fafafa !important;
        font-weight: 700 !important;
        letter-spacing: -0.025em !important;
    }
    .fi-header-subheading {
        color: #a1a1aa !important;
    }

    /* Sections */
    .fi-section {
        background: #15151c !important;
        border: 1px solid rgba(255,255,255,0.05) !important;
        border-radius: 0.75rem !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.3) !important;
    }
    .fi-section-header-heading {
        color: #fafafa !important;
    }

    /* Tables */
    .fi-ta {
        background: #15151c !important;
        border: 1px solid rgba(255,255,255,0.05) !important;
        border-radius: 0.75rem !important;
        overflow: hidden;
    }
    .fi-ta-header {
        background: #1a1a23 !important;
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
    }
    .fi-ta-header-cell, .fi-ta-cell {
        color: #d4d4d8 !important;
    }
    .fi-ta-row {
        border-bottom: 1px solid rgba(255,255,255,0.04) !important;
    }
    .fi-ta-row:hover {
        background: rgba(139, 92, 246, 0.04) !important;
    }

    /* Buttons */
    .fi-btn-color-primary {
        background: linear-gradient(135deg, #8b5cf6, #6366f1) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px -4px rgba(139, 92, 246, 0.5) !important;
        font-weight: 600 !important;
        border: none !important;
    }
    .fi-btn-color-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 22px -4px rgba(139, 92, 246, 0.65) !important;
    }

    /* Inputs */
    .fi-input, .fi-select-input, .fi-textarea-input {
        background: #1a1a23 !important;
        border: 1px solid rgba(255,255,255,0.08) !important;
        color: #e4e4e7 !important;
        border-radius: 0.5rem !important;
    }
    .fi-input:focus, .fi-select-input:focus, .fi-textarea-input:focus {
        border-color: #8b5cf6 !important;
        box-shadow: 0 0 0 3px rgba(139,92,246,0.2) !important;
    }
    .fi-input::placeholder { color: #52525b !important; }

    /* Badges */
    .fi-badge {
        border-radius: 0.4rem !important;
        font-weight: 600 !important;
    }

    /* Tabs */
    .fi-tabs-item {
        color: #a1a1aa !important;
    }
    .fi-tabs-item-active {
        color: #c4b5fd !important;
        border-color: #8b5cf6 !important;
    }

    /* Form labels */
    .fi-fo-field-wrp-label {
        color: #d4d4d8 !important;
    }

    /* Scrollbar */
    *::-webkit-scrollbar { width: 8px; height: 8px; }
    *::-webkit-scrollbar-track { background: transparent; }
    *::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.08); border-radius: 4px; }
    *::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.15); }
</style>
