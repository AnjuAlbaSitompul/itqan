<div class="org-node" data-id="{{ $node->id }}" data-parent-id="{{ $node->parent_id }}" data-type="{{ $node->type }}">

    <div class="card shadow-sm org-item">
        <div class="card-body p-3 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

            <div class="org-main">
                @if($node->children->count() || $node->employees->count())
                    <button type="button" class="btn btn-primary btn-sm toggle-node">
                        <i class="fe fe-chevron-right"></i>
                    </button>
                @else
                    <button type="button" class="btn btn-outline-secondary border-0 btn-sm toggle-node empty-node" disabled>
                        <i class="fe fe-minus"></i>
                    </button>
                @endif

                <div class="org-content">
                    <div class="node-view">
                        <div class="fs-5 fw-bold node-title">
                            {{ $node->name }}
                        </div>
                        <div class="org-meta text-muted">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                {{ ucfirst(str_replace('_', ' ', $node->type)) }}
                            </span>
                            <span class="org-stat">
                                <i class="fe fe-target me-1"></i> MP: <strong
                                    class="manpower-count">{{ $node->man_power }}</strong>
                            </span>
                            <span class="org-stat">
                                <i class="fe fe-users me-1"></i> EMP: <strong
                                    class="employee-count">{{ $node->employees->count() }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="node-edit d-none">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-4">
                                <input type="text" class="form-control form-control-sm node-name"
                                    value="{{ $node->name }}" placeholder="Node Name">
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">MP</span>
                                    <input type="number" class="form-control node-man-power"
                                        value="{{ $node->man_power }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-5">
                                <select class="form-select form-select-sm node-outlet select2-outlet">
                                    <option value="">General (No Outlet)</option>
                                    @foreach($outlets as $outlet)
                                        <option value="{{ $outlet->id }}" {{ $node->outlet_id == $outlet->id ? 'selected' : '' }}>
                                            {{ $outlet->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-1 flex-wrap">
                <button type="button" class="btn btn-outline-secondary btn-sm btn-icon manage-employee"
                    title="Manage Employees"><i class="fe fe-users"></i></button>

                @if($node->type != 'sub_unit')
                    <button type="button" class="btn btn-outline-primary btn-sm btn-icon add-child" title="Add Child"><i
                            class="fe fe-plus"></i></button>
                @endif

                <button type="button" class="btn btn-outline-info btn-sm btn-icon edit-node" title="Edit"><i
                        class="fe fe-edit-2"></i></button>
                <button type="button" class="btn btn-outline-success btn-sm btn-icon save-node d-none" title="Save"><i
                        class="fe fe-check"></i></button>
                <button type="button" class="btn btn-outline-secondary btn-sm btn-icon cancel-edit d-none"
                    title="Cancel"><i class="fe fe-x"></i></button>
                <button type="button" class="btn btn-outline-danger btn-sm btn-icon delete-node" title="Delete"><i
                        class="fe fe-trash-2"></i></button>
            </div>

        </div>
    </div>

    <div class="org-collapse">
        <div class="employee-wrapper">
            @foreach($node->employees as $employee)
                <div class="card employee-item flex-row align-items-center mb-2" data-user-id="{{ $employee->user_id }}"
                    data-jabatan-id="{{ $employee->jabatan_id }}" data-is-head="{{ $employee->is_head ? '1' : '0' }}">

                    <div class="employee-avatar">
                        @if($employee->user?->ownProfile?->avatar)
                            <img src="{{ Storage::url($employee->user->ownProfile->avatar) }}"
                                alt="{{ $employee->user->name }}">
                        @else
                            {{ strtoupper(substr($employee->user?->name ?? 'U', 0, 2)) }}
                        @endif
                    </div>

                    <div class="employee-info ms-2 flex-grow-1">
                        <div class="employee-name">{{ $employee->user?->name }}</div>
                        <div class="employee-position">
                            <span>{{ $employee->jabatan?->name }}</span>
                            @if($employee->is_head)
                                <span class="badge bg-warning text-dark head-badge ms-1"><i class="fe fe-star"></i> Head</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-1 pe-2">
                        <button type="button" class="btn btn-sm btn-light edit-employee-btn"><i
                                class="fe fe-edit-2 text-info"></i></button>
                        <button type="button" class="btn btn-sm btn-light remove-employee"><i
                                class="fe fe-trash-2 text-danger"></i></button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="org-children">
            @foreach($node->children as $child)
                @include('organization.partials.node', ['node' => $child])
            @endforeach
        </div>
    </div>
</div>