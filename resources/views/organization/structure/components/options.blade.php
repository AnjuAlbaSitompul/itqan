<script>

    window.userOptions = `
<option value="">Select User</option>

@foreach($users as $user)
    <option value="{{ $user->id }}">
        {{ $user->name }}
    </option>
@endforeach
`;

    window.outletOptions = `
        <option value="">
            General Unit (No Outlet)
        </option>

        @foreach($outlets as $outlet)
            <option value="{{ $outlet->id }}">
                {{ $outlet->name }}
            </option>
        @endforeach
    `;

    window.jabatanOptions = `
<option value="">Select Position</option>

@foreach($jabatans as $jabatan)
    <option value="{{ $jabatan->id }}">
        {{ $jabatan->name }}
    </option>
@endforeach
`;

</script>