<script>
    // Global Options
    window.userOptions = `
            <option value="">Select User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        `;
    window.outletOptions = `
            <option value="">General Unit (No Outlet)</option>
            @foreach($outlets as $outlet)
                <option value="{{ $outlet->id }}">{{ $outlet->name }}</option>
            @endforeach
        `;
    window.jabatanOptions = `
            <option value="">Select Position</option>
            @foreach($jabatans as $jabatan)
                <option value="{{ $jabatan->id }}">{{ $jabatan->name }}</option>
            @endforeach
        `;

    $(function () {
        // Open root nodes
        $('#organization-tree > .org-node').addClass('open');

        function getNextType(type) {
            switch (type) {
                case 'division': return 'department';
                case 'department': return 'unit';
                case 'unit': return 'sub_unit';
                default: return null;
            }
        }

        function updateEmployeeCount(node) {
            const total = node.find('> .org-collapse > .employee-wrapper > .employee-item').length;
            node.find('> .org-item .employee-count').text(total);
        }

        function buildTree($node) {
            let children = [];
            let employees = [];

            $node.find('> .org-collapse > .org-children > .org-node').each(function () {
                children.push(buildTree($(this)));
            });

            $node.find('> .org-collapse > .employee-wrapper > .employee-item').each(function () {
                employees.push({
                    user_id: $(this).attr('data-user-id'),
                    jabatan_id: $(this).attr('data-jabatan-id'),
                    is_head: parseInt($(this).attr('data-is-head')) || 0
                });
            });

            // URUTKAN AGAR HEAD SELALU DI INDEX 0 (Menyesuaikan dengan Backend Controller)
            employees.sort((a, b) => b.is_head - a.is_head);

            return {
                id: $node.data('id'),
                parent_id: $node.data('parent-id'),
                type: $node.data('type'),
                name: $node.find('.node-name').val() || $.trim($node.find('.node-title').first().text()),
                man_power: $node.find('.node-man-power').val() || 0,
                outlet_id: $node.find('.node-outlet').val() || null,
                employees,
                children
            };
        }

        function collectTree() {
            let tree = [];
            $('#organization-tree > .org-node').each(function () {
                tree.push(buildTree($(this)));
            });
            return tree;
        }

        function nodeTemplate(parentId, type) {
            const uid = 'tmp_' + Date.now() + Math.floor(Math.random() * 99999);
            const typeLabel = type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());

            return `
        <div class="org-node open" data-id="${uid}" data-parent-id="${parentId ?? ''}" data-type="${type}">
            <div class="card shadow-sm org-item">
                <div class="card-body p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <div class="org-main">
                        <button type="button" class="btn btn-primary btn-sm toggle-node"><i class="fe fe-chevron-right"></i></button>
                        <div class="org-content">
                            <div class="node-view d-none">
                                <div class="fs-5 fw-bold node-title">${typeLabel} Name</div>
                                <div class="org-meta text-muted">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">${typeLabel}</span>
                                    <span class="org-stat"><i class="fe fe-target me-1"></i> MP: <strong class="manpower-count">0</strong></span>
                                    <span class="org-stat"><i class="fe fe-users me-1"></i> EMP: <strong class="employee-count">0</strong></span>
                                </div>
                            </div>
                            <div class="node-edit">
                                <div class="row g-2 align-items-center">
                                    <div class="col-12 col-md-4">
                                        <input type="text" class="form-control form-control-sm node-name" placeholder="${typeLabel} Name">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">MP</span>
                                            <input type="number" value="0" class="form-control node-man-power">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <select class="form-select form-select-sm node-outlet select2-outlet">
                                            ${window.outletOptions}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-1 flex-wrap">
                        <button type="button" class="btn btn-outline-secondary btn-sm btn-icon manage-employee" title="Manage Employees"><i class="fe fe-users"></i></button>
                        ${type !== 'sub_unit' ? `<button type="button" class="btn btn-outline-primary btn-sm btn-icon add-child" title="Add Child"><i class="fe fe-plus"></i></button>` : ''}
                        <button type="button" class="btn btn-outline-info btn-sm btn-icon edit-node d-none" title="Edit"><i class="fe fe-edit-2"></i></button>
                        <button type="button" class="btn btn-outline-success btn-sm btn-icon save-node" title="Save"><i class="fe fe-check"></i></button>
                        <button type="button" class="btn btn-outline-secondary btn-sm btn-icon cancel-edit d-none" title="Cancel"><i class="fe fe-x"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-icon delete-node" title="Delete"><i class="fe fe-trash-2"></i></button>
                    </div>
                </div>
            </div>
            <div class="org-collapse">
                <div class="employee-wrapper"></div>
                <div class="org-children"></div>
            </div>
        </div>`;
        }

        // Accordion Toggle
        $(document).on('click', '.toggle-node', function (e) {
            e.stopPropagation();
            $(this).closest('.org-node').toggleClass('open');
        });

        // Add Root Node
        $('#addRootDivision').click(function () {
            $('#organization-tree').prepend(nodeTemplate(null, 'division'));
            setTimeout(() => { $('.select2-outlet').select2({ width: '100%' }); }, 50);
        });

        // Add Child Node
        $(document).on('click', '.add-child', function () {
            const node = $(this).closest('.org-node');
            const nextType = getNextType(node.data('type'));
            if (!nextType) return;
            const child = $(nodeTemplate(node.data('id'), nextType));
            node.find('> .org-collapse > .org-children').append(child);
            child.find('.select2-outlet').select2({ width: '100%' });
            node.addClass('open');
        });

        // Delete Node
        $(document).on('click', '.delete-node', function () {
            if (confirm('Delete node?')) $(this).closest('.org-node').remove();
        });

        // Edit / Save Node Mode
        $(document).on('click', '.edit-node', function () {
            const card = $(this).closest('.org-item');
            card.find('.node-view, .edit-node').addClass('d-none');
            card.find('.node-edit, .save-node, .cancel-edit').removeClass('d-none');
        });

        $(document).on('click', '.cancel-edit', function () {
            const card = $(this).closest('.org-item');
            card.find('.node-edit, .save-node, .cancel-edit').addClass('d-none');
            card.find('.node-view, .edit-node').removeClass('d-none');
        });

        $(document).on('click', '.save-node', function () {
            const card = $(this).closest('.org-item');
            card.find('.node-title').text(card.find('.node-name').val());
            card.find('.manpower-count').text(card.find('.node-man-power').val());
            card.find('.node-edit, .save-node, .cancel-edit').addClass('d-none');
            card.find('.node-view, .edit-node').removeClass('d-none');
        });

        // =========================
        // Manage Employee
        // =========================
        $(document).on('click', '.manage-employee', function () {
            const node = $(this).closest('.org-node');
            const wrapper = node.find('> .org-collapse > .employee-wrapper');

            // Cegah double form
            if (wrapper.find('.employee-form-add').length) return;

            wrapper.prepend(`
                    <div class="card w-100 mb-2 employee-form employee-form-add shadow-sm">
                        <div class="card-body p-2">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <select class="form-select form-select-sm employee-user select2-user">${window.userOptions}</select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select form-select-sm employee-jabatan select2-jabatan">${window.jabatanOptions}</select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mt-1">
                                        <input type="checkbox" class="form-check-input employee-head">
                                        <label class="form-check-label small">Head</label>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-success btn-sm save-employee"><i class="fe fe-check"></i></button>
                                    <button type="button" class="btn btn-secondary btn-sm cancel-add-employee"><i class="fe fe-x"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);

            wrapper.find('.select2-user').first().select2({ width: '100%', placeholder: 'Search Employee' });
            wrapper.find('.select2-jabatan').first().select2({ width: '100%', placeholder: 'Search Position' });
            node.addClass('open');
        });

        $(document).on('click', '.cancel-add-employee', function () {
            $(this).closest('.employee-form-add').remove();
        });

        // Add Employee Save
        $(document).on('click', '.save-employee', function () {
            const form = $(this).closest('.employee-form');
            const node = form.closest('.org-node');
            const wrapper = node.find('> .org-collapse > .employee-wrapper');

            const userId = form.find('.employee-user').val();
            const jabatanId = form.find('.employee-jabatan').val();
            if (!userId || !jabatanId) return;

            const userText = form.find('.employee-user option:selected').text();
            const jabatanText = form.find('.employee-jabatan option:selected').text();
            const isHead = form.find('.employee-head').is(':checked');

            // Validasi: Jika ini dijadikan head, hapus status head pegawai lain di unit ini
            if (isHead) {
                wrapper.find('.employee-item').attr('data-is-head', '0');
                wrapper.find('.head-badge').remove();
            }

            const initials = userText.trim().split(/\s+/).map(x => x.charAt(0)).join('').substring(0, 2).toUpperCase();

            wrapper.append(`
                    <div class="card employee-item flex-row align-items-center mb-2" 
                         data-user-id="${userId}" data-jabatan-id="${jabatanId}" data-is-head="${isHead ? '1' : '0'}">
                        <div class="employee-avatar">${initials}</div>
                        <div class="employee-info ms-2 flex-grow-1">
                            <div class="employee-name">${userText}</div>
                            <div class="employee-position">
                                <span>${jabatanText}</span>
                                ${isHead ? '<span class="badge bg-warning text-dark head-badge ms-1"><i class="fe fe-star"></i> Head</span>' : ''}
                            </div>
                        </div>
                        <div class="d-flex gap-1 pe-2">
                            <button type="button" class="btn btn-sm btn-light edit-employee-btn"><i class="fe fe-edit-2 text-info"></i></button>
                            <button type="button" class="btn btn-sm btn-light remove-employee"><i class="fe fe-trash-2 text-danger"></i></button>
                        </div>
                    </div>
                `);

            updateEmployeeCount(node);
            form.remove();
        });

        // =========================
        // Edit Existing Employee
        // =========================
        $(document).on('click', '.edit-employee-btn', function () {
            const item = $(this).closest('.employee-item');

            if (item.next('.employee-form-edit').length) return;

            const userId = item.attr('data-user-id');
            const jabatanId = item.attr('data-jabatan-id');
            const isHead = item.attr('data-is-head') == '1';

            const formHtml = `
                    <div class="card w-100 mb-2 employee-form employee-form-edit shadow-sm border-info">
                        <div class="card-body p-2">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-4">
                                    <select class="form-select form-select-sm edit-user select2-user">${window.userOptions}</select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select form-select-sm edit-jabatan select2-jabatan">${window.jabatanOptions}</select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mt-1">
                                        <input type="checkbox" class="form-check-input edit-head" ${isHead ? 'checked' : ''}>
                                        <label class="form-check-label small">Head</label>
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-info btn-sm update-employee text-white"><i class="fe fe-check"></i></button>
                                    <button type="button" class="btn btn-secondary btn-sm cancel-edit-employee"><i class="fe fe-x"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

            item.hide().after(formHtml);
            const form = item.next('.employee-form-edit');

            form.find('.select2-user').val(userId).select2({ width: '100%' });
            form.find('.select2-jabatan').val(jabatanId).select2({ width: '100%' });
        });

        $(document).on('click', '.cancel-edit-employee', function () {
            const form = $(this).closest('.employee-form-edit');
            form.prev('.employee-item').show();
            form.remove();
        });

        // Update Employee Save
        $(document).on('click', '.update-employee', function () {
            const form = $(this).closest('.employee-form-edit');
            const item = form.prev('.employee-item');
            const node = form.closest('.org-node');
            const wrapper = node.find('> .org-collapse > .employee-wrapper');

            const userId = form.find('.edit-user').val();
            const jabatanId = form.find('.edit-jabatan').val();
            const isHead = form.find('.edit-head').is(':checked');
            const userText = form.find('.edit-user option:selected').text().trim();
            const jabatanText = form.find('.edit-jabatan option:selected').text().trim();

            if (!userId || !jabatanId) return;

            // Validasi: Jika dicentang sebagai Head, batalkan Head yang lain di wrapper ini
            if (isHead) {
                wrapper.find('.employee-item').attr('data-is-head', '0');
                wrapper.find('.head-badge').remove();
            }

            const initials = userText.split(/\s+/).map(x => x.charAt(0)).join('').substring(0, 2).toUpperCase();

            item.attr('data-user-id', userId);
            item.attr('data-jabatan-id', jabatanId);
            item.attr('data-is-head', isHead ? '1' : '0');

            item.find('.employee-avatar').text(initials);
            item.find('.employee-name').text(userText);
            item.find('.employee-position').html(`
                    <span>${jabatanText}</span>
                    ${isHead ? '<span class="badge bg-warning text-dark head-badge ms-1"><i class="fe fe-star"></i> Head</span>' : ''}
                `);

            item.show();
            form.remove();
            updateEmployeeCount(node);
        });

        // Remove Employee
        $(document).on('click', '.remove-employee', function () {
            const node = $(this).closest('.org-node');
            $(this).closest('.employee-item').remove();
            updateEmployeeCount(node);
        });

        // =========================
        // Save Server AJAX
        // =========================
        $('#saveTree').click(function () {
            const tree = collectTree();
            console.log(tree)
            // Tambahkan loading spinner jika perlu
            const btn = $(this);
            const originalHtml = btn.html();
            btn.html('<i class="spinner-border spinner-border-sm me-1"></i> Saving...').prop('disabled', true);

            $.ajax({
                url: '/organization/save',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    tree: tree
                },
                success: function (res) {
                    swal({ type: 'success', title: 'Berhasil', text: res.message });
                },
                error: function (xhr) {
                    console.error(xhr.responseJSON?.error);
                    swal({ type: 'error', title: 'Error', text: xhr.responseJSON?.message });
                },
                complete: function () {
                    btn.html(originalHtml).prop('disabled', false);
                }
            });
        });

    });
</script>