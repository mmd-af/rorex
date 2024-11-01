@extends('user.layouts.index')

@section('title')
    {{ __('leaves.staff_requests') }}
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
    <style>
        .custom-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">{{ __('leaves.staff_requests') }}</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#LeaveRequest"
                    data-info="Modal 1 Content">
                    {{ __('leaves.send_new_request') }} <i class="fa-solid fa-square-arrow-up-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 border p-3 m-2">
            <h6 class="text-success"> {{ __('leaves.remaining_allowable_leave') }}
                {{ number_format(Auth::user()->employee->leave_balance / 8, 2) }}
                {{ __('leaves.days') }}
                ({{ Auth::user()->employee->leave_balance }} {{ __('leaves.hour') }})</h6>
        </div>
        <div class="col-xl-3 col-md-6 border p-3 m-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-success">{{ __('leaves.leaved_days_statistics') }}</h6>
                    <div class="mb-3">
                        <select id="year" name="year" class="form-select" onchange="getLeaveDays(event)">
                            <option value="">-- {{ __('leaves.select_year') }} --</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div id="resultLeaveDays"></div>
                    <p class="text-warning">
                        {{ __('leaves.note_statistic_from') }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 border p-3 m-2">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title text-success">{{ __('leaves.hourly_Leaved_statistics') }}</h6>
                    <div class="mb-3">
                        <select id="year" name="year" class="form-select" onchange="getHourlyLeave(event)">
                            <option value="">-- {{ __('leaves.select_year') }} --</option>
                            <option value="2024">2024</option>
                        </select>
                    </div>
                    <div id="resultHourlyLeave"></div>
                    <p class="text-warning">{{ __('leaves.note_statistic_from') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="staffRequestTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>{{ __('leaves.tracking_number') }}</th>
                        <th>{{ __('leaves.start_date') }}</th>
                        <th>{{ __('leaves.end_date') }}</th>
                        <th>{{ __('leaves.type') }}</th>
                        <th>{{ __('leaves.value') }}</th>
                        <th>{{ __('leaves.file') }}</th>
                        <th>{{ __('leaves.status') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('leaves.tracking_number') }}</th>
                        <th>{{ __('leaves.start_date') }}</th>
                        <th>{{ __('leaves.end_date') }}</th>
                        <th>{{ __('leaves.type') }}</th>
                        <th>{{ __('leaves.value') }}</th>
                        <th>{{ __('leaves.file') }}</th>
                        <th>{{ __('leaves.status') }}</th>
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
                    <h1 class="modal-title fs-5" id="LeaveRequestLabel">{{ __('leaves.leave_request') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="leaveForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 lh-lg h5">
                            @if (empty(Auth::user()->employee->last_name))
                                {{ __('leaves.last_name') }}
                                <input type="text" class="form-control" name="last_name" id="last_name" value="">
                            @else
                                <input type="hidden" name="last_name" value="{{ Auth::user()->employee->last_name }}">
                            @endif
                            @if (empty(Auth::user()->employee->first_name))
                                {{ __('leaves.first_name') }}
                                <input type="text" class="form-control" name="first_name" id="first_name" value="">
                            @else
                                <input type="hidden" name="first_name" value="{{ Auth::user()->employee->first_name }}">
                            @endif
                            @if (empty(Auth::user()->employee->staff_code))
                                {{ __('leaves.staff_code') }}
                                <input type="text" class="form-control" name="staff_code" id="staff_code" value="">
                            @else
                                <input type="hidden" name="staff_code" value="{{ Auth::user()->employee->staff_code }}">
                            @endif
                            @if (empty(Auth::user()->employee->department))
                                {{ __('leaves.department') }}
                                <input type="text" class="form-control" name="department" id="department" value="">
                            @else
                                <input type="hidden" name="department" value="{{ Auth::user()->employee->department }}">
                            @endif
                            <div class="col-md-6">
                                <label for="type">{{ __('leaves.type') }}:</label>
                                <select class="form-select" name="type" id="type"
                                    onchange="actionForSelectType(event)" required>
                                    <option selected>-- {{ __('leaves.select_type') }} --</option>
                                    <option value="Allowed Leave">{{ __('leaves.allowed_leave') }}</option>
                                    <option value="Medical Leave">{{ __('leaves.medical_leave') }}</option>
                                    <option value="Speacial Event Leave">{{ __('leaves.speacial_event_leave') }}</option>
                                    <option value="Hourly Leave">{{ __('leaves.hourly_leave') }}</option>
                                    <option value="Without Paid Leave">{{ __('leaves.without_paid_leave') }}</option>
                                    <option value="Without Paid Hourly Leave">{{ __('leaves.without_paid_hourly_leave') }}
                                    </option>
                                </select>
                            </div>
                            <div class="row mt-4" id="datesForLeave">
                            </div>
                            <div class="row mt-4" id="modalSubject">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="col-form-label">{{ __('leaves.your_email') }}</label>
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
                                    <label for="departmentRole"
                                        class="col-form-label">{{ __('leaves.referred_to') }}</label>
                                    <select class="form-select" name="departmentRole" id="departmentRole"
                                        onchange="getRelateUserWithRole()" required>
                                        <option value="">{{ __('leaves.select_department') }}</option>
                                    </select>
                                </div>
                                <div class="col-sm-12 col-lg-6">
                                    <label for="assigned_to"
                                        class="col-form-label">{{ __('leaves.assigned_to') }}</label>
                                    <div id="assigned_user">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3"
                            id="performAction">{{ __('leaves.send') }}</button>
                    </form>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('leaves.close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var translations = {
            start_date: "{{ __('leaves.start_date') }}",
            end_date: "{{ __('leaves.end_date') }}",
            second_date_before_first_date: "{{ __('leaves.second_date_before_first_date') }}",
            days_excluding_holiday: "{{ __('leaves.days_excluding_holiday') }}",
            days_request_more_total: "{{ __('leaves.days_request_more_total') }}",
            use_without_paid_leave: "{{ __('leaves.use_without_paid_leave') }}",
            total_requested_days: "{{ __('leaves.total_requested_days') }}",
            excluding_holidays: "{{ __('leaves.excluding_holidays') }}",
            totally: "{{ __('leaves.totally') }}",
            unpaid_leave: "{{ __('leaves.unpaid_leave') }}",
            explain: "{{ __('leaves.explain') }}",
            attached_file_required: "{{ __('leaves.attached_file_required') }}",
            date: "{{ __('leaves.date') }}",
            start_time: "{{ __('leaves.start_time') }}",
            end_time: "{{ __('leaves.end_time') }}",
            end_time_cannot_be_earlier_start: "{{ __('leaves.end_time_cannot_be_earlier_start') }}",
            dont_remaining_allowable_leave: "{{ __('leaves.dont_remaining_allowable_leave') }}",
            consider_without_paid: "{{ __('leaves.consider_without_paid') }}",
            you_have_remaining_paid: "{{ __('leaves.you_have_remaining_paid') }}",
            i_accept: "{{ __('leaves.i_accept') }}"
        };
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
                                    <label for="startDate" class="form-label">${translations.start_date}</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(1)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">${translations.end_date}</label>
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
                                    <label for="startDate" class="form-label">${translations.start_date}</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(3)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">${translations.end_date}</label>
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
            let leave_balance = "{{ number_format(Auth::user()->employee->leave_balance, 2) }}";


            if (startDateValue !== '' && endDateValue !== '') {
                var startDate = new Date(startDateValue);
                var endDate = new Date(endDateValue);
                if (endDate < startDate) {
                    document.getElementById('dateDifference').innerHTML =
                        `<p class="text-danger">${translations.second_date_before_first_date}</p>`;
                    return;
                }
                var timeDifference = Math.abs(endDate - startDate + 1);
                var dayDifference = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
                let numberOfExcludingHolidays;
                do {
                    numberOfExcludingHolidays = prompt(translations.days_excluding_holiday);
                } while (numberOfExcludingHolidays === null || numberOfExcludingHolidays.trim() === '' || isNaN(
                        numberOfExcludingHolidays) || !Number.isInteger(parseFloat(numberOfExcludingHolidays)));
                numberOfExcludingHolidays = parseInt(numberOfExcludingHolidays, 10);
                let showInformation = document.getElementById('dateDifference');
                if (x === 1) {
                    if ((leave_balance / 8) < numberOfExcludingHolidays) {
                        showInformation.innerHTML +=
                            `<div class="m-3">
                         <h4 class="text-danger">${translations.days_request_more_total}</h4>
                         <h6 class="text-info">${translations.use_without_paid_leave}</h6>
                         <input type="hidden" name="leave_days" value="${numberOfExcludingHolidays}" required>
                      </div>`;
                    } else {
                        showInformation.innerHTML =
                            `<div class="mt-2">
                        <p class="text-primary">${translations.total_requested_days} = ${dayDifference} days</p>
                        <p class="text-info">${translations.excluding_holidays} = ${numberOfExcludingHolidays} days</p>
                        <input type="hidden" name="totally" value="${dayDifference}">
                        <input type="hidden" name="leave_balance" value="${leave_balance}">
                        <input type="hidden" name="leave_days" value="${numberOfExcludingHolidays}" required>
                    </div>`;
                    }
                }
                if (x === 2) {
                    showInformation.innerHTML =
                        `<div class="mt-3">
                        <p class="text-primary">${translations.totally} = ${dayDifference} days</p>
                        <p class="text-info">${translations.excluding_holidays} = ${numberOfExcludingHolidays} days</p>
                        <input type="hidden" name="leave_days" value="${numberOfExcludingHolidays}">
                        <input type="hidden" name="totally" value="${dayDifference}">
                        <input type="hidden" name="leave_balance" value="${leave_balance}">
                    </div>`;
                }
                if (x === 3) {
                    showInformation.innerHTML =
                        `<div class="mt-2">
                        <p class="text-primary">${translations.total_requested_days} = ${dayDifference} days</p>
                        <p class="text-info">${translations.excluding_holidays} = ${numberOfExcludingHolidays} days</p>
                        <p class="text-danger">${translations.unpaid_leave}</p>
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
                                    <label for="startDate" class="form-label">${translations.start_date}</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(2)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">${translations.end_date}</label>
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
                                <label for="description">${translations.explain}</label>
                                <input type="text" class="form-control" name="description" id="description" value="" required>
                                </div> 
                                <div class="mb-3">
                                   <label for="file">${translations.file}:</label>
                                   <input type="file" class="form-control" name="file" id="file" required/>
                                  <small id="file" class="form-text text-muted">${translations.attached_file_required}</small>
                                  </div>`;
        }

        function LeaveRequestForSpecialEvents(datesForLeave) {
            datesForLeave.innerHTML = `
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">${translations.start_date}</label>
                                    <input type="date" name="start_date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference(2)" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">${translations.end_date}</label>
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
                                <label for="description">${translations.explain}:</label>
                                <input type="text" class="form-control" name="description" id="description" value="" required>
                                </div> 
                                <div class="mb-3">
                                   <label for="file">${translations.file}:</label>
                                   <input type="file" class="form-control" name="file" id="file" required/>
                                  <small id="file" class="form-text text-muted">${translations.attached_file_required}</small>
                                  </div>`;
        }

        function LeaveRequestForHour(datesForLeave, targetValue) {
            datesForLeave.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label for="startDate" class="form-label">${translations.date}</label>
                    <input type="date" name="start_date" class="form-control" id="startDate" required>
                </div>
            </div>
            <div class="col-md-6">
                <label for="startTime" class="form-label">${translations.start_time}</label>
                <input type="time" name="start_time" class="form-control" id="startTime" onchange="calculateTimeDifference('${targetValue}')" required>
            </div>
            <div class="col-md-6">
                <label for="endTime" class="form-label">${translations.end_time}</label>
                <input type="time" name="end_time" class="form-control" id="endTime" onchange="calculateTimeDifference('${targetValue}')" required>
            </div>
            <div class="col-md-6">
                <h6 id="timeDifference"></h6>
            </div>`;
        }


        function actionForSelectType(event) {
            let datesForLeave = document.getElementById('datesForLeave');
            let targetValue = event.target.value;
            datesForLeave.innerHTML = ``;
            if (targetValue === "Allowed Leave") {
                LeaveRequestForRest(datesForLeave)
            }
            if (targetValue === "Medical Leave") {
                LeaveRequestForMedicalLeave(datesForLeave)
            }
            if (targetValue === "Speacial Event Leave") {
                LeaveRequestForSpecialEvents(datesForLeave)
            }
            if (targetValue === "Hourly Leave" || targetValue === "Without Paid Hourly Leave") {
                LeaveRequestForHour(datesForLeave, targetValue)
            }
            if (targetValue === "Without Paid Leave") {
                LeaveRequestWithoutPaid(datesForLeave)
            }
        }

        function calculateTimeDifference(targetValue) {
            var startTimeValue = document.getElementById('startTime').value.trim();
            var endTimeValue = document.getElementById('endTime').value.trim();
            let leave_balance = "{{ number_format(Auth::user()->employee->leave_balance, 2) }}";
            if (startTimeValue !== '' && endTimeValue !== '') {
                var startTime = new Date('1970-01-01T' + startTimeValue + ':00Z').getTime();
                var endTime = new Date('1970-01-01T' + endTimeValue + ':00Z').getTime();
                if (endTime < startTime) {
                    document.getElementById('timeDifference').innerHTML =
                        `<p class="text-danger">${translations.end_time_cannot_be_earlier_start}</p>`;
                    return;
                }
                let modalSubject = document.getElementById('modalSubject');
                var timeDifferenceInMilliseconds = Math.abs(endTime - startTime);
                var hours = Math.floor(timeDifferenceInMilliseconds / (1000 * 60 * 60));
                var minutes = Math.floor((timeDifferenceInMilliseconds % (1000 * 60 * 60)) / (1000 * 60));
                document.getElementById('timeDifference').innerHTML =
                    `<p class="text-success">${hours} hours and ${minutes} minutes</p>
             <input type="hidden" name="leave_time" value="${hours}:${minutes}">
             <input type="hidden" name="leave_balance" value="${leave_balance}">`;
                if (leave_balance < (hours + (minutes / 60))) {
                    modalSubject.innerHTML = `
                    <input type="hidden" name="subject" value="Without Paid Hourly Leave">`;
                    document.getElementById('timeDifference').innerHTML +=
                        `<p class="text-danger">${translations.dont_remaining_allowable_leave}</p>
                         <div class="custom-checkbox p-3 border shadow-lg">
                         <input onClick="changeTypeValueHourlyLeave(event)" class="" type="checkbox" id="consider_as_without_paid_leave" name="consider_as_without_paid_leave" value="true">
                         <label class="text-warning mx-2" for="consider_as_without_paid_leave">${translations.consider_without_paid}</label>
                         </div>`;

                }
                if (targetValue === "Without Paid Hourly Leave") {
                    modalSubject.innerHTML = `
                    <input type="hidden" name="subject" value="Without Paid Hourly Leave">`;
                    document.getElementById('timeDifference').innerHTML +=
                        `<p class="text-danger">${translations.you_have_remaining_paid}</p>
                         <div class="custom-checkbox p-3 border shadow-lg">
                         <input onClick="changeTypeValueHourlyLeave(event)" class="" type="checkbox" id="consider_as_without_paid_leave" name="consider_as_without_paid_leave" value="true">
                         <label class="text-warning mx-2" for="consider_as_without_paid_leave">${translations.i_accept}</label>
                         </div>`;
                } else {
                    modalSubject.innerHTML = `
                    <input type="hidden" name="subject" value="Hourly Leave">`;
                }
            }
        }

        function changeTypeValueHourlyLeave(event) {
            let changeType = document.getElementById('type');
            if (event.target.checked) {
                changeType.value = "Without Paid Hourly Leave";
            } else {
                changeType.value = "Hourly Leave";
            }
        }

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
                    <select class="form-select" name="assigned_to" id="assigned_to" required>
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
            var departament = formData.get('department');
            var type = formData.get('type');
            var file = formData.get('file');
            let startDay = formData.get('start_date');
            let endDay = formData.get('end_date');
            var totally = formData.get('totally');
            var leave_time = formData.get('leave_time');
            var leave_days = formData.get('leave_days');
            var email = formData.get('email');
            var departmentRole = formData.get('departmentRole');
            var subject = formData.get('subject');
            var assigned_to = formData.get('assigned_to');
            var description = formData.get('description');
            var start_time = formData.get('start_time');
            var end_time = formData.get('end_time');
            var consider_as_without_paid_leave = formData.get('consider_as_without_paid_leave');
            var daysWithoutPay = formData.get('daysWithoutPay');
            var leave_balance = formData.get('leave_balance');
            var check_date_other_request = formData.get('check_date_other_request');
            var dateOfRequest = moment().format('YYYY/MM/DD');
            if (type === "Medical Leave" || type === "Speacial Event Leave") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + last_name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + staff_code + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject +
                    '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>Requests <b>' + leave_days + ' days</b> during the period:<br><b>' + startDay +
                    '</b>until:<b> ' + endDay +
                    '</b><br>Total days: ' + totally +
                    '<br>Total work days (EXCLUDING Holidays): ' + leave_days +
                    '<br><b>' + description + '</b><br>Email: ' + email +
                    '<hr><small>request from: dashboard/Leave Requests</small>';
            }
            if (type === "Allowed Leave") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + last_name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + staff_code + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject +
                    '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>Requests <b>' + leave_days + ' days</b> during the period:<br><b>' + startDay +
                    '</b>until:<b> ' + endDay +
                    '</b><br>Total days: ' + totally +
                    '<br>Allowed leave when the user send this request: ' + formatNumber(leave_balance / 8) +
                    ' days (' +
                    leave_balance +
                    ' Hour)<br>Total work days (EXCLUDING Holidays): ' + leave_days +
                    '<br>Email: ' + email + '<hr><small>request from: dashboard/Leave Requests</small>';
            }
            if (type === "Without Paid Leave") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + last_name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + staff_code + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject +
                    '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>Requests <b>' + leave_days + ' days</b> during the period:<br><b>' + startDay +
                    '</b>until:<b> ' + endDay +
                    '</b><br>Total days: ' + totally +
                    '<br>Allowed leave: ' + formatNumber(leave_balance / 8) + ' days (' + leave_balance +
                    ' Hour)<br>Total work days (EXCLUDING Holidays): ' + leave_days +
                    '<br>Days without Pay: ' +
                    daysWithoutPay + '<br>Email: ' + email +
                    '<hr><small>request from: dashboard/Leave Requests</small>';
            }
            if (type === "Hourly Leave" || type === "Without Paid Hourly Leave") {
                var newDescription = 'Date: ' + dateOfRequest +
                    '<br><div id="box">Name: ' + last_name + ' ' + first_name + '<br>' +
                    'Code Staff: ' + staff_code + '</div><br>' +
                    '<div id="alignCenter"><b>' + subject +
                    '</b></div><br>as an Employee of S.C. ROREX PIPE S.R.L. in the Department of: ' + departament +
                    '<br>requests for hour leave on:<br><b>' + startDay +
                    '</b><br>between:<b> ' + start_time + ' </b>until: <b>' + end_time + ' </b><br>' +
                    leave_time + ' Hour' +
                    '<br>Allowed leave when the user send this request: ' + formatNumber(leave_balance / 8) +
                    ' days (' +
                    leave_balance + ' Hour )' +
                    '<br>Email: ' + email + '<hr><small>request from: dashboard/Leave Requests</small>';

                let startHourDate = startDay;
                startDay = `${startHourDate} ${start_time}:00`;
                endDay = `${startHourDate} ${end_time}:00`;
            }
            let data = {
                first_name: first_name,
                last_name: last_name,
                staff_code: staff_code,
                departament: departament,
                subject: subject,
                type: type,
                file: file,
                description: newDescription,
                start_date: startDay,
                end_date: endDay,
                consider_as_without_paid_leave: consider_as_without_paid_leave,
                leave_time: leave_time,
                leave_days: leave_days,
                email: email,
                departmentRole: departmentRole,
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

        function formatNumber(number, decimals = 2) {
            var factor = Math.pow(10, decimals);
            return (Math.ceil(number * factor) / factor).toFixed(decimals);
        }
    </script>
@endsection
