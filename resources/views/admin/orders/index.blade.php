@extends('admin.layouts.index')

@section('title')
    Orders
@endsection
@section('style')
    <style>
    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Orders</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="orderTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Company Name</th>
                        <th>Transportation</th>
                        <th>Truck Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Id</th>
                        <th>Company Name</th>
                        <th>Transportation</th>
                        <th>Truck Name</th>
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
            $('#orderTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.orders.ajax.getDataTable') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'company',
                        name: 'company'
                    },
                    {
                        data: 'transportation',
                        name: 'transportation'
                    },
                    {
                        data: 'truck',
                        name: 'truck'
                    },
                    {
                        data: 'action',
                        name: 'action'
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
