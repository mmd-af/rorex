@extends('admin.layouts.index')

@section('title')
    Daily Reports
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Daily Reports</li>
    </ol>
    <div class="card mb-4">
        @include('admin.layouts.partial.errors')
        <div class="card-body">
            <table id="dailyReportTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>cod_staff</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work2</th>
                    <th>remarca</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>cod_staff</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Weeks</th>
                    <th>Shift</th>
                    <th>on_work1</th>
                    <th>off_work2</th>
                    <th>remarca</th>
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
            $('#dailyReportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.dailyReports.ajax.getDataTable') }}",
                columns: [
                    {data: 'cod_staff', name: 'cod_staff'},
                    {data: 'nume', name: 'nume'},
                    {data: 'data', name: 'data'},
                    {data: 'saptamana', name: 'saptamana'},
                    {data: 'nume_schimb', name: 'nume_schimb'},
                    {data: 'on_work1', name: 'on_work1'},
                    {data: 'off_work2', name: 'off_work2'},
                    {data: 'remarca', name: 'remarca'}
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

