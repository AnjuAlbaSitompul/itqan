<style>
    /* ===== LIST ===== */

    .period-list {
        max-height: 650px;
        overflow-y: auto;
        background: #f8fafc;
    }

    .period-list::-webkit-scrollbar {
        width: 5px;
    }

    .period-list::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 20px;
    }

    .period-filter {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #fff;
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }

    /* ===== PERIOD ITEM ===== */

    .period-item {
        margin: 8px;
        padding: 14px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: .2s;
    }

    .period-item:hover {
        border-color: #6259ca;
    }

    .period-item.active {
        border-color: #6259ca;
        background: rgba(98, 89, 202, .05);
    }

    .period-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .period-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: rgba(98, 89, 202, .1);
        color: #6259ca;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .period-title {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
    }

    .period-subtitle {
        font-size: 12px;
        color: #6b7280;
    }

    .period-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        background: #dcfce7;
        color: #15803d;
    }

    /* ===== CARD ===== */

    .card {
        border-radius: 14px;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* ===== MINI STAT ===== */

    .mini-stat {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
    }

    .mini-stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 16px;
    }

    /* ===== OFFCANVAS ===== */

    .kpi-offcanvas {
        width: 600px !important;
    }

    .kpi-offcanvas .offcanvas-header,
    .kpi-offcanvas .offcanvas-footer {
        background: #fff;
    }

    .kpi-offcanvas .offcanvas-body {
        background: #f8fafc;
        padding: 1.5rem;
    }

    /* ===== FORM ===== */

    .form-section {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 16px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 16px;
        color: #111827;
    }

    .form-control,
    .form-select {
        min-height: 42px;
        border-radius: 10px;
        border-color: #d1d5db;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6259ca;
        box-shadow: none;
    }

    /* ===== DELETE BUTTON ===== */

    .btn-delete-period {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        background: transparent;
        transition: .2s;
    }

    .btn-delete-period:hover {
        background: #fee2e2;
    }

    .btn-delete-period i {
        color: #dc2626;
    }

    /* ===== MOBILE ===== */

    @media (max-width: 768px) {
        .kpi-offcanvas {
            width: 100% !important;
        }

        .offcanvas-body,
        .offcanvas-footer {
            padding: 1rem;
        }
    }

    </style>