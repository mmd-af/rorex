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
                    $startMonth = 3;
                    $startYear = 2024;
                    $currentMonth = date('n');
                    $currentYear = date('Y');                
                    $totalMonths = ($currentYear - $startYear) * 12 + ($currentMonth - $startMonth + 1);
                    for ($i = $totalMonths - 1; $i >= 0; $i--) {
                        $monthValue = ($startMonth + $i - 1) % 12 + 1;
                        $yearValue = $startYear + floor(($startMonth + $i - 1) / 12);
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
    <div id="responseMessage"></div>
    <div id="warningMessage"></div>
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
                        <th>edit_by</th>
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
                        <th>edit_by</th>
                        <th>action</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>

    <div class="modal fade" id="checkRelatedLetterModal" tabindex="-1" aria-labelledby="checkRelatedLetterModal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkRelatedLetterModal">Edit Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="checkRelatedLetterAlert">
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
                </div>
                <div class="modal-footer justify-content-between">
                    <p class="text-danger">
                        *** This section searches based on the specified date in the letters and displays the results
                    </p>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFormModal" tabindex="-1" aria-labelledby="editFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFormModalLabel">Edit Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="alert">
                    </div>
                    <form action="" method="post" id="editFrom">
                        @csrf
                        <input type="hidden" name="reportID" id="reportID" value="">
                        <div class="mb-3">
                            <label for="nume_schimb" class="form-label">Nume Schimb</label>
                            <select class="form-control" name="nume_schimb" id="nume_schimb"
                                onchange="changeNumeSchimb(event)">
                                <option value="Morning">Morning</option>
                                <option value="Afternoon">Afternoon</option>
                                <option value="Night">Night</option>
                                <option value="Daily">Daily</option>
                                <option value="Daily-Reduce">Daily-Reduce</option>
                                <option value="OverTime">OverTime</option>
                                <option value="Leave">Leave</option>
                                <option value="Without Paid Leave">Without Paid Leave</option>
                                <option value="Odihna">Odihna</option>
                                <option value="Tura implicita">Tura implicita</option>
                                <option value="No Join">No Join</option>
                            </select>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-borderless align-middle">
                                <tbody class="table-group-divider">
                                    <tr>
                                        <td><label for="on_work1" class="form-label">On Work 1</label></td>
                                        <td class="bg-secondary text-light"><label for="off_work1" class="form-label">Off
                                                Work 1</label></td>
                                        <td></td>
                                        <td></td>
                                        <td class="border text-center h5">Result</td>
                                    </tr>
                                    <tr>
                                        <td><input type="time" class="form-control" id="on_work1" name="on_work1"
                                                value=""></td>
                                        <td class="bg-secondary text-light"><input type="time" class="form-control"
                                                id="off_work1" name="off_work1" value=""></td>
                                        <td></td>
                                        <td></td>
                                        <td class="border text-center h5" id="sumWork1"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="bg-secondary text-light"><label for="on_work2" class="form-label">On
                                                Work
                                                2</label></td>
                                        <td><label for="off_work2" class="form-label">Off Work 2</label></td>
                                        <td></td>
                                        <td class="border text-center h5"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="bg-secondary text-light"><input type="time" class="form-control"
                                                id="on_work2" name="on_work2" value=""></td>
                                        <td><input type="time" class="form-control" id="off_work2" name="off_work2"
                                                value=""></td>
                                        <td></td>
                                        <td class="border text-center h5" id="sumWork2"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><label for="on_work3" class="form-label">On Work 3</label></td>
                                        <td><label for="off_work3" class="form-label">Off Work 3</label></td>
                                        <td class="border text-center h5"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><input type="time" class="form-control" id="on_work3" name="on_work3"
                                                value=""></td>
                                        <td><input type="time" class="form-control" id="off_work3" name="off_work3"
                                                value=""></td>
                                        <td class="border text-center h5" id="sumWork3"></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="border text-center text-success h4" id="resultSumWork"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-auto">
                                <div class="p-3" id="fixValueWithHourButton">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-borderless align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Work Time(Hour)</th>
                                        <th class="bg-secondary text-light">OverTime(minute)</th>
                                        <th>Absente(Hour)</th>
                                    </tr>
                                </thead>
                                <tbody class="table-group-divider">
                                    <tr>
                                        <td>
                                            <label for="munca_ore" class="form-label">Munca Ore</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="munca_ore" name="munca_ore" value="" readonly>
                                        </td>
                                        <td class="bg-secondary text-light">
                                            <label for="ot_ore" class="form-label">OT Ore</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="ot_ore" name="ot_ore" value="">
                                        </td>
                                        <td>
                                            <label for="absenta_zile" class="form-label">Absenta Zile</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="absenta_zile" name="absenta_zile" value="" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="bg-secondary text-light">
                                            <label for="plus_week_day" class="form-label">Plus Week Day</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="plus_week_day" name="plus_week_day" value="">
                                        </td>
                                        <td>
                                            <label for="tarziu_minute" class="form-label">Tarziu Minute</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="tarziu_minute" name="tarziu_minute" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="bg-secondary text-light">
                                            <label for="plus_week_night" class="form-label">Plus Week Night</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="plus_week_night" name="plus_week_night" value="">
                                        </td>
                                        <td>
                                            <label for="devreme_minute" class="form-label">Devreme Minute</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="devreme_minute" name="devreme_minute" value="">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="bg-secondary text-light">
                                            <label for="plus_holiday_day" class="form-label">Plus Holiday Day</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="plus_holiday_day" name="plus_holiday_day" value="">
                                        </td>
                                        <td>
                                            <label for="lipsa_ceas_timpi" class="form-label">Lipsa Ceas Timpi</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="lipsa_ceas_timpi" name="lipsa_ceas_timpi" value="" readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="bg-secondary text-light">
                                            <label for="plus_holiday_night" class="form-label">Plus Holiday Night</label>
                                            <input type="number" min="0" step="any" class="form-control"
                                                id="plus_holiday_night" name="plus_holiday_night" value="">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-3">
                            <label for="concediu_ore" class="form-label">Concediu Ore</label>
                            <input type="number" min="0" step="any" class="form-control" id="concediu_ore"
                                name="concediu_ore" value="">
                        </div>
                        <div class="mb-3">
                            <label for="without_paid_leave" class="form-label">Without Paid Leave</label>
                            <input type="number" min="0" step="any" class="form-control"
                                id="without_paid_leave" name="without_paid_leave" value="">
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
                        data: 'edit_by',
                        name: 'edit_by'
                    },
                    {
                        data: 'action',
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
        let alert = document.getElementById('alert');
        let reportID = document.getElementById('reportID');
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
        let without_paid_leave = document.getElementById('without_paid_leave');
        let remarca = document.getElementById('remarca');
        let sumWork1 = document.getElementById('sumWork1');
        let sumWork2 = document.getElementById('sumWork2');
        let sumWork3 = document.getElementById('sumWork3');
        let resultSumWork = document.getElementById('resultSumWork');
        let fixValueWithHourButton = document.getElementById('fixValueWithHourButton');

        function openEditFormModal(id) {
            editFrom.style.visibility = 'hidden';
            alert.innerHTML = ` <div class="row justify-content-center my-3">
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
                        </div>`;
            let configInformation = {
                id: id
            }
            axios.post("{{ route('admin.dailyReports.ajax.getData') }}", configInformation)
                .then(function(response) {
                    alert.innerHTML = ``;
                    sumWork1.innerHTML = ``;
                    sumWork1.classList.remove('bg-warning');
                    sumWork2.innerHTML = ``;
                    sumWork2.classList.remove('bg-warning');
                    sumWork3.innerHTML = ``;
                    sumWork3.classList.remove('bg-warning');
                    resultSumWork.innerHTML = ``;
                    resultSumWork.classList.remove('bg-warning');
                    munca_ore.classList.remove('bg-warning');
                    let url = "{{ route('admin.dailyReports.ajax.update', 'dailyID') }}";
                    url = url.replace('dailyID', response.data.data.id);
                    editFrom.setAttribute('action', url);
                    reportID.value = response.data.data.id;
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
                    without_paid_leave.value = response.data.data.without_paid_leave;
                    remarca.value = response.data.data.remarca;
                    editFrom.style.visibility = 'visible';
                    handleTimeFieldChange();
                    fixValueWithHourButton.innerHTML = `<button type="button" class="btn btn-success p-3"
        onclick="fixValueWithHour()"><i class="fa fa-arrow-left" aria-hidden="true"></i> Auto Fix</button>`;

                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        var timeFields = document.querySelectorAll("input[type='time']");
        timeFields.forEach(function(timeField) {
            timeField.addEventListener("input", handleTimeFieldChange);
        });


        function handleTimeFieldChange(event) {
            alert.innerHTML = ` <div class="row justify-content-center my-3">
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
    </div>`;

            let configInformation = {
                on_work1: on_work1.value,
                off_work1: off_work1.value,
                on_work2: on_work2.value,
                off_work2: off_work2.value,
                on_work3: on_work3.value,
                off_work3: off_work3.value,
            }
            axios.post("{{ route('admin.dailyReports.ajax.renderTimeForm') }}", configInformation)
                .then(function(response) {
                    alert.innerHTML = ``;
                    renderTimeForm(response);
                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        function renderTimeForm(response) {
            let sum1 = parseFloat(response.data.data.original.sumWork1);
            let sum2 = parseFloat(response.data.data.original.sumWork2);
            let sum3 = parseFloat(response.data.data.original.sumWork3);
            sumWork1.innerHTML = sum1;
            sumWork1.classList.add('bg-warning');
            sumWork2.innerHTML = sum2;
            sumWork2.classList.add('bg-warning');
            sumWork3.innerHTML = sum3;
            sumWork3.classList.add('bg-warning');
            let totalSumWork = sum1 + sum2 + sum3;
            totalSumWork = totalSumWork.toFixed(2);
            resultSumWork.innerHTML = totalSumWork;
            resultSumWork.classList.add('bg-warning');
            munca_ore.classList.remove('bg-warning');
            munca_ore.classList.add('bg-danger');
            ot_ore.classList.remove('bg-warning');
            ot_ore.classList.add('bg-danger');
            absenta_zile.classList.remove('bg-warning');
            absenta_zile.classList.add('bg-danger');
            lipsa_ceas_timpi.classList.remove('bg-warning');
            lipsa_ceas_timpi.classList.add('bg-danger');
        }

        function fixValueWithHour() {
            let resultSumWork_value = parseFloat(resultSumWork.innerHTML);
            if (resultSumWork_value > 8) {
                munca_ore.value = 8;
                ot_ore.value = (resultSumWork_value - 8).toFixed(2);
                munca_ore.classList.remove('bg-danger');
                munca_ore.classList.add('bg-warning');
                ot_ore.classList.remove('bg-danger');
                ot_ore.classList.add('bg-warning');
            } else {
                munca_ore.value = resultSumWork_value;
                munca_ore.classList.remove('bg-danger');
                munca_ore.classList.add('bg-warning');
                ot_ore.value = 0;
                ot_ore.classList.remove('bg-danger');
                ot_ore.classList.add('bg-warning');
            }
            if (on_work1.value !== '' && off_work2.value !== '') {
                absenta_zile.classList.remove('bg-danger');
                absenta_zile.classList.add('bg-warning');
                absenta_zile.value = 0;
                lipsa_ceas_timpi.classList.remove('bg-danger');
                lipsa_ceas_timpi.classList.add('bg-warning');
                lipsa_ceas_timpi.value = 0;
            }
            if (on_work1.value === '' && off_work2.value !== '') {
                absenta_zile.classList.remove('bg-danger');
                absenta_zile.classList.add('bg-warning');
                absenta_zile.value = 0.5;
                lipsa_ceas_timpi.classList.remove('bg-danger');
                lipsa_ceas_timpi.classList.add('bg-warning');
                lipsa_ceas_timpi.value = 1;
            }
            if (on_work1.value !== '' && off_work2.value === '') {
                absenta_zile.classList.remove('bg-danger');
                absenta_zile.classList.add('bg-warning');
                absenta_zile.value = 0.5;
                lipsa_ceas_timpi.classList.remove('bg-danger');
                lipsa_ceas_timpi.classList.add('bg-warning');
                lipsa_ceas_timpi.value = 1;
            }
            if (on_work1.value === '' && off_work2.value === '') {
                absenta_zile.classList.remove('bg-danger');
                absenta_zile.classList.add('bg-warning');
                absenta_zile.value = 1;
                lipsa_ceas_timpi.classList.remove('bg-danger');
                lipsa_ceas_timpi.classList.add('bg-warning');
                lipsa_ceas_timpi.value = 2;
            }

            plus_week_day.classList.remove('bg-danger');
            plus_week_night.classList.remove('bg-danger');
            plus_holiday_day.classList.remove('bg-danger');
            plus_holiday_night.classList.remove('bg-danger');
            plus_week_day.value = 0;
            plus_week_night.value = 0;
            plus_holiday_day.value = 0;
            plus_holiday_night.value = 0;
        }

        var numberFields = document.querySelectorAll("input[type='number']");
        numberFields.forEach(function(numberField) {
            numberField.addEventListener("input", handleNumberFieldChange);
        });

        function handleNumberFieldChange(event) {
            var resultSumWork_value = parseFloat(resultSumWork.innerHTML);
            alert.innerHTML = ``;
            if (event.target.name === "ot_ore") {
                munca_ore.value = resultSumWork_value;
                plus_week_day.classList.remove('bg-danger');
                plus_week_night.classList.remove('bg-danger');
                plus_holiday_day.classList.remove('bg-danger');
                plus_holiday_night.classList.remove('bg-danger');
                plus_week_day.value = 0;
                plus_week_night.value = 0;
                plus_holiday_day.value = 0;
                plus_holiday_night.value = 0;
                calculateMuncaOre(munca_ore, event)
            }
            if (event.target.name === "ot_ore" || event.target.name === "plus_week_day" || event.target.name ===
                "plus_week_night" || event.target.name ===
                "plus_holiday_day" || event.target.name ===
                "plus_holiday_night") {
                checkOtOre(event)
            }
        }

        function calculateMuncaOre(munca_ore, event) {
            var munca_ore_value = parseFloat(munca_ore.value);
            var ot_ore_value = parseFloat(event.target.value);
            if (isNaN(ot_ore_value) || ot_ore_value === '' || ot_ore_value < 0) return;
            if (munca_ore_value - ot_ore_value < 0) {
                ot_ore.value = munca_ore_value;
                ot_ore_value = munca_ore_value;
            }
            munca_ore.value = (munca_ore_value - ot_ore_value).toFixed(2);
        }

        function checkOtOre(event) {
            var new_plus_week_day = parseFloat(plus_week_day.value);
            var new_plus_week_night = parseFloat(plus_week_night.value);
            var new_plus_holiday_day = parseFloat(plus_holiday_day.value);
            var new_plus_holiday_night = parseFloat(plus_holiday_night.value);
            var new_ot_ore = parseFloat(ot_ore.value);
            if (isNaN(new_ot_ore)) {
                ot_ore.classList.remove('bg-warning');
                ot_ore.classList.add('bg-danger');
                return;
            } else {
                ot_ore.classList.remove('bg-danger');
                ot_ore.classList.add('bg-warning');
            }
            var sum = new_plus_week_day +
                new_plus_week_night + new_plus_holiday_day + new_plus_holiday_night;
            if (sum > new_ot_ore) {
                plus_week_day.classList.add('bg-danger');
                plus_week_night.classList.add('bg-danger');
                plus_holiday_day.classList.add('bg-danger');
                plus_holiday_night.classList.add('bg-danger');
                event.target.value = 0;
            } else {
                plus_week_day.classList.remove('bg-danger');
                plus_week_day.classList.add('bg-warning');
                plus_week_night.classList.remove('bg-danger');
                plus_week_night.classList.add('bg-warning');
                plus_holiday_day.classList.remove('bg-danger');
                plus_holiday_day.classList.add('bg-warning');
                plus_holiday_night.classList.remove('bg-danger');
                plus_holiday_night.classList.add('bg-warning');
            }
        }

        function changeNumeSchimb(event) {
            if (event.target.value === "Leave") {
                absenta_zile.value = 0;
                lipsa_ceas_timpi.value = 0;
                concediu_ore.value = 8;
            }
            if (event.target.value === "Without Paid Leave") {
                absenta_zile.value = 0;
                lipsa_ceas_timpi.value = 0;
                without_paid_leave.value = 8;
            }
        }

        editFrom.addEventListener('submit', function(event) {
            event.preventDefault();
            let actionUrl = editFrom.getAttribute('action');
            const formData = new FormData(this);
            axios.post(actionUrl, formData)
                .then(function(response) {
                    const messageElement = document.getElementById('responseMessage');
                    const warningElement = document.getElementById('warningMessage');
                    warningElement.innerHTML =
                        `<div class="alert alert-warning">Something has changed. Please <a href="" onclick="location.reload()">refresh</a> the page</div>`;
                    if (response.data.data.original.message) {
                        $('#editFormModal').modal('hide');
                        messageElement.innerHTML =
                            `<div class="alert alert-success"> ${response.data.data.original.message}</div>`;
                        setTimeout(() => {
                            messageElement.innerHTML = '';
                        }, 3000);
                    }
                    if (response.data.data.original.error) {
                        $('#editFormModal').modal('hide');
                        messageElement.innerHTML =
                            `<div class="alert alert-danger"> ${response.data.data.original.error}</div>`;
                        setTimeout(() => {
                            messageElement.innerHTML = '';
                        }, 6000);
                    }

                })
                .catch(function(error) {
                    document.getElementById('responseMessage').innerHTML =
                        `<div class="alert alert-danger">Operation Fail</div>`;
                });
        });

        function checkRelatedLetter(id, staffCode, thisDay) {
            let checkRelatedLetterAlert = document.getElementById('checkRelatedLetterAlert');
            checkRelatedLetterAlert.innerHTML = `
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
               </div>`;
            let data = {
                id: id,
                staffCode: staffCode,
                thisDay: thisDay
            }
            axios.post("{{ route('admin.dailyReports.ajax.checkRelatedLetter') }}", data)
                .then(function(response) {
                    checkRelatedLetterAlert.innerHTML = ``;
                    response.data.data.forEach(function(item) {
                        let assignments = item.assignments;
                        let statusHtml = generateStatus(assignments);
                        checkRelatedLetterAlert.innerHTML += `
                             <div class="row">
                             <div class="col-sm-12 col-md-6">
                                <h5 class="bg-secondary text-white px-2">${item.id}</h5>
                                   ${item.description}   
                             </div>
                                <div class="col-sm-12 col-md-6">
                                    <p>${statusHtml}</p>
                                 <p>(Soon you can control your letter here)</p>
                                </div>
                         </div>`;
                    })
                })
                .catch(function(error) {
                    console.error(error);
                });

            function limitText(text, limit) {
                if (text.length > limit) {
                    return text.substring(0, limit) + '...';
                } else {
                    return text;
                }
            }

            function generateStatus(assignments) {
                let status = '';
                assignments.forEach(assignment => {                    
                    let signedStatus = assignment.signed_by ?
                        '<div class="bg-success rounded-3 text-light px-2">Signed</div>' :
                        '<div class="bg-warning rounded-3 px-2">Not signed</div>';
                    let description = '';

                    if (assignment.description) {
                        let limitedDescription = limitText(assignment.description, 45);
                        if (assignment.description !== limitedDescription) {
                            description = '<div class="bg-info rounded-3">' + limitedDescription + '<details>' +
                                '<summary>Show Full Message</summary>' + assignment.description +
                                '</details></div>';
                        } else {
                            description = '<div class="bg-info rounded-3">' + assignment.description + '</div>';
                        }
                    }
                    let employeeName = '';
                    if (assignment.assigned_to.employee.last_name && assignment.assigned_to.employee.first_name) {
                        employeeName = assignment.assigned_to.employee.last_name + ' ' + assignment.assigned_to.employee.first_name;
                    } else {
                        employeeName = 'Unknown Name';
                    }
                    status += employeeName + signedStatus + assignment.status + description +
                        '<br><small class="text-info">last update: ' + new Date(assignment.updated_at)
                        .toLocaleString() + '</small><hr>';
                });

                return status;
            }
        }
        // $('#editFormModal').on('hidden.bs.modal', function() {
        // location.reload();
        // });
    </script>
@endsection
