@extends('admin.layouts.index')

@section('title')
    Leave Control
@endsection
@section('style')
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Leave Control</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="userTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Staff Code</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Leave Balance (Hour)</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Staff Code</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Leave Balance (Hour)</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.manageStaffLeaves.ajax.getDataTable') }}",
                columns: [{
                        data: 'staff_code',
                        name: 'staff_code',
                        width: '10%'
                    },
                    {
                        data: 'last_name',
                        name: 'last_name',
                        width: '20%'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name',
                        width: '20%'
                    },
                    {
                        data: 'leave_balance',
                        name: 'leave_balance',
                        width: '20%'
                    }
                ],
                initComplete: function() {
                    var table = this;

                    this.api().columns().every(function() {
                        var column = this;
                        var header = $(column.header());

                        var filterRow = header.closest('thead').find('.filter-row');

                        if (!filterRow.length) {
                            filterRow = $('<tr class="filter-row"></tr>').appendTo(header
                                .closest('thead'));
                        }

                        var input = $(
                                '<input type="text" class="form-control form-control-sm" placeholder="Search...">'
                                )
                            .appendTo($('<th></th>').appendTo(filterRow))
                            .on('keyup change', function() {
                                if (column.search() !== this.value) {
                                    column
                                        .search(this.value)
                                        .draw();
                                }
                            });
                    });
                }
            });
        });
    </script>
@endsection
