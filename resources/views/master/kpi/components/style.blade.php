<style>
    .period-list {
        max-height: 600px;
        overflow-y: auto;
    }

    .period-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        cursor: pointer;
        transition: all .2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .period-item:last-child {
        border-bottom: none;
    }

    .period-item:hover {
        background: #f8fafc;
    }

    .period-item.active {
        background: rgba(98, 89, 202, .08);
        border-left: 4px solid #6259ca;
    }

    .period-content {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .period-icon {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: rgba(98, 89, 202, .1);
        color: #6259ca;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .mini-stat {
        border: 1px solid #eef2f7;
        border-radius: 18px;
        padding: 18px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .mini-stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
    }

    .bg-success-subtle {
        background: rgba(34, 197, 94, .12);
    }

    .bg-warning-subtle {
        background: rgba(245, 158, 11, .12);
    }

    .bg-danger-subtle {
        background: rgba(239, 68, 68, .12);
    }

    .text-success {
        color: #16a34a !important;
    }

    .text-warning {
        color: #d97706 !important;
    }

    .text-danger {
        color: #dc2626 !important;
    }
</style>