@extends('admin.layouts.index')

@section('title')
    Manage Requests
@endsection
@section('style')

@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Manage Requests</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="manageRequestTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Date of Request</th>
                    <th>Requests</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Date of Request</th>
                    <th>Requests</th>
                    <th>Status</th>
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
            $('#manageRequestTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.manageRequests.ajax.getDataTable') }}",
                columns: [
                    {data: 'created_at', name: 'created_at', width: '10%'},
                    {data: 'request_id', name: 'request_id'},
                    {data: 'status', name: 'status', width: '20%'}
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
