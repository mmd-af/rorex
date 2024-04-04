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
                        <th>action</th>
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
                        <th>action</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>

    <div class="modal fade" id="editFormModal" tabindex="-1" aria-labelledby="editFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFormModalLabel">Edit Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="alert">
                        <div class="row justify-content-center my-3">
                            <div class="spinner-grow text-primary mx-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-secondary mx-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="row justify-content-center my-3">
                            <div class="spinner-grow text-secondary mx-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="spinner-grow text-primary mx-3" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <form action="" method="post" id="editFrom">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="nume_schimb" class="form-label">Nume Schimb</label>
                            <input type="text" class="form-control" id="nume_schimb" name="nume_schimb" value=""
                                disabled>
                        </div>
                        <div class="mb-3">
                            <label for="on_work1" class="form-label">On Work 1</label>
                            <input type="text" class="form-control" id="on_work1" name="on_work1" value="">
                        </div>
                        <div class="mb-3">
                            <label for="off_work1" class="form-label">Off Work 1</label>
                            <input type="text" class="form-control" id="off_work1" name="off_work1" value="">
                        </div>
                        <div class="mb-3">
                            <label for="on_work2" class="form-label">On Work 2</label>
                            <input type="text" class="form-control" id="on_work2" name="on_work2" value="">
                        </div>
                        <div class="mb-3">
                            <label for="off_work2" class="form-label">Off Work 2</label>
                            <input type="text" class="form-control" id="off_work2" name="off_work2" value="">
                        </div>
                        <div class="mb-3">
                            <label for="on_work3" class="form-label">On Work 3</label>
                            <input type="text" class="form-control" id="on_work3" name="on_work3" value="">
                        </div>
                        <div class="mb-3">
                            <label for="off_work3" class="form-label">Off Work 3</label>
                            <input type="text" class="form-control" id="off_work3" name="off_work3" value="">
                        </div>
                        <div class="mb-3">
                            <label for="absenta_zile" class="form-label">Absenta Zile</label>
                            <input type="text" class="form-control" id="absenta_zile" name="absenta_zile"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="munca_ore" class="form-label">Munca Ore</label>
                            <input type="text" class="form-control" id="munca_ore" name="munca_ore" value="">
                        </div>
                        <div class="mb-3">
                            <label for="ot_ore" class="form-label">OT Ore</label>
                            <input type="text" class="form-control" id="ot_ore" name="ot_ore" value="">
                        </div>
                        <div class="mb-3">
                            <label for="plus_week_day" class="form-label">Plus Week Day</label>
                            <input type="text" class="form-control" id="plus_week_day" name="plus_week_day"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="plus_week_night" class="form-label">Plus Week Night</label>
                            <input type="text" class="form-control" id="plus_week_night" name="plus_week_night"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="plus_holiday_day" class="form-label">Plus Holiday Day</label>
                            <input type="text" class="form-control" id="plus_holiday_day" name="plus_holiday_day"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="plus_holiday_night" class="form-label">Plus Holiday Night</label>
                            <input type="text" class="form-control" id="plus_holiday_night" name="plus_holiday_night"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="tarziu_minute" class="form-label">Tarziu Minute</label>
                            <input type="text" class="form-control" id="tarziu_minute" name="tarziu_minute"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="devreme_minute" class="form-label">Devreme Minute</label>
                            <input type="text" class="form-control" id="devreme_minute" name="devreme_minute"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="lipsa_ceas_timpi" class="form-label">Lipsa Ceas Timpi</label>
                            <input type="text" class="form-control" id="lipsa_ceas_timpi" name="lipsa_ceas_timpi"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="concediu_ore" class="form-label">Concediu Ore</label>
                            <input type="text" class="form-control" id="concediu_ore" name="concediu_ore"
                                value="">
                        </div>
                        <div class="mb-3">
                            <label for="remarca" class="form-label">Report</label>
                            <input type="text" class="form-control" id="remarca" name="remarca" value="">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
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
                    },
                    {
                        data: 'edit',
                        name: 'action'
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

        let editFrom = document.getElementById('editFrom');
        editFrom.style.visibility = 'hidden';
        function openEditFormModal(id) {
            let alert = document.getElementById('alert');
            let nume_schimb = document.getElementById('nume_schimb');
            let on_work1 = document.getElementById('on_work1');
            let off_work1 = document.getElementById('off_work1');
            let on_work2 = document.getElementById('on_work2');
            let off_work2 = document.getElementById('off_work2');
            let on_work3 = document.getElementById('on_work3');
            let off_work3 = document.getElementById('off_work3');
            let absenta_zile = document.getElementById('absenta_zile');
            let munca_ore = document.getElementById('munca_ore');
            let ot_ore = document.getElementById('ot_ore');
            let plus_week_day = document.getElementById('plus_week_day');
            let plus_week_night = document.getElementById('plus_week_night');
            let plus_holiday_day = document.getElementById('plus_holiday_day');
            let plus_holiday_night = document.getElementById('plus_holiday_night');
            let tarziu_minute = document.getElementById('tarziu_minute');
            let devreme_minute = document.getElementById('devreme_minute');
            let lipsa_ceas_timpi = document.getElementById('lipsa_ceas_timpi');
            let concediu_ore = document.getElementById('concediu_ore');
            let remarca = document.getElementById('remarca');
            let configInformation = {
                id: id
            }
            axios.post('{{ route('admin.dailyReports.ajax.getData') }}', configInformation)
                .then(function(response) {
                    alert.innerHTML = ``;
                    nume_schimb.value = response.data.data.nume_schimb;
                    on_work1.value = response.data.data.on_work1;
                    off_work1.value = response.data.data.off_work1;
                    on_work2.value = response.data.data.on_work2;
                    off_work2.value = response.data.data.off_work2;
                    on_work3.value = response.data.data.on_work3;
                    off_work3.value = response.data.data.off_work3;
                    absenta_zile.value = response.data.data.absenta_zile;
                    munca_ore.value = response.data.data.munca_ore;
                    ot_ore.value = response.data.data.ot_ore;
                    plus_week_day.value = response.data.data.plus_week_day;
                    plus_week_night.value = response.data.data.plus_week_night;
                    plus_holiday_day.value = response.data.data.plus_holiday_day;
                    plus_holiday_night.value = response.data.data.plus_holiday_night;
                    tarziu_minute.value = response.data.data.tarziu_minute;
                    devreme_minute.value = response.data.data.devreme_minute;
                    lipsa_ceas_timpi.value = response.data.data.lipsa_ceas_timpi;
                    concediu_ore.value = response.data.data.concediu_ore;
                    remarca.value = response.data.data.remarca;
                    editFrom.style.visibility = 'visible';

                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    </script>
@endsection
