<style>
    .select2-container .select2-selection--single,
    .select2-container .select2-selection--multiple {
        min-height: 42px;
        border: 1px solid #dee2e6;
        border-radius: .375rem;
        padding-top: 2px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #0d6efd;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 4px 10px;
        margin-top: 6px;
        font-size: .85rem;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 8px;
        border-right: 1px solid rgba(255, 255, 255, .3);
        padding-right: 8px;
    }

    /* OFFCANVAS */
    .offcanvas-end {
        width: 500px;
    }

    @media (max-width:576px) {
        .offcanvas-end {
            width: 100%;
        }
    }

    /* UTILITIES */
    .border-dashed {
        border-style: dashed !important;
        border-width: 2px !important;
    }

    .fade-out {
        opacity: 0;
        transform: scale(.9);
        transition: all .3s ease;
    }

    /* APPROVAL LIST */
    .approval-list-vertical {
        max-height: 700px;
        overflow-y: auto;
        display: flex;
        width: 100%;
        flex-direction: column;
        gap: 12px;
    }

    .approval-list-vertical::-webkit-scrollbar {
        width: 6px;
    }

    .approval-list-vertical::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 20px;
    }

    .approval-row {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        transition: .2s;
    }

    .approval-row:hover {
        border-color: #22c55e;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .06);
        transform: translateY(-1px);
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-pending {
        background: #f59e0b;
    }

    .status-approved {
        background: #22c55e;
    }

    .status-rejected {
        background: #ef4444;
    }

    .approval-main {
        flex: 1;
        min-width: 0;
    }

    .approval-title {
        font-weight: 700;
        font-size: .95rem;
        color: #111827;
    }

    .approval-meta {
        font-size: .8rem;
        color: #6b7280;
    }

    .member-chip {
        background: #f3f4f6;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: .75rem;
        white-space: nowrap;
    }

    .kpi-chip {
        background: #ecfdf5;
        color: #15803d;
        border: 1px solid #bbf7d0;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: .75rem;
        white-space: nowrap;
    }

    .approval-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: flex-end;
    }

    .approval-info {
        min-width: 170px;
        text-align: right;
    }

    .assigned-kpi-item {
        width: 100%;
        flex-shrink: 0;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        transition: .2s;
    }

    .assigned-kpi-item:hover {
        background: #f1f5f9;
        border-color: #22c55e;
    }

    .formula-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .formula-badge {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        padding: 5px 10px;
        border-radius: 30px;
        font-size: .75rem;
        font-weight: 600;
    }

    .kpi-realization-card {
        background: #fff;
        border: 1px solid #edf1f7;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 15px;
        transition: all .2s ease;
    }

    .kpi-realization-card:hover {
        border-color: #dbe3ff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .05);
    }

    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .kpi-header h6 {
        margin: 0;
    }

    #kpiRealizationList {
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-height: 650px;
        overflow-y: auto;
    }

    #kpiRealizationList::-webkit-scrollbar {
        width: 6px;
    }

    #kpiRealizationList::-webkit-scrollbar-thumb {
        background: #d6dae4;
        border-radius: 10px;
    }

    .badge.bg-success-subtle {
        background: rgba(25, 135, 84, .12);
        color: #198754;
        font-weight: 600;
    }
</style>