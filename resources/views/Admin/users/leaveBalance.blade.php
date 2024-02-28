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
                    <th>Cod Staff</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Leave Balance</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Cod Staff</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Leave Balance</th>
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
        $(document).ready(function () {
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.users.ajax.getLeaveBalanceData') }}",
                columns: [
                    {data: 'cod_staff', name: 'cod_staff',width:'10%'},
                    {data: 'prenumele_tatalui', name: 'prenumele_tatalui',width:'20%'},
                    {data: 'name', name: 'name',width:'20%'},
                    {data: 'leave_balance', name: 'leave_balance',width:'20%'}
                ],
                initComplete: function () {
                    var table = this;

                    this.api().columns().every(function () {
                        var column = this;
                        var header = $(column.header());

                        var filterRow = header.closest('thead').find('.filter-row');

                        if (!filterRow.length) {
                            filterRow = $('<tr class="filter-row"></tr>').appendTo(header.closest('thead'));
                        }

                        var input = $('<input type="text" class="form-control form-control-sm" placeholder="Search...">')
                            .appendTo($('<th></th>').appendTo(filterRow))
                            .on('keyup change', function () {
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

