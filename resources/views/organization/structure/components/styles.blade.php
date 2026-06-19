<style>
    /* ================= TREE & LINE STRUCTURE ================= */
    .org-tree-wrapper {
        width: 100%;
        max-height: 75vh;
        overflow-y: auto;
        overflow-x: hidden;
        padding-right: 12px;
        padding-bottom: 50px;
    }

    .org-tree-wrapper::-webkit-scrollbar {
        width: 6px;
    }

    .org-tree-wrapper::-webkit-scrollbar-track {
        background: transparent;
    }

    .org-tree-wrapper::-webkit-scrollbar-thumb {
        background: #adb5bd;
        border-radius: 10px;
    }

    .org-node {
        margin-left: 24px;
        position: relative;
    }

    .org-children {
        margin-left: 22px;
        padding-left: 24px;
        border-left: 2px solid #adb5bd;
        position: relative;
    }

    .org-node::before {
        content: '';
        position: absolute;
        top: 38px;
        left: -24px;
        width: 24px;
        height: 2px;
        background: #adb5bd;
        z-index: 0;
    }

    #organization-tree>.org-node::before {
        display: none;
    }

    #organization-tree>.org-node {
        margin-left: 0;
    }

    /* ================= CARDS & UI (Dark Mode Friendly) ================= */
    .org-item {
        margin-top: 12px;
        transition: all 0.2s ease;
        position: relative;
        z-index: 1;
    }

    .org-item:hover {
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

    .org-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 6px;
        align-items: center;
    }

    .org-stat {
        font-size: 13px;
        font-weight: 500;
    }

    .org-stat i {
        font-size: 14px;
    }

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
        border-radius: 50px;
        transition: 0.2s;
    }

    /* Update di bagian .employee-avatar */
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
        overflow: hidden;
        /* Tambahkan ini agar border-radius memotong gambar yang lewat */
    }

    /* Tambahkan block CSS ini agar image dari avatar mengisi dengan proporsional */
    .employee-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .employee-info {
        display: flex;
        flex-direction: column;
    }

    .employee-name {
        font-size: 13px;
        font-weight: 600;
    }

    .employee-position {
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 6px;
        opacity: 0.8;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
    }

    .btn-icon:hover {
        transform: scale(1.05);
    }

    .toggle-node {
        border-radius: 50%;
        z-index: 2;
    }

    .org-node.open>.org-item .card-body .toggle-node:not(:disabled) {
        transform: rotate(90deg);
    }

    .org-collapse {
        display: none;
    }

    .org-node.open>.org-collapse {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
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

        .employee-wrapper {
            margin-left: 12px;
            flex-direction: column;
        }
    }
</style>