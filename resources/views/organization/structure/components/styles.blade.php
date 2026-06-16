<style>
    :root {
        --org-primary: #4f46e5;
        --org-bg: #f8fafc;
        --org-border: #e2e8f0;
        --org-text-main: #0f172a;
        --org-text-muted: #64748b;
        --org-radius: 16px;
        --org-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --org-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
    }

    /* ================= PAGE & WRAPPER ================= */
    .org-page {
        padding: 20px;
    }

    .org-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        border: 1px solid var(--org-border);
        border-radius: var(--org-radius);
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: var(--org-shadow);
    }

    .org-toolbar h3 {
        margin: 0;
        font-weight: 700;
        color: var(--org-text-main);
    }

    .org-toolbar p {
        margin: 4px 0 0;
        color: var(--org-text-muted);
        font-size: 14px;
    }

    /* VERTICAL SCROLL WRAPPER */
    .org-tree-wrapper {
        width: 100%;
        max-height: 75vh;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 12px;
        padding-bottom: 30px;
        -webkit-overflow-scrolling: touch;
    }

    .org-tree-wrapper::-webkit-scrollbar {
        width: 8px;
    }

    .org-tree-wrapper::-webkit-scrollbar-track {
        background: var(--org-bg);
        border-radius: 8px;
    }

    .org-tree-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }

    .org-tree-wrapper::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    #organization-tree {
        width: 100%;
    }

    /* ================= TREE STRUCTURE ================= */
    .org-node {
        margin-left: 24px;
        position: relative;
    }

    .org-children {
        margin-left: 22px;
        padding-left: 24px;
        border-left: 2px solid #cbd5e1;
        position: relative;
    }

    .org-node::before {
        content: '';
        position: absolute;
        top: 38px;
        left: -24px;
        width: 24px;
        height: 2px;
        background: #cbd5e1;
        z-index: 0;
    }

    #organization-tree>.org-node::before {
        display: none;
    }

    #organization-tree>.org-node {
        margin-left: 0;
    }

    /* ================= ITEM CARDS ================= */
    .org-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 72px;
        padding: 14px 20px;
        margin-top: 12px;
        background: #fff;
        border: 1px solid var(--org-border);
        border-radius: var(--org-radius);
        box-shadow: var(--org-shadow);
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }

    .org-item:hover {
        border-color: #cbd5e1;
        box-shadow: var(--org-shadow-hover);
        transform: translateY(-1px);
    }

    .org-main {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .org-content {
        flex: 1;
    }

    .org-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--org-text-main);
        line-height: 1.4;
    }

    .org-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 6px;
        align-items: center;
    }

    /* ================= BADGES ================= */
    .org-badge {
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
        border-radius: 999px;
        text-transform: uppercase;
    }

    .badge-division {
        background: #e0e7ff;
        color: #4338ca;
    }

    .badge-department {
        background: #dcfce7;
        color: #15803d;
    }

    .badge-unit {
        background: #fef3c7;
        color: #b45309;
    }

    .badge-sub_unit {
        background: #f3e8ff;
        color: #7e22ce;
    }

    .org-stat {
        display: flex;
        align-items: center;
        color: var(--org-text-muted);
        font-size: 13px;
        font-weight: 500;
    }

    .org-stat i {
        font-size: 14px;
    }

    /* ================= BUTTONS ================= */
    .org-actions {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 10px;
        background: #f1f5f9;
        color: #475569;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .btn-icon:hover {
        background: #e2e8f0;
        color: var(--org-text-main);
        transform: scale(1.05);
    }

    .btn-icon.primary-soft {
        color: #4f46e5;
        background: #e0e7ff;
    }

    .btn-icon.success-soft {
        color: #10b981;
        background: #d1fae5;
    }

    .btn-icon.secondary-soft {
        color: #64748b;
        background: #e2e8f0;
    }

    .btn-icon.danger {
        color: #ef4444;
        background: #fee2e2;
    }

    .btn-icon.danger:hover {
        background: #ef4444;
        color: #fff;
    }

    .toggle-node {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        background: var(--org-bg);
        color: var(--org-primary);
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
    }

    .toggle-node.empty-node {
        color: #cbd5e1;
        cursor: default;
        box-shadow: none;
    }

    .org-node.open>.org-item .toggle-node:not(.empty-node) {
        transform: rotate(90deg);
        background: var(--org-primary);
        color: #fff;
    }

    .org-collapse {
        display: none;
    }

    .org-node.open>.org-collapse {
        display: block;
        animation: slideDown 0.3s ease forwards;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ================= EMPLOYEES ================= */
    .employee-wrapper {
        margin-left: 48px;
        margin-top: 12px;
        margin-bottom: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .employee-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px 8px 8px;
        background: #fff;
        border: 1px solid var(--org-border);
        border-radius: 999px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: 0.2s;
    }

    .employee-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .employee-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        flex-shrink: 0;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .employee-info {
        display: flex;
        flex-direction: column;
    }

    .employee-name {
        font-size: 13px;
        font-weight: 600;
        color: var(--org-text-main);
    }

    .employee-position {
        font-size: 11px;
        color: var(--org-text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .head-badge {
        background: #fef3c7;
        color: #b45309;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 700;
    }

    .btn-remove-emp {
        width: 24px;
        height: 24px;
        border: none;
        border-radius: 50%;
        background: #fee2e2;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: auto;
        transition: 0.2s;
        opacity: 0.7;
    }

    .employee-item:hover .btn-remove-emp {
        opacity: 1;
    }

    .btn-remove-emp:hover {
        background: #ef4444;
        color: #fff;
    }

    /* ================= FORMS & INPUTS ================= */
    .employee-form {
        margin-left: 48px;
        margin-top: 12px;
        padding: 16px;
        background: #fff;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        min-height: 40px;
        box-shadow: none;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--org-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .input-group-text {
        border-radius: 8px 0 0 8px;
        border-color: #cbd5e1;
        font-weight: 600;
        font-size: 13px;
    }

    .select2-container--default .select2-selection--single {
        height: 40px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        display: flex;
        align-items: center;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
    }

    /* ================= RESPONSIVE ================= */
    @media (max-width: 768px) {
        .org-toolbar {
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
        }

        .org-toolbar-actions {
            display: flex;
            gap: 10px;
        }

        .org-toolbar-actions button {
            flex: 1;
        }

        .org-item {
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
            padding: 16px;
        }

        .org-actions {
            justify-content: flex-end;
            padding-top: 12px;
            border-top: 1px solid var(--org-border);
        }

        .org-node {
            margin-left: 12px;
        }

        .org-children {
            margin-left: 10px;
            padding-left: 12px;
        }

        .org-node::before {
            width: 12px;
            left: -12px;
        }

        .employee-wrapper,
        .employee-form {
            margin-left: 12px;
        }
    }
</style>