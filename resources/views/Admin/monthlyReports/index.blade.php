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
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>cod_staff</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalReportBody">
                    <form>
                        @csrf
                        <label for="date">Select Date:</label>
                        <select id="date" name="date" class="form-control">
                            <?php
                            $currentMonth = date('n');
                            $currentYear = date('Y');
                            for ($i = 0; $i <= 12; $i++) {
                                $monthValue = ($currentMonth - $i + 12) % 12 + 1;
                                $yearValue = $currentYear + floor(($currentMonth - $i) / 12);
                                $formattedMonth = sprintf('%02d', $monthValue);
                                $dateOutput = "$yearValue-$formattedMonth";
                                $monthName = date("F", mktime(0, 0, 0, $monthValue, 1, $yearValue));
                                echo "<option value=\"$dateOutput\">$monthName $yearValue</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="cod_staff" id="cod_staff" value="">
                        <button type="button" class="btn btn-primary mt-3" onclick="monthlyReportWithDate()">Show
                        </button>
                    </form>
                    <div id="showResult"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
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
                    {data: 'name', name: 'name'},
                    {"data": "button", "name": "button", "orderable": false, "searchable": false}
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

        function showReportModal(id) {
            $('#reportModal').modal('show');
            let cod_staff = document.getElementById('cod_staff');
            cod_staff.value = id;
        }

        function monthlyReportWithDate() {
            let showResult = document.getElementById('showResult');
            var codStaff = document.getElementById('cod_staff').value;
            var date = document.getElementById('date').value;
            showResult.innerHTML = `
                    <div class="row justify-content-center">
                        <div class="spinner-grow text-primary m-3 p-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="spinner-grow text-secondary m-3 p-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="spinner-grow text-secondary m-3 p-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="spinner-grow text-primary m-3 p-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`;
            formData = {
                cod_staff: codStaff,
                date: date
            }
            axios.post('{{route('admin.monthlyReports.ajax.monthlyReportWithDate')}}', formData)
                .then(function (response) {
                    // console.log(response.data);
                    showResult.innerHTML=`
                    <div class="row justify-content-center mt-5">
                        <div class="row border border-3">
                            <div class="col-sm-6 border"><strong>Total night hours:</strong></div>
                            <div class="col-sm-6 border"><h6>${response.data}</h6></div>
                        </div>
                    </div>`;
                })
                .catch(function (error) {
                    console.error(error);
                });
        }
        $(document).on('hidden.bs.modal', function () {
            location.reload(true);
        });
    </script>
@endsection

