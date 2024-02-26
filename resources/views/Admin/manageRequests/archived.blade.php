@extends('admin.layouts.index')

@section('title')
    Support
@endsection
@section('style')

@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Support</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="supportTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Date of Request</th>
                    <th>User Name</th>
                    <th>Requests</th>
                    <th>Sign</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Date of Request</th>
                    <th>User Name</th>
                    <th>Requests</th>
                    <th>Sign</th>
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
            $('#supportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.manageRequests.ajax.getArchiveDataTable') }}",
                columns: [
                    {data: 'created_at', name: 'created_at', width: '10%'},
                    {data: 'user', name: 'user', width: '10%'},
                    {data: 'requests', name: 'requests', width: '60%'},
                    {data: 'status', name: 'status', width: '10%'}
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

