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
        <div class="card-body">
            <table id="supportTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Tracking Number</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>organization</th>
                    <th>Read By</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Tracking Number</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>organization</th>
                    <th>Read By</th>
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
        $(document).ready(function () {
            $('#supportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.supports.ajax.getDataTable') }}",
                columns: [
                    {data: 'id', name: 'id', width: '10%'},
                    {data: 'name', name: 'subject'},
                    {data: 'subject', name: 'subject'},
                    {data: 'organization', name: 'organization', width: '10%'},
                    {data: 'read_by', name: 'read_by', width: '10%'},
                    {data: 'button', name: 'button', orderable: false, searchable: false, width: '10%'}
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

        function showMessageModal(id) {
            alert(id)
        }
    </script>
@endsection

