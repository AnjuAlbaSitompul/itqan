<style>
    /* ======================================
   PAGE
====================================== */

    .org-page {
        padding: 20px;
    }

    .org-toolbar {

        display: flex;
        justify-content: space-between;
        align-items: center;

        background: #fff;

        border: 1px solid #e5e7eb;
        border-radius: 16px;

        padding: 20px;
        margin-bottom: 20px;
    }

    .org-toolbar h3 {
        margin: 0;
        font-weight: 700;
    }

    .org-toolbar p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    /* ======================================
   TREE
====================================== */

    #organization-tree {
        font-size: 14px;
    }

    .org-node {
        margin-left: 18px;
    }

    .org-children {

        margin-left: 18px;
        padding-left: 18px;

        border-left: 1px dashed #dbeafe;
    }

    /* ======================================
   NODE
====================================== */

    .org-item {

        display: flex;
        align-items: center;
        justify-content: space-between;

        min-height: 56px;

        padding: 10px 14px;
        margin-top: 8px;

        background: #fff;

        border: 1px solid #e5e7eb;
        border-radius: 12px;

        transition: all .15s ease;
    }

    .org-item:hover {

        border-color: #cbd5e1;

        box-shadow:
            0 4px 12px rgba(0, 0, 0, .05);
    }

    .org-main {

        display: flex;
        align-items: center;

        gap: 12px;
        flex: 1;
    }

    .org-content {
        flex: 1;
    }

    .org-title {

        font-size: 14px;
        font-weight: 600;

        color: #111827;
        line-height: 1.2;
    }

    .org-meta {

        display: flex;
        flex-wrap: wrap;

        gap: 6px;

        margin-top: 4px;
    }

    /* ======================================
   BADGES
====================================== */

    .org-badge {

        background: #eff6ff;
        color: #2563eb;

        border-radius: 999px;

        padding: 2px 8px;

        font-size: 11px;
        font-weight: 500;
    }

    .org-stat {

        background: #f8fafc;
        color: #64748b;

        border-radius: 999px;

        padding: 2px 8px;

        font-size: 11px;
    }

    /* ======================================
   ACTIONS
====================================== */

    .org-actions {

        display: flex;
        align-items: center;

        gap: 4px;
    }

    .btn-icon {

        width: 32px;
        height: 32px;

        border: none;
        border-radius: 8px;

        background: #f8fafc;
        color: #475569;

        display: flex;
        align-items: center;
        justify-content: center;

        transition: .15s;
    }

    .btn-icon:hover {
        background: #e2e8f0;
    }

    .btn-icon.danger {
        color: #dc2626;
    }

    /* ======================================
   TOGGLE
====================================== */

    .toggle-node {

        width: 28px;
        height: 28px;

        border: none;
        border-radius: 8px;

        background: #f8fafc;

        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toggle-node i {
        transition: .2s ease;
    }

    .toggle-placeholder {
        width: 28px;
    }

    .org-node.open>.org-item .toggle-node i {
        transform: rotate(90deg);
    }


    /* ======================================
   COLLAPSE
====================================== */

    .org-collapse {
        display: none;
    }

    .org-node.open>.org-collapse {
        display: block;
    }

    /* ======================================
   EMPLOYEES
====================================== */

    .employee-wrapper {

        margin-left: 40px;
        margin-top: 8px;

        display: flex;
        flex-wrap: wrap;

        gap: 8px;
    }

    .employee-item {

        display: flex;
        align-items: center;

        gap: 8px;

        padding: 6px 10px;

        background: #f8fafc;

        border: 1px solid #e2e8f0;
        border-radius: 999px;
    }

    .employee-avatar {

        width: 22px;
        height: 22px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #2563eb;
        color: #fff;

        font-size: 10px;
        font-weight: 600;

        flex-shrink: 0;
    }

    .employee-name {

        font-size: 12px;
        font-weight: 500;

        color: #111827;
    }

    .employee-position {
        display: none;
    }

    .head-badge {

        margin-left: 4px;

        background: #fef3c7;
        color: #92400e;

        padding: 2px 6px;

        border-radius: 999px;

        font-size: 10px;
    }

    /* ======================================
   EMPLOYEE FORM
====================================== */

    .employee-form {

        margin-left: 40px;
        margin-top: 10px;

        padding: 12px;

        background: #fff;

        border: 1px solid #e5e7eb;
        border-radius: 12px;
    }

    .employee-form .form-control,
    .employee-form .form-select {

        min-height: 38px;

        border-radius: 10px;
    }

    /* ======================================
   EDIT MODE
====================================== */

    .node-edit {

        width: 100%;
    }

    .node-edit .form-control {

        border-radius: 10px;
    }

    /* ======================================
   RESPONSIVE
====================================== */

    @media (max-width: 768px) {

        .org-toolbar {

            flex-direction: column;
            align-items: flex-start;

            gap: 12px;
        }

        .org-item {

            flex-direction: column;
            align-items: flex-start;

            gap: 10px;
        }

        .org-actions {
            width: 100%;
        }

        .employee-wrapper {
            margin-left: 20px;
        }

        .employee-form {
            margin-left: 20px;
        }
    }

    .select2-container--default .select2-selection--single {

        height: 38px;
        border: 1px solid #dee2e6;
        border-radius: .5rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {

        line-height: 36px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {

        height: 36px;
    }
</style>