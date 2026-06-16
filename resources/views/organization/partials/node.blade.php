<div class="org-node" data-id="{{ $node->id }}" data-parent-id="{{ $node->parent_id }}" data-type="{{ $node->type }}">
    <div class="org-item">
        <div class="org-main">

            @if($node->children->count() || $node->employees->count())
                <button type="button" class="btn-icon toggle-node">
                    <i class="fe fe-chevron-right"></i>
                </button>
            @else
                <button type="button" class="btn-icon toggle-node empty-node">
                    <i class="fe fe-minus"></i>
                </button>
            @endif

            <div class="org-content">
                <div class="node-view">
                    <div class="org-title node-title">
                        {{ $node->name }}
                    </div>
                    <div class="org-meta">
                        <span class="org-badge badge-{{ strtolower($node->type) }}">
                            {{ ucfirst(str_replace('_', ' ', $node->type)) }}
                        </span>
                        <span class="org-stat">
                            <i class="fe fe-target me-1"></i> MP: <strong class="manpower-count">{{ $node->man_power }}</strong>
                        </span>
                        <span class="org-stat">
                            <i class="fe fe-users me-1"></i> EMP: <strong class="employee-count">{{ $node->employees->count() }}</strong>
                        </span>
                    </div>
                </div>

                <div class="node-edit d-none">
                    <div class="row g-2 align-items-center">
                        <div class="col-12 col-md-4">
                            <input type="text" class="form-control node-name" value="{{ $node->name }}"
                                placeholder="Node Name">
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted">MP</span>
                                <input type="number" class="form-control node-man-power" value="{{ $node->man_power }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-5 mt-2 mt-md-0">
                            <select class="form-select node-outlet select2-outlet">
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

        <div class="org-actions">
            <button type="button" class="btn-icon manage-employee" title="Manage Employees"><i
                    class="fe fe-users"></i></button>

            @if($node->type != 'sub_unit')
                <button type="button" class="btn-icon add-child primary-soft" title="Add Child"><i
                        class="fe fe-plus"></i></button>
            @endif

            <button type="button" class="btn-icon edit-node" title="Edit"><i class="fe fe-edit-2"></i></button>
            <button type="button" class="btn-icon save-node success-soft d-none" title="Save"><i
                    class="fe fe-check"></i></button>
            <button type="button" class="btn-icon cancel-edit secondary-soft d-none" title="Cancel"><i
                class="fe fe-x"></i></button>
            <button type="button" class="btn-icon danger delete-node" title="Delete"><i
                    class="fe fe-trash-2"></i></button>
        </div>
    </div>

    <div class="org-collapse">
        <div class="employee-wrapper">
            @foreach($node->employees as $employee)
                <div class="employee-item" data-user-id="{{ $employee->user_id }}"
                    data-jabatan-id="{{ $employee->jabatan_id }}" data-is-head="{{ $employee->is_head }}">
                    <div class="employee-avatar">
                        {{ strtoupper(substr($employee->user?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="employee-info">
                        <div class="employee-name">{{ $employee->user?->name }}</div>
                        <div class="employee-position">
                            {{ $employee->jabatan?->name }}
                            @if($employee->is_head)
                                <span class="head-badge"><i class="fe fe-star"></i> Head</span>
                            @endif
                        </div>
                    </div>
                    <button type="button" class="btn-remove-emp remove-employee"><i class="fe fe-x"></i></button>
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