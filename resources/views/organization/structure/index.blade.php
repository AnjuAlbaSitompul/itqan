@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4 org-page">

        <div class="card shadow-sm mb-4 org-toolbar">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h3 class="card-title mb-1 fw-bold">Organization Structure</h3>
                    <p class="card-text text-muted small mb-0">Manage organization hierarchy and manpower allocation.</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary rounded-pill px-4" id="addRootDivision">
                        <i class="fe fe-plus me-1"></i> Division
                    </button>
                    <button class="btn btn-success rounded-pill px-4" id="saveTree">
                        <i class="fe fe-save me-1"></i> Save
                    </button>
                </div>
            </div>
        </div>

        <div class="org-tree-wrapper">
            <div id="organization-tree">
                @foreach($organizations as $organization)
                    @include('organization.partials.node', [
                        'node' => $organization
                    ])
                @endforeach
                </div>
        </div>

        </div>
@endsection
@push('styles')
    @include('organization.structure.components.styles')

@endpush
@push('scripts')
       @include('organization.structure.components.scripts')
@endpush