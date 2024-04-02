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
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="row">
            <div class="col-sm-12 col-md-6 bg-warning">
                <form class="form-control bg-warning" action="{{ route('admin.dailyReports.import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="file">Update Daily Reports <small>support: xlsx,xls</small></label>
                        <input type="file" name="file" id="file" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary mt-2">Import</button>
                </form>
            </div>
            <div class="col-sm-12 col-md-6 bg-danger-subtle">
                <strong>Important:</strong>
                <ol>
                    <li>The first row of the file is not added because it is the name of the column.</li>
                    <li>Only Sheet1 will be uploaded.</li>
                    <li>Duplicate data <strong>is updated</strong></li>
                    <li>Be patient while uploading until the operation is finished and the page is refreshed.</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <form class="form-control px-5">
                @csrf
                <label for="date">Select Date:</label>
                <select id="date" name="date" class="form-control">
                    <?php
                    $currentMonth = date('n');
                    $currentYear = date('Y');
                    for ($i = 1; $i <= 12; $i++) {
                        $monthValue = (($currentMonth - $i + 12) % 12) + 1;
                        $yearValue = $currentYear + floor(($currentMonth - $i) / 12);
                        if ($monthValue > $currentMonth) {
                            $yearValue--;
                        }
                        $formattedMonth = sprintf('%02d', $monthValue);
                        $dateOutput = "$yearValue-$formattedMonth";
                        $monthName = date('F', mktime(0, 0, 0, $monthValue, 1, $yearValue));
                        echo "<option value=\"$dateOutput\">$monthName $yearValue</option>";
                    }
                    ?>
                </select>
                <button type="button" class="btn btn-primary mt-3" onclick="monthlyReportWithDate()">Show
                </button>
            </form>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="dailyReportTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>cod_staff</th>
                        <th>Name</th>
                        <th>First Name</th>
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
                        <th>First Name</th>
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
        function monthlyReportWithDate() {
            let date = document.getElementById('date').value;
            let csrfToken = $('meta[name="csrf-token"]').attr('content');
            $('#dailyReportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                destroy: true,
                ajax: {
                    url: "{{ route('admin.dailyReports.ajax.getDataTable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        date: date
                    }
                },
                columns: [{
                        data: 'cod_staff',
                        name: 'cod_staff'
                    },
                    {
                        data: 'nume',
                        name: 'nume'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        data: 'data',
                        name: 'data'
                    },
                    {
                        data: 'saptamana',
                        name: 'saptamana'
                    },
                    {
                        data: 'nume_schimb',
                        name: 'nume_schimb'
                    },
                    {
                        data: 'on_work1',
                        name: 'on_work1'
                    },
                    {
                        data: 'off_work2',
                        name: 'off_work2'
                    },
                    {
                        data: 'remarca',
                        name: 'remarca'
                    }
                ],
                initComplete: function() {
                    var table = this;
                    $('.filter-row').empty();
                    this.api().columns().every(function() {
                        var column = this;
                        var header = $(column.header());

                        var filterRow = header.closest('thead').find('.filter-row');

                        if (!filterRow.length) {
                            filterRow = $('<tr class="filter-row"></tr>').appendTo(header.closest(
                                'thead'));
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
        }
    </script>
@endsection
