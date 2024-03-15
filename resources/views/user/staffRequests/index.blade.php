@extends('user.layouts.index')

@section('title')
    Staff Requests
@endsection
@section('style')

@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Staff Requests</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="d-flex justify-content-center my-3">
        <button type="button" class="btn btn-primary mx-3" data-bs-toggle="modal"
                data-bs-target="#LeaveRequest"
                onclick="LeaveRequestForRest()">
            Leave Request <i class="fa-solid fa-square-arrow-up-right"></i>
        </button>
        <button type="button"
                class="btn btn-primary mx-3" data-bs-toggle="modal"
                data-bs-target="#LeaveRequest" data-info="Modal 1 Content"
                onclick="LeaveRequestForSpecialEvents()">
            Leave Request for Special Events <i class="fa-solid fa-square-arrow-up-right"></i>
        </button>
        <button type="button"
                class="btn btn-primary mx-3" data-bs-toggle="modal"
                data-bs-target="#LeaveRequest"
                onclick="LeaveRequestForHour()">
            Hourly leave Request <i class="fa-solid fa-square-arrow-up-right"></i>
        </button>
        <button type="button"
                class="btn btn-primary mx-3" data-bs-toggle="modal"
                data-bs-target="#LeaveRequest"
                onclick="CustomRequest()">
            Custom Request <i class="fa-solid fa-square-arrow-up-right"></i>
        </button>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="staffRequestTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Tracking Number</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Date of Request</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Tracking Number</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Date of Request</th>
                    <th>Status</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="LeaveRequest" tabindex="-1"
         aria-labelledby="LeaveRequestLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="LeaveRequestLabel">Leave Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveForm">
                        <div class="mb-3 lh-lg h5">
                            LastName:
                            @if(empty(Auth::user()->name))
                                <input type="text" class="form-control" name="name" id="name" value="">
                            @else
                                <text class="text-primary"> {{Auth::user()->name}}</text>
                                <input type="hidden" name="name" value="{{Auth::user()->name}}">
                            @endif
                            FirstName:
                            @if(empty(Auth::user()->first_name))
                                <input type="text" class="form-control" name="first_name" id="first_name"
                                       value="">
                            @else
                                <text class="text-primary"> {{Auth::user()->first_name}} </text>
                                <input type="hidden" name="first_name"
                                       value="{{Auth::user()->first_name}}">
                            @endif
                            with Code Staff:
                            @if(empty(Auth::user()->cod_staff))
                                <input type="text" class="form-control" name="cod_staff" id="cod_staff" value="">
                            @else
                                <text class="text-primary">{{Auth::user()->cod_staff}}</text>
                                <input type="hidden" name="cod_staff" value="{{Auth::user()->cod_staff}}">
                            @endif
                            as an employee of S.C. ROREX PIPE S.R.L. in the Departament of
                            @if(empty(Auth::user()->departament))
                                <input type="text" class="form-control" name="departament" id="departament" value="">
                            @else
                                <text class="text-primary">{{Auth::user()->departament}}</text>
                                <input type="hidden" name="departament" value="{{Auth::user()->departament}}">
                            @endif
                            please approve my request for vacation during the period:
                            <div class="row">
                                <p class="small text-success"> Allowed leave= {{Auth::user()->leave_balance}} days</p>
                            </div>
                            <div class="row mt-4" id="datesForLeave">
                            </div>
                            <div class="row mt-4 p-5" id="modalSubject">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="col-form-label">Your Email:</label>
                            @if(empty(Auth::user()->email))
                                <input type="text" class="form-control" name="email" id="email" value="">
                            @else
                                <p class="text-primary">{{Auth::user()->email}}</p>
                                <input type="hidden" name="email" value="{{Auth::user()->email}}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-sm-12 col-lg-6">
                                    <label for="departamentRole" class="col-form-label">Referred to:</label>
                                    <select class="form-control" name="departamentRole" id="departamentRole"
                                            onclick="getRelateUserWithRole()">
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
        function LeaveRequestForRest() {
            let datesForLeave = document.getElementById('datesForLeave');
            datesForLeave.innerHTML = `
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(1)">
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date:</label>
                                    <input type="date" name="end_date" class="form-control" id="endDate"
                                           onchange="calculateDateDifference(1)">
                                </div>
                                <div class="col-md-6">
                                    <h6 id="dateDifference"></h6>
                                </div>`;
            let modalSubject = document.getElementById('modalSubject');
            modalSubject.innerHTML = `
<input type="hidden" name="kind" value="Rest">
<input type="hidden" name="subject" value="Leave Request">
                        <input type="hidden" name="description" value="">`;
        }

        function LeaveRequestForSpecialEvents() {
            let datesForLeave = document.getElementById('datesForLeave');
            datesForLeave.innerHTML = `
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(2)">
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date:</label>
                                    <input type="date" name="end_date" class="form-control" id="endDate"
                                           onchange="calculateDateDifference(2)">
                                </div>
                                <div class="col-md-6">
                                    <h6 id="dateDifference"></h6>
                                </div>`;
            let modalSubject = document.getElementById('modalSubject');
            modalSubject.innerHTML = `
                                <input type="hidden" name="kind" value="SpecialEvents">
                                <input type="hidden" name="subject" value="leave for special events">
                                <label for="description">explain:</label>
                                <input type="text" class="form-control" name="description" id="description" value="">`;
            const descriptionInput = document.getElementById('description');
            let isTextAdded = false;
            descriptionInput.addEventListener('change', function (event) {
                if (!isTextAdded) {
                    this.value = event.target.value + " and I will send the document.";
                    isTextAdded = true;
                    this.readOnly = true;
                }
            });
        }

        function LeaveRequestForHour() {
            let datesForLeave = document.getElementById('datesForLeave');
            datesForLeave.innerHTML = `
     <div class="row">
           <div class="col-md-6">
              <label for="startDate" class="form-label">Date:</label>
              <input type="date" name="start_date" class="form-control" id="startDate">
           </div>
    </div>
    <div class="col-md-6">
        <label for="startTime" class="form-label">Start Time:</label>
        <input type="time" name="start_time" class="form-control" id="startTime" onchange="calculateTimeDifference()">
    </div>
    <div class="col-md-6">
        <label for="endTime" class="form-label">End Time:</label>
        <input type="time" name="end_time" class="form-control" id="endTime" onchange="calculateTimeDifference()">
    </div>
    <div class="col-md-6">
        <h6 id="timeDifference"></h6>
    </div>`;
            let modalSubject = document.getElementById('modalSubject');
            modalSubject.innerHTML = `
                        <input type="hidden" name="kind" value="Hour">
                        <input type="hidden" name="subject" value="Hourly Leave Request">
                        <input type="hidden" name="description" value="hour">`;
        }

        function CustomRequest() {
            let datesForLeave = document.getElementById('datesForLeave');
            datesForLeave.innerHTML = ``;
            let modalSubject = document.getElementById('modalSubject');
            let start_date = new Date();
            modalSubject.innerHTML = `
<input type="hidden" name="kind" value="CustomRequest">
<input type="hidden" class="form-control" name="vacation_day" value="0">
<input type="hidden" class="form-control" name="start_date" value="${start_date.toISOString().split('T')[0]}">
<label for="subject">Subject:</label>
<input type="text" class="form-control" name="subject" id="subject" value="">
<label for="description">Description:</label>
<textarea name="description" class="form-control" id="description"></textarea>`;
        }

        $(document).ready(function () {
            $('#staffRequestTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('user.staffRequests.ajax.getDataTable') }}",
                columns: [
                    {data: 'id', name: 'id', width: '10%'},
                    {data: 'subject', name: 'subject'},
                    {data: 'description', name: 'description'},
                    {data: 'created_at', name: 'created_at', width: '10%'},
                    {data: 'status', name: 'status', width: '20%'}
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

        function calculateDateDifference(x) {
            var startDateValue = document.getElementById('startDate').value.trim();
            var endDateValue = document.getElementById('endDate').value.trim();
            let leave_balance = "{{Auth::user()->leave_balance}}";
            if (startDateValue !== '' && endDateValue !== '') {
                var startDate = new Date(startDateValue);
                var endDate = new Date(endDateValue);
                if (endDate < startDate) {
                    document.getElementById('dateDifference').innerHTML = `<p class="text-danger">The second date cannot be before the first date</p>`;
                    return;
                }
                var timeDifference = Math.abs(endDate - startDate + 1);
                var dayDifference = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
                let numberOfExcludingHolidays = prompt('How many days EXCLUDING Holidays?');
                let showInformation = document.getElementById('dateDifference');
                if (x === 1) {
                    showInformation.innerHTML =
                        `<div class="mt-2">
                        <p class="text-primary">Totally= ${dayDifference} days</p>
                        <p class="text-warning">Allowed leave= ${leave_balance} days</p>
                        <p class="text-info">EXCLUDING Holidays= ${numberOfExcludingHolidays} days</p>
                        <input type="hidden" name="totally" value="${dayDifference}">
                        <input type="hidden" name="leave_balance" value="${leave_balance}">
                        <input type="hidden" name="vacation_day" value="${numberOfExcludingHolidays}">
                    </div>`;
                    if (leave_balance < numberOfExcludingHolidays) {
                        let daysWithoutPay = numberOfExcludingHolidays - leave_balance;
                        daysWithoutPay = Math.ceil(daysWithoutPay);
                        showInformation.innerHTML += `<div>
                             <p class="text-danger">Number of unpaid leave= ${daysWithoutPay}</p>
                        <input type="hidden" name="daysWithoutPay" value="${daysWithoutPay}">
                           </div>`;
                    }
                }
                if (x === 2) {
                    showInformation.innerHTML =
                        `<div class="mt-3">
                        <p class="text-primary">Totally= ${dayDifference} days</p>
                        <p class="text-info">EXCLUDING Holidays= ${numberOfExcludingHolidays} days</p>
                        <input type="hidden" name="vacation_day" value="${numberOfExcludingHolidays}">
                        <input type="hidden" name="totally" value="${dayDifference}">
                    </div>`;
                }
            }
        }

        function calculateTimeDifference() {
            var startTimeValue = document.getElementById('startTime').value.trim();
            var endTimeValue = document.getElementById('endTime').value.trim();

            if (startTimeValue !== '' && endTimeValue !== '') {
                var startTime = new Date('1970-01-01T' + startTimeValue + ':00Z').getTime();
                var endTime = new Date('1970-01-01T' + endTimeValue + ':00Z').getTime();
                if (endTime < startTime) {
                    document.getElementById('timeDifference').innerHTML = `<p class="text-danger">End time cannot be earlier than start time</p>`;
                    return;
                }
                var timeDifferenceInMilliseconds = Math.abs(endTime - startTime);
                var hours = Math.floor(timeDifferenceInMilliseconds / (1000 * 60 * 60));
                var minutes = Math.floor((timeDifferenceInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));

                document.getElementById('timeDifference').innerHTML =
                    `<p class="text-success">${hours} hours and ${minutes} minutes</p>
             <input type="hidden" name="vacation_day" value="${hours}:${minutes}">`;
            }
        }

        $(document).ready(function () {
            let departamentRole = document.getElementById('departamentRole');
            axios.get('{{route('user.staffRequests.ajax.getRoles')}}')
                .then(function (response) {
                    response.data.forEach(function (item) {
                        departamentRole.innerHTML += `<option value="${item.name}">${item.name}</option>`;
                    });
                })
                .catch(function (error) {
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
            axios.post('{{route('user.staffRequests.ajax.getUserWithRole')}}', data)
                .then(function (response) {
                    assigned_user.innerHTML = `
                    <select class="form-control" name="assigned_to" id="assigned_to">
                    </select>`;
                    let assignedTo = document.getElementById('assigned_to');
                    assignedTo.innerHTML = ``;
                    response.data.forEach(function (item) {
                        assignedTo.innerHTML += `<option value="${item.id}">${item.name} ${item.first_name}</option>`;
                    });
                })
                .catch(function (error) {
                    console.error(error);
                });
        }

        document.getElementById('leaveForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var form = event.target;
            var formData = new FormData(form);
            var name = formData.get('name');
            var first_name = formData.get('first_name');
            var cod_staff = formData.get('cod_staff');
            var departament = formData.get('departament');
            var startDay = formData.get('start_date');
            var endDay = formData.get('end_date');
            var totally = formData.get('totally');
            var vacation_day = formData.get('vacation_day');
            var email = formData.get('email');
            var departamentRole = formData.get('departamentRole');
            var subject = formData.get('subject');
            var assigned_to = formData.get('assigned_to');
            var description = formData.get('description');
            var start_time = formData.get('start_time');
            var end_time = formData.get('end_time');
            var daysWithoutPay = formData.get('daysWithoutPay');
            var leave_balance = formData.get('leave_balance');
            var kind = formData.get('kind');
            var dateOfRequest = moment().format('YYYY/MM/DD');
            if (kind === "Rest" || kind === "SpecialEvents") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + cod_staff + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject + '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>Requests <b>' + vacation_day + ' days</b> during the period:<br><b>' + startDay + ' </b>until:<b> ' + endDay +
                    '</b><br>Total days: ' + totally +
                    '<br>Allowed leave: ' + leave_balance + '<br>EXCLUDING Holidays: ' + vacation_day + '<br>Days without Pay: ' +
                    daysWithoutPay + '<br><small>' + description + '</small><br>Email: ' + email + '<hr>';
            }
            if (kind === "Hour") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + cod_staff + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject + '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>requests for hour leave on:<br><b>' + startDay +
                    '</b><br>between:<b> ' + start_time + ' </b>until: <b>' + end_time + ' </b><br>' + vacation_day + description +
                    '<br>Email: ' + email + '<hr>';
            }
            if (kind === "CustomRequest") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + cod_staff + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject + '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>' + description + '<br>Email: ' + email + '<hr>';
            }
            let data = {
                first_name: first_name,
                name: name,
                cod_staff: cod_staff,
                departament: departament,
                subject: subject,
                description: newDescription,
                start_date: startDay,
                end_date: endDay,
                vacation_day: vacation_day,
                email: email,
                departamentRole: departamentRole,
                assigned_to: assigned_to
            }
            axios.post('{{route('user.staffRequests.ajax.store')}}', data)
                .then(function (response) {
                    location.reload();
                })
                .catch(function (error) {
                    alert(error.request.response)
                });
            let performAction=document.getElementById('performAction');
            performAction.disabled = true;
            performAction.innerHTML = `
             <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Loading...`;
        });
    </script>
@endsection
