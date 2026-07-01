<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script>
    (function() {
        try { localStorage.setItem('theme', 'dark'); } catch(e) {}
        document.documentElement.classList.add('dark');
        document.addEventListener('DOMContentLoaded', () => document.documentElement.classList.add('dark'));
    })();
</script>
<style>
    body.fi-body {
        background: #313338 !important;
        color: #dbdee1 !important;
        font-family: 'Inter', sans-serif !important;
        -webkit-font-smoothing: antialiased;
    }

    /* === SIDEBAR — indigo deep === */
    .fi-sidebar {
        background: #2b2d31 !important;
        border-right: 1px solid #1e1f22 !important;
    }
    .fi-sidebar-header {
        background: #1e1f22 !important;
        border-bottom: 1px solid #1e1f22 !important;
    }
    .fi-sidebar-header * {
        color: #f2f3f5 !important;
    }

    /* Sidebar nav items */
    .fi-sidebar-item-button {
        border-radius: 0.5rem !important;
        margin: 1px 8px !important;
        padding: 0.5rem 0.75rem !important;
        color: #b5bac1 !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        transition: all 0.1s !important;
    }
    .fi-sidebar-item-button:hover {
        background: rgba(78, 80, 88, 0.5) !important;
        color: #f2f3f5 !important;
    }
    .fi-sidebar-item-icon {
        color: #80848e !important;
    }
    .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
        color: #dbdee1 !important;
    }

    /* Active — neon indigo glow */
    .fi-sidebar-item-active .fi-sidebar-item-button {
        background: #5865f2 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        box-shadow: 0 0 20px rgba(88, 101, 242, 0.4) !important;
    }
    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #ffffff !important;
        filter: drop-shadow(0 0 6px rgba(255,255,255,0.5));
    }

    /* Group labels */
    .fi-sidebar-group-label {
        color: #949ba4 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
        padding: 1.25rem 1rem 0.4rem !important;
    }

    /* === TOPBAR === */
    .fi-topbar {
        background: rgba(43,45,49,0.92) !important;
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #1e1f22 !important;
    }
    .fi-topbar * {
        color: #dbdee1 !important;
    }

    /* === CONTENT === */
    .fi-main {
        background: #313338 !important;
    }

    .fi-header-heading {
        color: #f2f3f5 !important;
        font-weight: 800 !important;
        letter-spacing: -0.025em !important;
    }
    .fi-header-subheading {
        color: #b5bac1 !important;
    }

    /* Sections */
    .fi-section {
        background: #2b2d31 !important;
        border: 1px solid #1e1f22 !important;
        border-radius: 0.625rem !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3) !important;
    }
    .fi-section-header-heading {
        color: #f2f3f5 !important;
    }

    /* Tables */
    .fi-ta {
        background: #2b2d31 !important;
        border: 1px solid #1e1f22 !important;
        border-radius: 0.625rem !important;
        overflow: hidden;
    }
    .fi-ta-header {
        background: #232428 !important;
        border-bottom: 1px solid #1e1f22 !important;
    }
    .fi-ta-header-cell, .fi-ta-cell {
        color: #dbdee1 !important;
    }
    .fi-ta-row {
        border-bottom: 1px solid rgba(255,255,255,0.04) !important;
    }
    .fi-ta-row:hover {
        background: rgba(88, 101, 242, 0.06) !important;
    }

    /* Buttons */
    .fi-btn-color-primary {
        background: #5865f2 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px -4px rgba(88, 101, 242, 0.5) !important;
        font-weight: 600 !important;
        border: none !important;
    }
    .fi-btn-color-primary:hover {
        background: #4752c4 !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 20px -4px rgba(88, 101, 242, 0.7) !important;
    }

    /* Inputs */
    .fi-input, .fi-select-input, .fi-textarea-input {
        background: #1e1f22 !important;
        border: 1px solid #1e1f22 !important;
        color: #dbdee1 !important;
        border-radius: 0.4rem !important;
    }
    .fi-input:focus, .fi-select-input:focus, .fi-textarea-input:focus {
        border-color: #5865f2 !important;
        box-shadow: 0 0 0 3px rgba(88,101,242,0.25) !important;
    }
    .fi-input::placeholder { color: #6a6e76 !important; }

    /* Badges */
    .fi-badge {
        border-radius: 0.35rem !important;
        font-weight: 700 !important;
    }

    /* Tabs */
    .fi-tabs-item {
        color: #b5bac1 !important;
    }
    .fi-tabs-item-active {
        color: #f2f3f5 !important;
        border-color: #5865f2 !important;
    }

    /* Form labels */
    .fi-fo-field-wrp-label {
        color: #dbdee1 !important;
    }

    /* Scrollbar */
    *::-webkit-scrollbar { width: 8px; height: 8px; }
    *::-webkit-scrollbar-thumb { background: #1a1b1e; border-radius: 4px; }
    *::-webkit-scrollbar-thumb:hover { background: #232428; }
</style>
