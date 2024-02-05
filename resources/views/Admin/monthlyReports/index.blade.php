@extends('admin.layouts.index')

@section('title')
    Monthly Reports
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Monthly Reports</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body">
            <table id="monthlyReportTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>cod_staff</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>cod_staff</th>
                    <th>Name</th>
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
            $('#monthlyReportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.monthlyReports.ajax.getUserTable') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'cod_staff', name: 'cod_staff'},
                    {data: 'name', name: 'name'}
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

