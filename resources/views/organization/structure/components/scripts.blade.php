<script>
    $(function () {
        // Open root nodes
        $('#organization-tree > .org-node').addClass('open');

        // =========================
        // Helpers
        // =========================
        function buildOutletOptions() {

            let html = `
        <option value="">
            General (No Outlet)
        </option>
    `;

            window.outlets.forEach(outlet => {

                html += `
            <option value="${outlet.id}">
                ${outlet.name}
            </option>
        `;

            });

            return html;
        }
        function getNextType(type) {

            switch (type) {

                case 'division':
                    return 'department';

                case 'department':
                    return 'unit';

                case 'unit':
                    return 'sub_unit';

                default:
                    return null;
            }
        }

        function updateEmployeeCount(node) {

            const total = node
                .find('> .org-collapse > .employee-wrapper .employee-item')
                .length;

            node.find('.employee-count').text(total);
        }

        function buildTree($node) {

            let children = [];
            let employees = [];

            $node
                .find('> .org-collapse > .org-children > .org-node')
                .each(function () {

                    children.push(
                        buildTree($(this))
                    );

                });

            $node
                .find('> .org-collapse > .employee-wrapper > .employee-item')
                .each(function () {

                    employees.push({
                        user_id: $(this).data('user-id'),
                        jabatan_id: $(this).data('jabatan-id'),
                        is_head: $(this).data('is-head') || 0
                    });

                });

            return {
                id: $node.data('id'),
                parent_id: $node.data('parent-id'),
                type: $node.data('type'),

                name:
                    $node.find('.node-name').val()
                    ||
                    $.trim(
                        $node.find('.node-title').first().text()
                    ),

                man_power:
                    $node.find('.node-man-power').val()
                    ||
                    0,

                outlet_id:
                    $node.find('.node-outlet').val()
                    || null,

                employees,
                children
            };
        }

        function collectTree() {

            let tree = [];

            $('#organization-tree > .org-node')
                .each(function () {

                    tree.push(
                        buildTree($(this))
                    );

                });

            return tree;
        }

        // =========================
        // Template
        // =========================

        function nodeTemplate(parentId, type) {

            const uid =
                'tmp_' +
                Date.now() +
                Math.floor(Math.random() * 99999);

            return `
            <div
                class="org-node open"
                data-id="${uid}"
                data-parent-id="${parentId ?? ''}"
                data-type="${type}"
            >

                <div class="org-item">

                    <div class="org-main">

                        <button type="button" class="btn-icon toggle-node">
    <i class="fe fe-chevron-right"></i>
</button>

                        <div class="org-content">

                            <div class="node-view d-none">

                                <div class="org-title node-title">
                                    New ${type}
                                </div>

                                <div class="org-meta">

                                    <span class="org-badge">
                                        ${type.replace('_', ' ')}
                                    </span>

                                    <span class="org-stat">
                                        MP:
                                        <span class="manpower-count">
                                            0
                                        </span>
                                    </span>

                                    <span class="org-stat">
                                        EMP:
                                        <span class="employee-count">
                                            0
                                        </span>
                                    </span>

                                </div>

                            </div>

<div class="node-edit">

    <div class="row g-2">

        <div class="col-md-${type === 'unit' ? '4' : '8'}">

            <input
                type="text"
                class="form-control form-control-sm node-name"
                placeholder="Name"
            >

        </div>

        ${type === 'unit'
                    ?
                    `
                <div class="col-md-4">

                    <select
                        class="form-select form-select-sm node-outlet select2-outlet"
                    >
                        ${window.outletOptions}
                    </select>

                </div>
                `
                    :
                    ''
                }

        <div class="col-md-4">

            <input
                type="number"
                value="0"
                class="form-control form-control-sm node-man-power"
                placeholder="MP"
            >

        </div>

    </div>

</div>

                        </div>

                    </div>

                    <div class="org-actions">

                        <button
                            type="button"
                            class="btn-icon manage-employee"
                        >
                            <i class="fe fe-users"></i>
                        </button>

                        ${type !== 'sub_unit'
                    ? `
                                <button
                                    type="button"
                                    class="btn-icon add-child"
                                >
                                    <i class="fe fe-plus"></i>
                                </button>
                                `
                    : ''
                }

                        <button
                            type="button"
                            class="btn-icon delete-node danger"
                        >
                            <i class="fe fe-trash"></i>
                        </button>

                    </div>

                </div>

                <div class="org-collapse">

                    <div class="employee-wrapper"></div>

                    <div class="org-children"></div>

                </div>

            </div>
        `;
        }

        // =========================
        // Accordion
        // =========================

        $(document).on(
            'click',
            '.toggle-node',
            function (e) {

                e.stopPropagation();

                $(this)
                    .closest('.org-node')
                    .toggleClass('open');
            }
        );

        // =========================
        // Root Node
        // =========================

        $('#addRootDivision').click(function () {

            $('#organization-tree')
                .prepend(
                    nodeTemplate(
                        null,
                        'division'
                    )
                );

            setTimeout(() => {
                $('.select2-outlet').select2({
                    width: '100%'
                });

            }, 50);

        });

        // =========================
        // Add Child
        // =========================

        $(document).on(
            'click',
            '.add-child',
            function () {

                const node =
                    $(this).closest('.org-node');

                const nextType =
                    getNextType(
                        node.data('type')
                    );

                if (!nextType)
                    return;

                const child = $(nodeTemplate(
                    node.data('id'),
                    nextType
                ));

                node
                    .find('> .org-collapse > .org-children')
                    .append(child);

                child.find('.select2-outlet').select2({
                    width: '100%'
                });
                node.addClass('open');

            }
        );

        // =========================
        // Delete Node
        // =========================

        $(document).on(
            'click',
            '.delete-node',
            function () {

                if (!confirm('Delete node?'))
                    return;

                $(this)
                    .closest('.org-node')
                    .remove();
            }
        );

        // =========================
        // Edit Node
        // =========================

        $(document).on(
            'click',
            '.edit-node',
            function () {

                const card =
                    $(this).closest('.org-item');

                card.find('.node-view')
                    .addClass('d-none');

                card.find('.node-edit')
                    .removeClass('d-none');

                card.find('.edit-node')
                    .addClass('d-none');

                card.find('.save-node')
                    .removeClass('d-none');

                card.find('.cancel-edit')
                    .removeClass('d-none');
            }
        );

        $(document).on(
            'click',
            '.cancel-edit',
            function () {

                const card =
                    $(this).closest('.org-item');

                card.find('.node-edit')
                    .addClass('d-none');

                card.find('.node-view')
                    .removeClass('d-none');

                card.find('.save-node')
                    .addClass('d-none');

                card.find('.cancel-edit')
                    .addClass('d-none');

                card.find('.edit-node')
                    .removeClass('d-none');
            }
        );

        $(document).on(
            'click',
            '.save-node',
            function () {

                const card =
                    $(this).closest('.org-item');

                const name =
                    card.find('.node-name').val();

                const manpower =
                    card.find('.node-man-power').val();

                card.find('.node-title')
                    .text(name);

                card.find('.manpower-count')
                    .text(manpower);

                card.find('.node-edit')
                    .addClass('d-none');

                card.find('.node-view')
                    .removeClass('d-none');

                card.find('.save-node')
                    .addClass('d-none');

                card.find('.cancel-edit')
                    .addClass('d-none');

                card.find('.edit-node')
                    .removeClass('d-none');
            }
        );

        // =========================
        // Employee
        // =========================

        $(document).on(
            'click',
            '.manage-employee',
            function () {

                const node = $(this).closest('.org-node');

                if (node.find('.employee-form').length)
                    return;

                const wrapper = node.find(
                    '> .org-collapse > .employee-wrapper'
                );

                wrapper.prepend(`
            <div class="employee-form mb-2">
                <div class="row g-2">

                    <div class="col-md-5">
                        <select class="form-select employee-user select2-user">
                            ${window.userOptions}
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select class="form-select employee-jabatan select2-jabatan">
                            ${window.jabatanOptions}
                        </select>
                    </div>

                    <div class="col-md-2">
                        <div class="form-check mt-2">
                            <input
                                type="checkbox"
                                class="form-check-input employee-head">
                            <label>Head</label>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <button
                            type="button"
                            class="btn btn-success save-employee">
                            +
                        </button>
                    </div>

                </div>
            </div>
        `);

                wrapper.find('.select2-user')
                    .first()
                    .select2({
                        width: '100%',
                        placeholder: 'Search Employee'
                    });

                wrapper.find('.select2-jabatan')
                    .first()
                    .select2({
                        width: '100%',
                        placeholder: 'Search Position'
                    });

                node.addClass('open');
            }
        );

        $(document).on(
            'click',
            '.save-employee',
            function () {

                const form =
                    $(this).closest('.employee-form');

                const node =
                    form.closest('.org-node');

                const userId =
                    form.find('.employee-user').val();

                const jabatanId =
                    form.find('.employee-jabatan').val();

                if (!userId || !jabatanId)
                    return;

                const userText =
                    form.find('.employee-user option:selected').text();

                const jabatanText =
                    form.find('.employee-jabatan option:selected').text();

                const isHead =
                    form.find('.employee-head').is(':checked');

                const initials =
                    userText
                        .trim()
                        .split(/\s+/)
                        .map(x => x.charAt(0))
                        .join('')
                        .substring(0, 2)
                        .toUpperCase();

                node
                    .find('> .org-collapse > .employee-wrapper')
                    .append(`
                    <div
                        class="employee-item"
                        data-user-id="${userId}"
                        data-jabatan-id="${jabatanId}"
                        data-is-head="${isHead ? 1 : 0}"
                    >

                        <div class="employee-avatar">
                            ${initials}
                        </div>

                        <div>

                            <div class="employee-name">
                                ${userText}
                            </div>

                            <div class="employee-position">

                                ${jabatanText}

                                ${isHead
                            ? '<span class="head-badge">Head</span>'
                            : ''
                        }

                            </div>

                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-light remove-employee"
                        >
                            <i class="fe fe-trash-2"></i>
                        </button>

                    </div>
                `);

                updateEmployeeCount(node);

                form.remove();
            }
        );

        $(document).on(
            'click',
            '.remove-employee',
            function () {

                const node =
                    $(this).closest('.org-node');

                $(this)
                    .closest('.employee-item')
                    .remove();

                updateEmployeeCount(node);
            }
        );

        // =========================
        // Save
        // =========================

        $('#saveTree').click(function () {

            const tree = collectTree();

            $.ajax({
                url: '/organization/save',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    tree: tree
                },
                success: function (res) {
                    swal({
                        type: 'success',
                        title: 'Berhasil',
                        text: res.message
                    });

                },
                error: function (xhr) {

                    console.error(xhr.responseJSON?.error)
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message
                    });

                }
            });

        });

    });
</script>