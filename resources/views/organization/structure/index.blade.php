@extends('layouts.app')

@section('content')

    <div class="org-page">

        <div class="org-toolbar">

            <div>
                <h3>Organization Structure</h3>
                <p>
                    Manage organization hierarchy and manpower allocation.
                </p>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary" id="addRootDivision">
                    <i class="fe fe-plus"></i>
                    Division
                </button>

                <button class="btn btn-success" id="saveTree">
                    <i class="fe fe-save"></i>
                    Save
                </button>
            </div>

        </div>

        <div id="organization-tree">

            @foreach($organizations as $organization)
                @include('organization.partials.node', [
                    'node' => $organization
                ])
            @endforeach

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