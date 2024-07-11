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
    <div class="d-flex justify-content-between">
        <div></div>
        <div>
            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#fullExport">
                <i class="fa-solid fa-file-csv fa-xl"></i>
            </button>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="monthlyReportTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>cod_staff</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>cod_staff</th>
                        <th>Last Name</th>
                        <th>First Name</th>
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalReportBody">
                    <form class="form-control px-5">
                        @csrf
                        <label for="date">Select Date:</label>
                        <select id="date" name="date" class="form-control">
                            <?php
                            $selectMonths = 4;
                            $currentMonth = date('n');
                            $currentYear = date('Y');
                            for ($i = 0; $i < 12; $i++) {
                                $monthValue = (($currentMonth - $i + 12) % 12) + 1;
                                $yearValue = $currentYear + floor(($currentMonth - $i - 1) / 12);
                                if ($monthValue <= $currentMonth && $i < $selectMonths) {
                                    $formattedMonth = sprintf('%02d', $monthValue);
                                    $dateOutput = "$yearValue-$formattedMonth";
                                    $monthName = date('F', mktime(0, 0, 0, $monthValue, 1, $yearValue));
                                    echo "<option value=\"$dateOutput\">$monthName $yearValue</option>";
                                }
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
    <div class="modal fade" id="fullExport" tabindex="-1" aria-labelledby="fullExport" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="fullExport">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.monthlyReports.fullExport') }}" class="form-control px-5" method="post">
                        @csrf
                        <label for="dateOfExport">Select Date:</label>
                        <select id="dateOfExport" name="dateOfExport" class="form-control">
                            <?php
                            $selectMonths = 4;
                            $currentMonth = date('n');
                            $currentYear = date('Y');
                            for ($i = 0; $i < 12; $i++) {
                                $monthValue = (($currentMonth - $i + 12) % 12) + 1;
                                $yearValue = $currentYear + floor(($currentMonth - $i - 1) / 12);
                                if ($monthValue <= $currentMonth && $i < $selectMonths) {
                                    $formattedMonth = sprintf('%02d', $monthValue);
                                    $dateOutput = "$yearValue-$formattedMonth";
                                    $monthName = date('F', mktime(0, 0, 0, $monthValue, 1, $yearValue));
                                    echo "<option value=\"$dateOutput\">$monthName $yearValue</option>";
                                }
                            }
                            ?>
                        </select>
                        <button type="submit" class="btn btn-primary mt-3">Export
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#monthlyReportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                responsive: true,
                ajax: "{{ route('admin.monthlyReports.ajax.getUserTable') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'cod_staff',
                        name: 'cod_staff'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'first_name',
                        name: 'first_name'
                    },
                    {
                        "data": "button",
                        "name": "button",
                        "orderable": false,
                        "searchable": false
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
            axios.post('{{ route('admin.monthlyReports.ajax.monthlyReportWithDate') }}', formData)
                .then(function(response) {
                    let codeStaff = response.data.codeStaff;
                    let monthDate = response.data.monthDate;
                    let url = "{{ route('admin.monthlyReports.userMonthlyReportExport') }}"
                    showResult.innerHTML = `
            <div class="row justify-content-between">
               <div class="col"></div>
                   <div class="col">
                        <form method="POST" action="${url}">
                             @csrf
                            <input type="hidden" name="cod_staff" value="${codeStaff}">
                            <input type="hidden" name="date" value="${monthDate}">
                             <button type="submit" class="btn btn-outline-success float-end">
                                <i class="fa-solid fa-file-csv fa-xl"></i>
                            </button>
                        </form>
                </div>
                </div>
                 <div class="row justify-content-center mt-5">
                                    <table class="table table-striped border text-center table-responsive p-5">
                                        <thead>
                                        <tr>
                                            <th scope="col">Total</th>
                                            <th scope="col">value</th>
                                            <th scope="col">unit</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Night</td>
                                            <td>${response.data.hourNight}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Morning</td>
                            <td>${response.data.hourMorning}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Afternoon</td>
                            <td>${response.data.hourAfternoon}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Daily</td>
                            <td>${response.data.hourDaily}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Total Overtime Work</td>
                            <td class="bg-info text-light">${response.data.ot_ore}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>plus_week_day</td>
                            <td class="bg-success text-light">${response.data.plus_week_day}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>plus_week_night</td>
                            <td class="bg-success text-light">${response.data.plus_week_night}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>plus_holiday_day</td>
                            <td class="bg-success text-light">${response.data.plus_holiday_day}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>plus_holiday_night</td>
                            <td class="bg-success text-light">${response.data.plus_holiday_night}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Delay Work</td>
                            <td class="bg-warning">${response.data.delayWork}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Early Exit</td>
                            <td class="bg-warning">${response.data.earlyExit}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <th>Daily Absence</th>
                            <th class="bg-danger">${response.data.dailyAbsence}</th>
                            <th>per day</th>
                        </tr>
                        <tr>
                            <th>Concediu ore</th>
                            <th class="bg-warning">${response.data.concediu_ore}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Without Paid Leave</th>
                            <th class="bg-warning">${response.data.without_paid_leave}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Total working hours</th>
                            <th>${response.data.totalHours}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Unknown</th>
                            <th>${response.data.hourUnknown}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Default Shift</th>
                            <th>${response.data.turaImplicita}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Forgot Punch</th>
                            <th>${response.data.forgotPunch}</th>
                            <th>pcs</th>
                        </tr>
                        </tbody>
                    </table>
                    </div>`;
                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        $(document).on('hidden.bs.modal', function() {
            location.reload(true);
        });
    </script>
@endsection
