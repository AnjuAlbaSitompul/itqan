<div class="org-node" data-id="{{ $node->id }}" data-parent-id="{{ $node->parent_id }}" data-type="{{ $node->type }}">

    <div class="org-item">

        <div class="org-main">

            @if(
                    $node->children->count() ||
                    $node->employees->count()
                )

                <button type="button" class="btn-icon toggle-node">
                    <i class="fe fe-chevron-right"></i>
                </button>

            @else

                <button type="button" class="toggle-node btn btn-sm btn-light">

                    <i class="fe fe-chevron-right"></i>

                </button>

            @endif

            <div class="org-content">

                <div class="node-view">

                    <div class="org-title node-title">
                        {{ $node->name }}
                    </div>

                    <div class="org-meta">

                        <span class="org-badge">
                            {{ ucfirst(str_replace('_', ' ', $node->type)) }}
                        </span>

                        <span class="org-stat">
                            MP:
                            <span class="manpower-count">
                                {{ $node->man_power }}
                            </span>
                        </span>

                        <span class="org-stat">
                            EMP:
                            <span class="employee-count">
                                {{ $node->employees->count() }}
                            </span>
                        </span>

                    </div>

                </div>

                <div class="node-edit d-none">

                    <div class="row g-2">

                        <div class="col-md-8">

                            <input type="text" class="form-control form-control-sm node-name" value="{{ $node->name }}">

                        </div>

                        <div class="col-md-4">

                            <input type="number" class="form-control form-control-sm node-man-power"
                                value="{{ $node->man_power }}">

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="org-actions">

            <button type="button" class="btn-icon manage-employee">
                <i class="fe fe-users"></i>
            </button>

            @if($node->type != 'sub_unit')

                <button type="button" class="btn-icon add-child">
                    <i class="fe fe-plus"></i>
                </button>

            @endif

            @if($node->type == 'unit')

                <select class="form-select form-select-sm node-outlet select2-outlet">
                    <option value="">
                        General Unit (No Outlet)
                    </option>

                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ $node->outlet_id == $outlet->id ? 'selected' : '' }}>
                            {{ $outlet->name }}
                        </option>
                    @endforeach

                </select>

            @endif

            <button type="button" class="btn-icon edit-node">
                <i class="fe fe-edit"></i>
            </button>

            <button type="button" class="btn-icon save-node d-none">
                <i class="fe fe-check"></i>
            </button>

            <button type="button" class="btn-icon cancel-edit d-none">
                <i class="fe fe-x"></i>
            </button>

            <button type="button" class="btn-icon danger delete-node">
                <i class="fe fe-trash"></i>
            </button>

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

                    <div>

                        <div class="employee-name">
                            {{ $employee->user?->name }}
                        </div>

                        <div class="employee-position">

                            {{ $employee->jabatan?->name }}

                            @if($employee->is_head)

                                <span class="head-badge">
                                    Head
                                </span>

                            @endif

                        </div>

                    </div>

                    <button type="button" class="btn btn-sm btn-light remove-employee">
                        <i class="fe fe-trash-2"></i>
                    </button>

                </div>

            @endforeach

        </div>

        <div class="org-children">

            @foreach($node->children as $child)

                @include(
                    'organization.partials.node',
                    ['node' => $child]
                )

            @endforeach

        </div>

    </div>

</div>