@extends('user.layouts.index')

@section('title')
    Staff Requests
@endsection
@section('style')
    <style id="printStyle">
        #box {
            border: 2px solid black;
            padding: 2px;
        }

        #alignCenter {
            text-align: center;
        }

        .errorMessage {
            color: red;
            font-weight: bold;
            margin: 20px;
        }
    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Staff Requests</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#LeaveRequest"
                    data-info="Modal 1 Content">
                    Send New Request <i class="fa-solid fa-square-arrow-up-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 border p-3 m-2">
            <h6 class="text-success"> Remaining allowable leave= {{ Auth::user()->leave_balance / 8 }} days
                ({{ Auth::user()->leave_balance }} hours)</h6>
        </div>
        <div class="col-xl-3 col-md-6 border p-3 m-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-success">Leaved Days statistics</h6>
                    <div class="mb-3">
                        <select id="year" name="year" class="form-select" onchange="getLeaveDays(event)">
                            <option value="">-- select year --</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div id="resultLeaveDays"></div>
                    <p class="text-warning">Note that this statistic is from 01/07/2024</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 border p-3 m-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-success">Hourly Leaved statistics</h6>
                    <div class="mb-3">
                        <select id="year" name="year" class="form-select" onchange="getHourlyLeave(event)">
                            <option value="">-- select year --</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div id="resultHourlyLeave"></div>
                    <p class="text-warning">Note that this statistic is from 01/07/2024</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="staffRequestTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>File</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>File</th>
                        <th>Status</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="LeaveRequest" tabindex="-1" aria-labelledby="LeaveRequestLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="LeaveRequestLabel">Leave Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 lh-lg h5">
                            @if (empty(Auth::user()->name))
                                LastName:
                                <input type="text" class="form-control" name="name" id="name" value="">
                            @else
                                <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                            @endif
                            @if (empty(Auth::user()->first_name))
                                FirstName:
                                <input type="text" class="form-control" name="first_name" id="first_name" value="">
                            @else
                                <input type="hidden" name="first_name" value="{{ Auth::user()->first_name }}">
                            @endif
                            @if (empty(Auth::user()->cod_staff))
                                Code Staff:
                                <input type="text" class="form-control" name="cod_staff" id="cod_staff" value="">
                            @else
                                <input type="hidden" name="cod_staff" value="{{ Auth::user()->cod_staff }}">
                            @endif
                            @if (empty(Auth::user()->departament))
                                Departament:
                                <input type="text" class="form-control" name="departament" id="departament"
                                    value="">
                            @else
                                <input type="hidden" name="departament" value="{{ Auth::user()->departament }}">
                            @endif
                            <div class="col-md-6">
                                <label for="type">Type:</label>
                                <select class="form-select" name="type" id="type"
                                    onchange="actionForSelectType(event)" required>
                                    <option selected>-- Select one --</option>
                                    <option value="Allowed Leave">Allowed Leave</option>
                                    <option value="Medical Leave">Medical Leave</option>
                                    <option value="Speacial Event Leave">Speacial Event Leave</option>
                                    <option value="Hourly Leave">Hourly Leave</option>
                                    <option value="Without Paid Leave">Without Paid Leave</option>
                                </select>
                            </div>
                            <div class="row mt-4" id="datesForLeave">
                            </div>
                            <div class="row mt-4" id="modalSubject">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="col-form-label">Your Email:</label>
                            @if (empty(Auth::user()->email))
                                <input type="email" class="form-control" name="email" id="email" value=""
                                    required>
                            @else
                                <p class="text-primary">{{ Auth::user()->email }}</p>
                                <input type="hidden" name="email" value="{{ Auth::user()->email }}" required>
                            @endif
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <label for="departamentRole" class="col-form-label">Referred to:</label>
                                    <select class="form-control" name="departamentRole" id="departamentRole"
                                        onchange="getRelateUserWithRole()" required>
                                        <option value="">SELECT DEPARTMENT</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <label for="assigned_to" class="col-form-label">Referred to:</label>
                                    <div id="assigned_user">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3" id="performAction">Send</button>
                    </form>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#staffRequestTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('user.leaves.ajax.getDataTable') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '5%'
                    },
                    {
                        data: 'start_date',
                        name: 'start_date'
                    },
                    {
                        data: 'end_date',
                        name: 'end_date'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'value',
                        name: 'value'
                    },
                    {
                        data: 'file',
                        name: 'file',
                        width: '5%'
                    },
                    {
                        data: 'status',
                        name: 'status',
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

        function LeaveRequestForRest(datesForLeave) {
            datesForLeave.innerHTML = `
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(1)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date:</label>
                                    <input type="date" name="end_date" class="form-control" id="endDate"
                                           onchange="calculateDateDifference(1)" required>
                                </div>
                                <div class="col-md-6">
                                    <h6 id="dateDifference"></h6>
                                </div>`;
            let modalSubject = document.getElementById('modalSubject');
            modalSubject.innerHTML = `
                    <input type="hidden" name="subject" value="Allowed Leave">`;
        }

        function LeaveRequestWithoutPaid(datesForLeave) {
            datesForLeave.innerHTML = `
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(3)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date:</label>
                                    <input type="date" name="end_date" class="form-control" id="endDate"
                                           onchange="calculateDateDifference(3)" required>
                                </div>
                                <div class="col-md-6">
                                    <h6 id="dateDifference"></h6>
                                </div>`;
            let modalSubject = document.getElementById('modalSubject');
            modalSubject.innerHTML = `
                    <input type="hidden" name="subject" value="Without Paid Leave">`;
        }

        function calculateDateDifference(x) {
            var startDateValue = document.getElementById('startDate').value.trim();
            var endDateValue = document.getElementById('endDate').value.trim();
            let leave_balance = "{{ Auth::user()->leave_balance }}";
            if (startDateValue !== '' && endDateValue !== '') {
                var startDate = new Date(startDateValue);
                var endDate = new Date(endDateValue);
                if (endDate < startDate) {
                    document.getElementById('dateDifference').innerHTML =
                        `<p class="text-danger">The second date cannot be before the first date</p>`;
                    return;
                }
                var timeDifference = Math.abs(endDate - startDate + 1);
                var dayDifference = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
                let numberOfExcludingHolidays;
                do {
                    numberOfExcludingHolidays = prompt('How many days EXCLUDING Holidays? (Just enter the number)');
                } while (numberOfExcludingHolidays === null || numberOfExcludingHolidays.trim() === '' || isNaN(
                        numberOfExcludingHolidays) || !Number.isInteger(parseFloat(numberOfExcludingHolidays)));
                numberOfExcludingHolidays = parseInt(numberOfExcludingHolidays, 10);
                let showInformation = document.getElementById('dateDifference');
                if (x === 1) {
                    if ((leave_balance / 8) < numberOfExcludingHolidays) {
                        showInformation.innerHTML +=
                            `<div class="m-3">
                         <h4 class="text-danger">The number of days you request is more than the total number of days you are allowed</h4>
                         <h6 class="text-info">You can use the without paid Leave option</h6>
                         <input type="hidden" name="leave_days" value="${numberOfExcludingHolidays}" required>
                      </div>`;
                    } else {
                        showInformation.innerHTML =
                            `<div class="mt-2">
                        <p class="text-primary">Total requested days= ${dayDifference} days</p>
                        <p class="text-info">EXCLUDING Holidays= ${numberOfExcludingHolidays} days</p>
                        <input type="hidden" name="totally" value="${dayDifference}">
                        <input type="hidden" name="leave_balance" value="${leave_balance}">
                        <input type="hidden" name="leave_days" value="${numberOfExcludingHolidays}" required>
                    </div>`;
                    }
                }
                if (x === 2) {
                    showInformation.innerHTML =
                        `<div class="mt-3">
                        <p class="text-primary">Totally= ${dayDifference} days</p>
                        <p class="text-info">EXCLUDING Holidays= ${numberOfExcludingHolidays} days</p>
                        <input type="hidden" name="leave_days" value="${numberOfExcludingHolidays}">
                        <input type="hidden" name="totally" value="${dayDifference}">
                        <input type="hidden" name="leave_balance" value="${leave_balance}">
                    </div>`;
                }
                if (x === 3) {
                    showInformation.innerHTML =
                        `<div class="mt-2">
                        <p class="text-primary">Total requested days= ${dayDifference} days</p>
                        <p class="text-info">EXCLUDING Holidays= ${numberOfExcludingHolidays} days</p>
                        <p class="text-danger">unpaid leave</p>
                        <input type="hidden" name="totally" value="${dayDifference}">
                        <input type="hidden" name="leave_balance" value="${leave_balance}">
                        <input type="hidden" name="leave_days" value="${numberOfExcludingHolidays}" required>
                        <input type="hidden" name="daysWithoutPay" value="${numberOfExcludingHolidays}">
                    </div>`;
                }
            }
        }

        function LeaveRequestForMedicalLeave(datesForLeave) {
            datesForLeave.innerHTML = `
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(2)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date:</label>
                                    <input type="date" name="end_date" class="form-control" id="endDate"
                                           onchange="calculateDateDifference(2)" required>
                                </div>
                                <div class="col-md-6">
                                    <h6 id="dateDifference"></h6>
                                </div>`;
            let modalSubject = document.getElementById('modalSubject');
            modalSubject.innerHTML =
                `
                                <input type="hidden" name="subject" value="Medical Leave">
                                <div class="mb-3">
                                <label for="description">explain:</label>
                                <input type="text" class="form-control" name="description" id="description" value="" required>
                                </div> 
                                <div class="mb-3">
                                   <label for="file">file:</label>
                                   <input type="file" class="form-control" name="file" id="file" required/>
                                  <small id="file" class="form-text text-muted">The attached file is required for Medical leave</small>
                                  </div>`;
        }

        function LeaveRequestForSpecialEvents(datesForLeave) {
            datesForLeave.innerHTML = `
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(2)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date:</label>
                                    <input type="date" name="end_date" class="form-control" id="endDate"
                                           onchange="calculateDateDifference(2)" required>
                                </div>
                                <div class="col-md-6">
                                    <h6 id="dateDifference"></h6>
                                </div>`;
            let modalSubject = document.getElementById('modalSubject');
            modalSubject.innerHTML =
                `
                                <input type="hidden" name="subject" value="leave for special events">
                                <div class="mb-3">
                                <label for="description">explain:</label>
                                <input type="text" class="form-control" name="description" id="description" value="" required>
                                </div> 
                                <div class="mb-3">
                                   <label for="file">file:</label>
                                   <input type="file" class="form-control" name="file" id="file" required/>
                                  <small id="file" class="form-text text-muted">The attached file is required for Special Events leave</small>
                                  </div>`;
        }

        function LeaveRequestForHour(datesForLeave) {
            datesForLeave.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label for="startDate" class="form-label">Date:</label>
                    <input type="date" name="start_date" class="form-control" id="startDate" required>
                </div>
            </div>
            <div class="col-md-6">
                <label for="startTime" class="form-label">Start Time:</label>
                <input type="time" name="start_time" class="form-control" id="startTime" onchange="calculateTimeDifference()" required>
            </div>
            <div class="col-md-6">
                <label for="endTime" class="form-label">End Time:</label>
                <input type="time" name="end_time" class="form-control" id="endTime" onchange="calculateTimeDifference()" required>
            </div>
            <div class="col-md-6">
                <h6 id="timeDifference"></h6>
            </div>`;
            let modalSubject = document.getElementById('modalSubject');
            modalSubject.innerHTML = `
                                <input type="hidden" name="subject" value="Hourly Leave">`;
        }

        function actionForSelectType(event) {
            let datesForLeave = document.getElementById('datesForLeave');
            datesForLeave.innerHTML = ``;
            if (event.target.value === "Allowed Leave") {
                LeaveRequestForRest(datesForLeave)
            }
            if (event.target.value === "Medical Leave") {
                LeaveRequestForMedicalLeave(datesForLeave)
            }
            if (event.target.value === "Speacial Event Leave") {
                LeaveRequestForSpecialEvents(datesForLeave)
            }
            if (event.target.value === "Hourly Leave") {
                LeaveRequestForHour(datesForLeave)
            }
            if (event.target.value === "Without Paid Leave") {
                LeaveRequestWithoutPaid(datesForLeave)
            }
        }

        function calculateTimeDifference() {
            var startTimeValue = document.getElementById('startTime').value.trim();
            var endTimeValue = document.getElementById('endTime').value.trim();

            if (startTimeValue !== '' && endTimeValue !== '') {
                var startTime = new Date('1970-01-01T' + startTimeValue + ':00Z').getTime();
                var endTime = new Date('1970-01-01T' + endTimeValue + ':00Z').getTime();
                if (endTime < startTime) {
                    document.getElementById('timeDifference').innerHTML =
                        `<p class="text-danger">End time cannot be earlier than start time</p>`;
                    return;
                }
                var timeDifferenceInMilliseconds = Math.abs(endTime - startTime);
                var hours = Math.floor(timeDifferenceInMilliseconds / (1000 * 60 * 60));
                var minutes = Math.floor((timeDifferenceInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));

                document.getElementById('timeDifference').innerHTML =
                    `<p class="text-success">${hours} hours and ${minutes} minutes</p>
             <input type="hidden" name="leave_time" value="${hours}:${minutes}">`;
            }
        }

        $(document).ready(function() {
            let departamentRole = document.getElementById('departamentRole');
            axios.get('{{ route('user.staffRequests.ajax.getRoles') }}')
                .then(function(response) {
                    response.data.forEach(function(item) {
                        departamentRole.innerHTML +=
                            `<option value="${item.name}">${item.name}</option>`;
                    });
                })
                .catch(function(error) {
                    console.error(error);
                });
        });

        function getRelateUserWithRole() {
            let assigned_user = document.getElementById('assigned_user');
            assigned_user.innerHTML = `<div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`;
            let data = {
                role_name: departamentRole.value
            }
            axios.post('{{ route('user.staffRequests.ajax.getUserWithRole') }}', data)
                .then(function(response) {
                    assigned_user.innerHTML = `
                    <select class="form-control" name="assigned_to" id="assigned_to" required>
                    </select>`;
                    let assignedTo = document.getElementById('assigned_to');
                    assignedTo.innerHTML = ``;
                    response.data.forEach(function(item) {
                        assignedTo.innerHTML +=
                            `<option value="${item.id}">${item.name} ${item.first_name}</option>`;
                    });
                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        document.getElementById('leaveForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var form = event.target;
            var formData = new FormData(form);
            var name = formData.get('name');
            var first_name = formData.get('first_name');
            var cod_staff = formData.get('cod_staff');
            var departament = formData.get('departament');
            var type = formData.get('type');
            var file = formData.get('file');
            let startDay = formData.get('start_date');
            let endDay = formData.get('end_date');
            var totally = formData.get('totally');
            var leave_time = formData.get('leave_time');
            var leave_days = formData.get('leave_days');
            var email = formData.get('email');
            var departamentRole = formData.get('departamentRole');
            var subject = formData.get('subject');
            var assigned_to = formData.get('assigned_to');
            var description = formData.get('description');
            var start_time = formData.get('start_time');
            var end_time = formData.get('end_time');
            var daysWithoutPay = formData.get('daysWithoutPay');
            var leave_balance = formData.get('leave_balance');
            var check_date_other_request = formData.get('check_date_other_request');
            var dateOfRequest = moment().format('YYYY/MM/DD');
            if (type === "Allowed Leave" || type === "Medical Leave" || type === "Speacial Event Leave" ||
                type === "Without Paid Leave") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + cod_staff + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject +
                    '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>Requests <b>' + leave_days + ' days</b> during the period:<br><b>' + startDay +
                    '</b>until:<b> ' + endDay +
                    '</b><br>Total days: ' + totally +
                    '<br>Allowed leave: ' + leave_balance + '<br>EXCLUDING Holidays: ' + leave_days +
                    '<br>Days without Pay: ' +
                    daysWithoutPay + '<br>' + description + '<br>Email: ' + email + '<hr>';
            }
            if (type === "Hourly Leave") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + cod_staff + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject +
                    '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>requests for hour leave on:<br><b>' + startDay +
                    '</b><br>between:<b> ' + start_time + ' </b>until: <b>' + end_time + ' </b><br>' +
                    leave_time +
                    '<br>Email: ' + email + '<hr>';

                let startHourDate = startDay;
                startDay = `${startHourDate} ${start_time}:00`;
                endDay = `${startHourDate} ${end_time}:00`;
            }
            let data = {
                first_name: first_name,
                name: name,
                cod_staff: cod_staff,
                departament: departament,
                subject: subject,
                type: type,
                file: file,
                description: newDescription,
                start_date: startDay,
                end_date: endDay,
                leave_time: leave_time,
                leave_days: leave_days,
                email: email,
                departamentRole: departamentRole,
                assigned_to: assigned_to
            }
            let performAction = document.getElementById('performAction');
            performAction.disabled = true;
            performAction.innerHTML = `
             <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Loading...`;
            axios.post(`{{ route('user.leaves.ajax.store') }}`, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    location.reload();
                })
                .catch(function(error) {
                    var response = JSON.parse(error.request.response);
                    alert(response.message);
                    performAction.disabled = false;
                    performAction.innerHTML = `Send`;
                });

        });

        function getLeaveDays(event) {
            axios.post(`{{ route('user.leaves.ajax.getLeavedDays') }}`, {
                    year: event.target.value
                })
                .then(response => {
                    document.getElementById('resultLeaveDays').innerHTML =
                        '<h6>Total leaved days </h6><h4 class="d-flex justify-content-center text-success">' + response
                        .data.total_leave_days +
                        '</h4>';
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function getHourlyLeave(event) {
            axios.post(`{{ route('user.leaves.ajax.getHourlyLeaved') }}`, {
                    year: event.target.value
                })
                .then(response => {
                    document.getElementById('resultHourlyLeave').innerHTML =
                        '<h6>Total Hourly leaved</h6><h4 class="d-flex justify-content-center text-success">' + response
                        .data.total_hour_leave +
                        '</h4>';
                })
                .catch(error => {
                    console.error(error);
                });
        }
    </script>
@endsection
