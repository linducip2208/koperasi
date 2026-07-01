<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>
    (function() {
        try { localStorage.setItem('theme', 'light'); } catch(e) {}
        document.documentElement.classList.remove('dark');
        document.addEventListener('DOMContentLoaded', () => document.documentElement.classList.remove('dark'));
    })();
</script>
<style>
    body.fi-body {
        background: #fefcf8 !important;
        font-family: 'Inter', sans-serif !important;
        -webkit-font-smoothing: antialiased;
    }

    /* === SIDEBAR — beige hangat === */
    .fi-sidebar {
        background: #f7f3ed !important;
        border-right: 1px solid #e8e0d3 !important;
    }
    .fi-sidebar-header {
        background: transparent !important;
        border-bottom: 1px solid #e8e0d3 !important;
    }

    /* Sidebar nav items */
    .fi-sidebar-item-button {
        border-radius: 0.5rem !important;
        margin: 1px 6px !important;
        padding: 0.45rem 0.75rem !important;
        color: #5b4636 !important;
        font-weight: 500 !important;
        font-size: 14px !important;
        transition: all 0.12s !important;
    }
    .fi-sidebar-item-button:hover {
        background: rgba(180, 130, 70, 0.08) !important;
        color: #2c1810 !important;
    }
    .fi-sidebar-item-icon {
        color: #8b6f4e !important;
    }

    /* Active — orange/coklat hangat */
    .fi-sidebar-item-active .fi-sidebar-item-button {
        background: rgba(217, 119, 6, 0.12) !important;
        color: #92400e !important;
        font-weight: 600 !important;
    }
    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #d97706 !important;
    }

    /* Group labels */
    .fi-sidebar-group-label {
        color: #a08866 !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.08em !important;
        padding: 1.25rem 0.875rem 0.4rem !important;
    }

    /* === TOPBAR === */
    .fi-topbar {
        background: rgba(254,252,248,0.92) !important;
        backdrop-filter: blur(12px);
        border-bottom: 1px solid #e8e0d3 !important;
    }

    /* === CONTENT === */
    .fi-main {
        background: #fefcf8 !important;
    }

    .fi-header-heading {
        color: #2c1810 !important;
        font-weight: 700 !important;
        letter-spacing: -0.025em !important;
    }
    .fi-header-subheading {
        color: #8b6f4e !important;
    }

    /* Sections */
    .fi-section {
        background: #ffffff !important;
        border: 1px solid #ebe3d4 !important;
        border-radius: 0.625rem !important;
        box-shadow: 0 1px 2px rgba(120,80,30,0.04) !important;
    }

    /* Tables */
    .fi-ta {
        background: #ffffff !important;
        border: 1px solid #ebe3d4 !important;
        border-radius: 0.625rem !important;
        overflow: hidden;
    }
    .fi-ta-header {
        background: #faf7f1 !important;
        border-bottom: 1px solid #ebe3d4 !important;
    }
    .fi-ta-row:hover {
        background: rgba(217, 119, 6, 0.04) !important;
    }

    /* Buttons */
    .fi-btn-color-primary {
        background: #d97706 !important;
        color: #ffffff !important;
        box-shadow: 0 2px 8px -2px rgba(217,119,6,0.4) !important;
        font-weight: 600 !important;
        border: none !important;
    }
    .fi-btn-color-primary:hover {
        background: #b45309 !important;
        transform: translateY(-1px);
    }

    /* Inputs */
    .fi-input, .fi-select-input, .fi-textarea-input {
        border-radius: 0.5rem !important;
        border-color: #e8e0d3 !important;
    }
    .fi-input:focus, .fi-select-input:focus, .fi-textarea-input:focus {
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgba(217,119,6,0.15) !important;
    }

    /* Badges */
    .fi-badge {
        border-radius: 0.4rem !important;
        font-weight: 600 !important;
    }

    /* Tabs */
    .fi-tabs-item-active {
        color: #92400e !important;
        border-color: #d97706 !important;
    }

    /* Scrollbar */
    *::-webkit-scrollbar { width: 8px; height: 8px; }
    *::-webkit-scrollbar-thumb { background: #d6c4a8; border-radius: 4px; }
    *::-webkit-scrollbar-thumb:hover { background: #b8a085; }
</style>
