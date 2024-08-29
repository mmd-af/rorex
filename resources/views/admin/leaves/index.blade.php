@extends('admin.layouts.index')

@section('title')
    Leaves
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Leaves</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter Leaves</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.leaves.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="from_date" class="form-label">From Date:</label>
                        <input type="date" name="from_date" id="from_date" class="form-control"
                            value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="to_date" class="form-label">To Date:</label>
                        <input type="date" name="to_date" id="to_date" class="form-control"
                            value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="user_id" class="form-label">User:</label>
                        <select name="user_id" id="user_id" class="form-select">
                            <option value="">--Select User--</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->employee->staff_code }} - {{ $user->employee->last_name }}
                                    {{ $user->employee->first_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn btn-primary btn-lg me-3">Search</button>
                        <a href="{{ route('admin.leaves.export', ['format' => 'pdf'] + request()->query()) }}"
                            class="btn btn-info btn-sm me-2">Export as PDF <i class="fa-solid fa-file-pdf fa-xs"></i></a>
                        <a href="{{ route('admin.leaves.export', ['format' => 'excel'] + request()->query()) }}"
                            class="btn btn-info btn-sm">Export as Excel <i class="fa-solid fa-file-csv fa-xs"></i></a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    @if ($leaves->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Leave Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Tracking Number</th>
                                <th>User ID</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Type</th>
                                <th>Leave Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->id }}/{{ $leave->request_id }}</td>
                                    <td>{{ $leave->users->employee->last_name }} {{ $leave->users->employee->first_name }}
                                    </td>
                                    <td>{{ $leave->formatted_start_date }}</td>
                                    <td>{{ $leave->formatted_end_date }}</td>
                                    <td>{{ $leave->type }}</td>
                                    <td>{{ $leave->formatted_leave_value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mt-4" role="alert">
            No records found.
        </div>
    @endif

@endsection

@section('script')
@endsection
