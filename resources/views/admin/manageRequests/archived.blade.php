@extends('admin.layouts.index')

@section('title')
    Support
@endsection
@section('style')
    <style>
        #box {
            border: 2px solid black;
            padding: 2px;
        }
    </style>
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
                        <th>Requests</th>
                        <th>File</th>
                        <th>Progress</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Requests</th>
                        <th>File</th>
                        <th>Progress</th>
                        <th>Action</th>
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
            $('#supportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.manageRequests.ajax.getArchiveDataTable') }}",
                columns: [{
                        data: 'requests',
                        name: 'requests',
                        width: '50%'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        width: '10%'
                    },
                    {
                        data: 'progress',
                        name: 'progress',
                        width: '20%'
                    },
                    {
                        data: 'action',
                        name: 'action',
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
