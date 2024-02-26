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
            Leave Request for Rest <i class="fa-solid fa-square-arrow-up-right"></i>
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
            Request for Hourly leave <i class="fa-solid fa-square-arrow-up-right"></i>
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
                            @if(empty(Auth::user()->prenumele_tatalui))
                                <input type="text" class="form-control" name="prenumele_tatalui" id="prenumele_tatalui"
                                       value="">
                            @else
                                <text class="text-primary"> {{Auth::user()->prenumele_tatalui}} </text>
                                <input type="hidden" name="prenumele_tatalui"
                                       value="{{Auth::user()->prenumele_tatalui}}">
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
                                    User:
                                    <label for="assigned_to" class="col-form-label">Referred to:</label>
                                    <select class="form-control" name="assigned_to" id="assigned_to">
                                        <option value="">SELECT USER</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3">Send</button>
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
            modalSubject.innerHTML = `<input type="hidden" name="subject" value="Leave Request for Rest">
                        <input type="hidden" name="description" value="vacation">`;
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
            modalSubject.innerHTML = `<input type="hidden" name="subject" value="leave for special events">
                                <label for="description">explain:</label>
                                <input type="text" class="form-control" name="description" id="description" value="">`;
            const descriptionInput = document.getElementById('description');
            let isTextAdded = false;
            descriptionInput.addEventListener('change', function (event) {
                if (!isTextAdded) {
                    this.value = "for " + event.target.value + " and I will send the document.";
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
            modalSubject.innerHTML = `<input type="hidden" name="subject" value="Leave Request for Hour">
                        <input type="hidden" name="description" value="hour">`;
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
                let numberOfholidays = prompt('Number of holidays between dates');
                let showInformation = document.getElementById('dateDifference');
                if (x === 1) {
                    showInformation.innerHTML =
                        `<div class="p-5">
                        <p class="text-info">Allowed leave= ${leave_balance} days</p>
                        <p class="text-primary">Your Request= ${dayDifference} days</p>
                        <p class="text-info">Holidays= ${numberOfholidays} days</p>
                        <input type="hidden" name="numberOfholidays" value="${numberOfholidays}">
                        <input type="hidden" name="leave_balance" value="${leave_balance}">
                        <input type="hidden" name="vacation_day" value="${dayDifference}">
                    </div>`;
                    if ((dayDifference - numberOfholidays) > leave_balance) {
                        let notAllowedDays = (dayDifference - numberOfholidays) - leave_balance;
                        showInformation.innerHTML += `<div class="px-5">
                             <p class="text-danger">Number of unpaid leave= ${notAllowedDays}</p>
                        <input type="hidden" name="notAllowedDays" value="${notAllowedDays}">
                        <input type="hidden" name="realAllowedLeaveDays" value="${leave_balance}">
                           </div>`;
                    } else {
                        showInformation.innerHTML += `<input type="hidden" name="realAllowedLeaveDays" value="${dayDifference - numberOfholidays}">`;
                    }
                }
                if (x === 2) {
                    showInformation.innerHTML =
                        `<div class="p-5">
                        <p class="text-primary">Your Request= ${dayDifference} days</p>
                        <p class="text-info">Holidays= ${numberOfholidays} days</p>
                        <input type="hidden" name="vacation_day" value="${dayDifference}">
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
            let assignedTo = document.getElementById('assigned_to');
            assignedTo.innerHTML = ``;
            let data = {
                role_name: departamentRole.value
            }
            axios.post('{{route('user.staffRequests.ajax.getUserWithRole')}}', data)
                .then(function (response) {
                    response.data.forEach(function (item) {
                        assignedTo.innerHTML += `<option value="${item.id}">${item.name}</option>`;
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
            var prenumele_tatalui = formData.get('prenumele_tatalui');
            var cod_staff = formData.get('cod_staff');
            var departament = formData.get('departament');
            var startDay = formData.get('start_date');
            var endDay = formData.get('end_date');
            var vacation_day = formData.get('vacation_day');
            var realAllowedLeaveDays = formData.get('realAllowedLeaveDays');
            var email = formData.get('email');
            var departamentRole = formData.get('departamentRole');
            var subject = formData.get('subject');
            var assigned_to = formData.get('assigned_to');
            var description = formData.get('description');
            var start_time = formData.get('start_time');
            var end_time = formData.get('end_time');
            var notAllowedDays = formData.get('notAllowedDays');
            var leave_balance = formData.get('leave_balance');
            var numberOfholidays = formData.get('numberOfholidays');

            if (start_time === null && end_time === null) {
                var newDescription = "Name: " + name + " " + prenumele_tatalui + "<br>" +
                    "with Code Staff: " + cod_staff + "<br>" +
                    "Subject: " + subject +
                    "<br>as an employee of S.C. ROREX PIPE S.R.L. in the Departament of: " + departament +
                    "<br>please approve my request for vacation during the period:<br>" + startDay + " until: " + endDay + " <br> Request for: "
                    + vacation_day + " days <br>Allowed leave: " + leave_balance + "<br>Holidays: " + numberOfholidays + "<br>Not Allowed Days: "
                    + notAllowedDays + "<br>for: " + description + "<br>Email: " + email + "<br>Referred to:" + departamentRole;
            } else {
                var newDescription = "Name: " + name + " " + prenumele_tatalui + "<br>" +
                    "with Code Staff: " + cod_staff + "<br>" +
                    "Subject: " + subject +
                    "<br>as an employee of S.C. ROREX PIPE S.R.L. in the Departament of: " + departament +
                    "<br>please approve my request for hour vacation during the date:<br>" + startDay + "<br>between: " + start_time + " until: "
                    + end_time + " <br> " + vacation_day + description + "<br>Email: " + email + "<br>Referred to:" + departamentRole;
            }
            let data = {
                prenumele_tatalui: prenumele_tatalui,
                name: name,
                cod_staff: cod_staff,
                departament: departament,
                subject: subject,
                description: newDescription,
                start_date: startDay,
                end_date: endDay,
                vacation_day: vacation_day,
                realAllowedLeaveDays: realAllowedLeaveDays,
                email: email,
                departamentRole: departamentRole,
                assigned_to: assigned_to
            }
            axios.post('{{route('user.staffRequests.ajax.store')}}', data)
                .then(function (response) {
                    location.reload();
                })
                .catch(function (error) {
                    alert(error.message)
                });
        });
    </script>
@endsection
