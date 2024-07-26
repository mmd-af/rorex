@extends('user.layouts.index')

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
    @include('user.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
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
                        <th>Staff Code</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Weeks</th>
                        <th>Shift</th>
                        <th>on_work1</th>
                        <th>off_work2</th>
                        <th>remarca</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Staff Code</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Weeks</th>
                        <th>Shift</th>
                        <th>on_work1</th>
                        <th>off_work2</th>
                        <th>remarca</th>
                        <th>Action</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="forgetRequest" tabindex="-1" aria-labelledby="forgetRequestLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="forgetRequestLabel">Request</h1>
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

                    <form action="{{ route('user.dailyReports.checkRequest') }}" method="post">
                        @csrf
                        <input type="hidden" id="first_name" name="first_name" value="">
                        <input type="hidden" id="last_name" name="last_name" value="">
                        <input type="hidden" id="department" name="department" value="">
                        <input type="hidden" id="email" name="email" value="">
                        <input type="hidden" id="staff_code" name="staff_code" value="">
                        <div class="mb-4">
                            <label for="date" class="col-form-label">Check for Date:
                                <div class="text-info" id="date_show"></div>
                            </label>
                            <input type="hidden" id="check_date" name="check_date" value="">
                        </div>
                        <div class="mb-4">
                            <label for="subject" class="col-form-label">Subject:</label>
                            <select class="form-control" name="subject" id="subject" onchange="handelRequestWithSubject()"
                                required>
                                <option value="">-- select subject --</option>
                                <option value="Forgot Punch">Forgot Punch</option>
                                <option value="Forgot Bring My Cart">Forgot Bring My Cart</option>
                                <option value="Consider OverTime">Consider OverTime</option>
                                <option value="Work at Home (Remote)">Work at Home (Remote)</option>
                                <option value="Mission">Mission</option>
                                <option value="Change Shift">Change Shift</option>
                            </select>
                        </div>
                        <div class="mb-4" id="descriptionData">
                        </div>
                        <div class="mb-4">
                            <label for="departmentRole" class="col-form-label">Referred to:</label>
                            <select class="form-control" name="departmentRole" id="departmentRole"
                                onchange="getRelateUserWithRole()" required>
                                <option value="">SELECT DEPARTMENT</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            User:
                            <label for="assigned_to" class="col-form-label">Referred to:</label>
                            <div id="assigned_user">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Send message</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
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
                    url: "{{ route('user.dailyReports.ajax.getDataTable') }}",
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
                        data: 'button',
                        name: 'button',
                        orderable: false,
                        searchable: false
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

        $(document).ready(function() {
            let departmentRole = document.getElementById('departmentRole');
            axios.get("{{ route('user.dailyReports.ajax.getRoles') }}")
                .then(function(response) {
                    response.data.forEach(function(item) {
                        departmentRole.innerHTML +=
                            `<option value="${item.name}">${item.name}</option>`;
                    });
                })
                .catch(function(error) {
                    console.error(error);
                });
        });

        function handelRequestWithSubject() {
            let subject = document.getElementById('subject');
            let descriptionData = document.getElementById('descriptionData');
            descriptionData.innerHTML = ``;
            subject = subject.value;
            if (subject === "Forgot Punch") {
                descriptionData.innerHTML = `<label for="description" class="col-form-label">At what time?</label>
                            <input type="time" class="form-control" name="description" id="description" required>`;
            }
            if (subject === "Forgot Bring My Cart") {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">Message:</label>
                     <textarea class="form-control" name="description" id="description" required>I forgot to bring my card. enter and exit time: --:-- to --:--</textarea>`;
            }
            if (subject === "Consider as allow Leave") {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">how many hour?</label>
                            <input type="number" min="0" step="any" class="form-control" name="description" id="description" required>`;
            }
            if (subject === "Consider OverTime") {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">please describe it:</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === "Work at Home (Remote)") {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">Start Work:</label>
                    <textarea class="form-control" name="description" id="description" required>I worked at home from .......... to ......... hour</textarea>`;
            }
            if (subject === "Mission") {
                descriptionData.innerHTML = `<label for="description" class="col-form-label">please describe it:</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === "other") {
                descriptionData.innerHTML = `<label for="description" class="col-form-label">Message:</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === "Change Shift") {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">Message:</label>
                            <textarea class="form-control" name="description" id="description" required>I request to change my shift to .........</textarea>`;
            }
        }

        function getRelateUserWithRole() {
            let assigned_user = document.getElementById('assigned_user');
            assigned_user.innerHTML = `<div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`;
            let data = {
                role_name: departmentRole.value
            }
            axios.post("{{ route('user.dailyReports.ajax.getUserWithRole') }}", data)
                .then(function(response) {
                    assigned_user.innerHTML = `
                    <select class="form-control" name="assigned_to" id="assigned_to" required>
                    </select>`;
                    let assignedTo = document.getElementById('assigned_to');
                    assignedTo.innerHTML = ``;
                    response.data.forEach(function(item) {
                        assignedTo.innerHTML +=
                            `<option value="${item.id}">${item.employee.last_name} ${item.employee.first_name}</option>`;
                    });
                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        function requestForm(id, data) {
            let alert = document.getElementById('alert');
            let last_name = document.getElementById('last_name');
            let first_name = document.getElementById('first_name');
            let department = document.getElementById('department');
            let email = document.getElementById('email');
            let staff_code = document.getElementById('staff_code');
            let date_show = document.getElementById('date_show');
            let check_date = document.getElementById('check_date');
            check_date.value = data;
            date_show.innerHTML = data;
            let configInformation = {
                id: id
            }
            axios.post("{{ route('user.dailyReports.ajax.getData') }}", configInformation)
                .then(function(response) {
                    alert.innerHTML = ``;
                    last_name.value = response.data.data.last_name;
                    first_name.value = response.data.data.first_name;
                    department.value = response.data.data.department;
                    email.value = response.data.data.users.email;
                    staff_code.value = response.data.data.staff_code;
                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    </script>
@endsection
