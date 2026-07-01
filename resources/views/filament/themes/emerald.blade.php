<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --koperasi-shadow-soft: 0 1px 2px rgba(15,23,42,0.04), 0 4px 12px rgba(15,23,42,0.04);
        --koperasi-shadow-card: 0 1px 3px rgba(15,23,42,0.04), 0 8px 24px -8px rgba(15,23,42,0.08);
        --koperasi-shadow-pop: 0 12px 40px -12px rgba(15,23,42,0.18);
    }
    body.fi-body {
        background:
            radial-gradient(ellipse 80% 60% at 8% 0%, rgba(16,185,129,0.05), transparent 60%),
            radial-gradient(ellipse 60% 50% at 100% 100%, rgba(8,145,178,0.04), transparent 60%),
            #f8fafc;
        -webkit-font-smoothing: antialiased;
        font-feature-settings: "cv02", "cv03", "cv04", "cv11";
    }
    .dark body.fi-body {
        background:
            radial-gradient(ellipse 80% 60% at 8% 0%, rgba(16,185,129,0.06), transparent 60%),
            radial-gradient(ellipse 60% 50% at 100% 100%, rgba(8,145,178,0.05), transparent 60%),
            #0f172a;
    }

    .fi-sidebar {
        border-color: rgba(226, 232, 240, 0.7) !important;
        background: rgba(255,255,255,0.95) !important;
        backdrop-filter: blur(20px) saturate(160%);
    }
    .dark .fi-sidebar {
        background: rgba(15,23,42,0.85) !important;
        border-color: rgba(255,255,255,0.06) !important;
    }

    .fi-sidebar-item-button {
        border-radius: 0.75rem !important;
        margin: 1px 0 !important;
        font-weight: 600 !important;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }
    .fi-sidebar-item-active .fi-sidebar-item-button {
        background: linear-gradient(135deg, rgba(16,185,129,0.12), rgba(6,182,212,0.08)) !important;
        color: #047857 !important;
        position: relative;
    }
    .fi-sidebar-item-active .fi-sidebar-item-button::before {
        content: ""; position: absolute;
        left: 0; top: 25%; bottom: 25%; width: 3px;
        background: linear-gradient(180deg, #10b981, #06b6d4);
        border-radius: 0 3px 3px 0;
    }
    .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #10b981 !important;
    }

    .fi-sidebar-group-label {
        font-size: 10px !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.15em !important;
        color: #94a3b8 !important;
        padding: 1rem 0.75rem 0.5rem !important;
    }

    .fi-topbar {
        background: rgba(255,255,255,0.72) !important;
        backdrop-filter: blur(20px) saturate(160%);
        border-bottom: 1px solid rgba(226, 232, 240, 0.6) !important;
    }

    .fi-header-heading {
        font-weight: 800 !important;
        letter-spacing: -0.025em !important;
    }
    .fi-header-subheading {
        font-weight: 500 !important;
        color: #64748b !important;
    }

    .fi-section {
        border-radius: 1rem !important;
        border-color: rgba(241, 245, 249, 1) !important;
        box-shadow: var(--koperasi-shadow-soft) !important;
    }

    .fi-ta {
        border-radius: 1rem !important;
        box-shadow: var(--koperasi-shadow-card) !important;
        border-color: rgba(241, 245, 249, 1) !important;
        overflow: hidden;
    }
    .fi-ta-row:hover {
        background: linear-gradient(90deg, rgba(16,185,129,0.03), rgba(6,182,212,0.02)) !important;
    }

    .fi-btn-color-primary {
        background: linear-gradient(135deg, #047857, #0d9488) !important;
        box-shadow: 0 4px 14px -4px rgba(5,150,105,0.4) !important;
        font-weight: 700 !important;
    }
    .fi-btn-color-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px -4px rgba(5,150,105,0.55) !important;
    }

    .fi-input, .fi-select-input, .fi-textarea-input {
        border-radius: 0.75rem !important;
    }
    .fi-input:focus, .fi-select-input:focus, .fi-textarea-input:focus {
        box-shadow: 0 0 0 3px rgba(16,185,129,0.15) !important;
        border-color: #10b981 !important;
    }

    .fi-badge {
        font-weight: 700 !important;
        border-radius: 0.5rem !important;
    }

    .fi-tabs-item-active {
        color: #047857 !important;
        border-color: #10b981 !important;
    }

    *::-webkit-scrollbar { width: 8px; height: 8px; }
    *::-webkit-scrollbar-thumb { background: rgb(203 213 225); border-radius: 4px; }
    *::-webkit-scrollbar-thumb:hover { background: rgb(148 163 184); }
</style>
