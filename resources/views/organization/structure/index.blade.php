@extends('layouts.app')

@section('content')
    <div class="org-page">

        <div class="org-toolbar">
            <div>
                <h3>Organization Structure</h3>
                <p>Manage organization hierarchy and manpower allocation.</p>
            </div>

            <div class="org-toolbar-actions">
                <button class="btn btn-primary shadow-sm rounded-pill px-4" id="addRootDivision">
                    <i class="fe fe-plus me-1"></i> Division
                </button>
                <button class="btn btn-success shadow-sm rounded-pill px-4" id="saveTree">
                    <i class="fe fe-save me-1"></i> Save
                </button>
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

        @include('organization.structure.components.options')
@endsection
@push('styles')
    @include('organization.structure.components.styles')
@endpush
@push('scripts')
    @include('organization.structure.components.scripts')
@endpush