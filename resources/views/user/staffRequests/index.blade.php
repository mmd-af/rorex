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
                    data-info="Modal 1 Content" onclick="CustomRequest()">
                    Send New Request <i class="fa-solid fa-square-arrow-up-right"></i>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <a class="text-white text-decoration-none" href="{{ route('user.staffRequests.archived') }}">Archive <i
                            class="fa-solid fa-square-arrow-up-right"></i></a>

                </div>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div></div>
        <div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="staffRequestTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Tracking Number</th>
                        <th>Description</th>
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
                    <h1 class="modal-title fs-5" id="LeaveRequestLabel">create a new Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveForm">
                        <div class="mb-3 lh-lg h5">
                            name:
                            @if (empty(Auth::user()->employee->last_name))
                                last name:
                                <input type="text" class="form-control" name="last_name" id="last_name" value="">
                            @else
                                <text class="text-primary"> {{ Auth::user()->employee->last_name }}</text>
                                <input type="hidden" name="last_name" value="{{ Auth::user()->employee->last_name }}">
                            @endif
                            @if (empty(Auth::user()->employee->first_name))
                                first name:
                                <input type="text" class="form-control" name="first_name" id="first_name" value="">
                            @else
                                <text class="text-primary"> {{ Auth::user()->employee->first_name }} </text>
                                <input type="hidden" name="first_name" value="{{ Auth::user()->employee->first_name }}">
                            @endif
                            Code Staff:
                            @if (empty(Auth::user()->employee->staff_code))
                                <input type="text" class="form-control" name="staff_code" id="staff_code" value="">
                            @else
                                <text class="text-primary">{{ Auth::user()->employee->staff_code }}</text>
                                <input type="hidden" name="staff_code" value="{{ Auth::user()->employee->staff_code }}">
                            @endif
                            Department:
                            @if (empty(Auth::user()->employee->department))
                                <input type="text" class="form-control" name="department" id="department" value="">
                            @else
                                <text class="text-primary">{{ Auth::user()->employee->department }}</text>
                                <input type="hidden" name="department" value="{{ Auth::user()->employee->department }}">
                            @endif
                            <div class="row mt-4" id="datesForLeave">
                            </div>
                            <div class="row mt-4 p-5" id="modalSubject">
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
                                    <label for="departmentRole" class="col-form-label">Referred to:</label>
                                    <select class="form-control" name="departmentRole" id="departmentRole"
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
        function CustomRequest() {
            let datesForLeave = document.getElementById('datesForLeave');
            datesForLeave.innerHTML = ``;
            let modalSubject = document.getElementById('modalSubject');
            let start_date = new Date();
            modalSubject.innerHTML = `
<input type="hidden" name="kind" value="CustomRequest">
<input type="hidden" class="form-control" name="vacation_day" value="0">
<input type="hidden" class="form-control" name="start_date" value="${start_date.toISOString().split('T')[0]}">
<div class="mb-4">
                            <label for="subject" class="col-form-label">Subject:</label>
                            <select class="form-control" name="subject" id="subject" onchange="handelRequestWithSubject()"
                                required>
                                <option value="">-- select subject --</option>
                                <option value="Forgot Punch">Forgot Punch</option>
                                <option value="Forgot Bring My Cart">Forgot Bring My Cart</option>
                                <option value="OverTime">OverTime</option>
                                <option value="Work at Home (Remote)">Work at Home (Remote)</option>
                                <option value="Mission">Mission</option>
                                <option value="Change Shift">Change Shift</option>
                                <option value="other">other</option>
                            </select>
                        </div>
                        <div class="mb-4" id="descriptionData">
                        </div>`;
        }

        function MissionRequest() {
            let datesForLeave = document.getElementById('datesForLeave');
            datesForLeave.innerHTML = ``;
            let modalSubject = document.getElementById('modalSubject');
            let start_date = new Date();
            modalSubject.innerHTML = `
            <input type="hidden" name="kind" value="CustomRequest">
            <input type="hidden" class="form-control" name="vacation_day" value="0">
            <input type="hidden" class="form-control" name="start_date" value="${start_date.toISOString().split('T')[0]}">
            <label for="subject">Subject:</label>
            <input type="text" class="form-control" name="subject" id="subject" value="Request for Mission" readonly>
            <label for="description">Description:</label>
            <textarea name="description" class="form-control" id="description" required>
              please consider mission on these days:
               ${start_date.toISOString().split('T')[0]}
              from --:-- to --:-- hour
    
             </textarea>`;
        }

        function handelRequestWithSubject() {
            let subject = document.getElementById('subject');
            let descriptionData = document.getElementById('descriptionData');
            descriptionData.innerHTML = ``;
            subject = subject.value;
            if (subject === "Forgot Punch") {
                descriptionData.innerHTML = `
                <label for"check-date" class="col-form-label">choose date:</label>
                <input type="date" class="form-control" id="check_date_other_request" name="check_date_other_request" value="" required>
                <label for="description" class="col-form-label">At what time?</label>
                            <input type="time" class="form-control" name="description" id="description" required>`;
            }
            if (subject === "Forgot Bring My Cart") {
                descriptionData.innerHTML =
                    `
                <label for"check-date" class="col-form-label">choose date:</label>
                <input type="date" class="form-control" id="check_date_other_request" name="check_date_other_request" value="" required>
                <label for="description" class="col-form-label">Message:</label>
                <textarea class="form-control" name="description" id="description" required>I forgot to bring my card. enter and exit time: --:-- to --:--</textarea>`;
            }
            if (subject === "OverTime") {
                descriptionData.innerHTML =
                    `
                    <label for"check-date" class="col-form-label">choose date:</label>
                    <input type="date" class="form-control" id="check_date_other_request" name="check_date_other_request" value="" required>
                   <label for="description" class="col-form-label">please describe it:</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === "Work at Home (Remote)") {
                descriptionData.innerHTML =
                    `
                <label for"check_date_other_request" class="col-form-label">write the date on list</label>
               <input type="text" class="form-control" id="check_date_other_request" name="check_date_other_request" value="Below for Work at Home (Remote)" readonly required>
                <label for="description" class="col-form-label">Message:</label>
                            <textarea class="form-control" name="description" id="description" required>I want to work at home from dd/mm/yyyy to dd/mm/yyyy from .......... to .........</textarea>`;
            }
            if (subject === "Mission") {
                descriptionData.innerHTML =
                    `
                <label for"check-date" class="col-form-label">choose date:</label>
                <input type="date" class="form-control" id="check_date_other_request" name="check_date_other_request" value="" required>
                <label for="description" class="col-form-label">please describe it:</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === "other") {
                descriptionData.innerHTML =
                    `
                <label for"check-date" class="col-form-label">choose date:</label>
               <input type="date" class="form-control" id="check_date_other_request" name="check_date_other_request" value="" required>
                <label for="description" class="col-form-label">Message:</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === "Change Shift") {
                descriptionData.innerHTML =
                    `
                <label for"check_date_other_request" class="col-form-label">write the date on list</label>
               <input type="text" class="form-control" id="check_date_other_request" name="check_date_other_request" value="Below for Change Shift" readonly required>
                <label for="description" class="col-form-label">Message:</label>
                            <textarea class="form-control" name="description" id="description" required>I request to change my shift from dd/mm/yyyy to dd/mm/yyyy from ........... to .........</textarea>`;
            }
        }
        $(document).ready(function() {
            $('#staffRequestTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('user.staffRequests.ajax.getDataTable') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        width: '10%'
                    },
                    {
                        data: 'description',
                        name: 'description'
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

        $(document).ready(function() {
            let departmentRole = document.getElementById('departmentRole');
            axios.get('{{ route('user.staffRequests.ajax.getRoles') }}')
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

        function getRelateUserWithRole() {
            let assigned_user = document.getElementById('assigned_user');
            assigned_user.innerHTML = `<div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`;
            let data = {
                role_name: departmentRole.value
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
                            `<option value="${item.id}">${item.employee.last_name} ${item.employee.first_name}</option>`;
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
            var last_name = formData.get('last_name');
            var first_name = formData.get('first_name');
            var staff_code = formData.get('staff_code');
            var department = formData.get('department');
            var startDay = formData.get('start_date');
            var endDay = formData.get('end_date');
            var vacation_day = formData.get('vacation_day');
            var email = formData.get('email');
            var departmentRole = formData.get('departmentRole');
            var subject = formData.get('subject');
            var assigned_to = formData.get('assigned_to');
            var description = formData.get('description');
            var start_time = formData.get('start_time');
            var end_time = formData.get('end_time');
            var daysWithoutPay = formData.get('daysWithoutPay');
            var leave_balance = formData.get('leave_balance');
            var kind = formData.get('kind');
            var check_date_other_request = formData.get('check_date_other_request');
            var dateOfRequest = moment().format('YYYY/MM/DD');
            if (kind === "CustomRequest") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + last_name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + staff_code + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject +
                    '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + department +
                    '<br>' +
                    '<b>Check for date: ' + check_date_other_request + ' </b>' +
                    '<br>' + description + '<br>Email: ' + email + '<hr><small>request from: dashboard/Other Requests</small>';
            }
            let data = {
                first_name: first_name,
                last_name: last_name,
                staff_code: staff_code,
                department: department,
                subject: subject,
                description: newDescription,
                start_date: startDay,
                end_date: endDay,
                vacation_day: vacation_day,
                email: email,
                departmentRole: departmentRole,
                assigned_to: assigned_to
            }
            axios.post('{{ route('user.staffRequests.ajax.store') }}', data)
                .then(function(response) {
                    location.reload();
                })
                .catch(function(error) {
                    alert(error.request.response)
                });
            let performAction = document.getElementById('performAction');
            performAction.disabled = true;
            performAction.innerHTML = `
             <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                      Loading...`;
        });
    </script>
@endsection
