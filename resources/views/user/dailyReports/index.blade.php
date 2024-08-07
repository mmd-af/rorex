@extends('user.layouts.index')

@section('title')
    {{ __('general.daily_reports') }}
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">{{ __('general.daily_reports') }}</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <form class="form-control px-5">
                @csrf
                <label for="date">{{ __('general.select_date') }}:</label>
                <select id="date" name="date" class="form-control">
                    <?php
                    $startMonth = 3;
                    $startYear = 2024;
                    $currentMonth = date('n');
                    $currentYear = date('Y');
                    $totalMonths = ($currentYear - $startYear) * 12 + ($currentMonth - $startMonth + 1);
                    for ($i = $totalMonths - 1; $i >= 0; $i--) {
                        $monthValue = (($startMonth + $i - 1) % 12) + 1;
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
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="dailyReportTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>{{ __('general.staff_code') }}</th>
                        <th>{{ __('general.name') }}</th>
                        <th>{{ __('general.date') }}</th>
                        <th>{{ __('general.weeks') }}</th>
                        <th>{{ __('general.shift') }}</th>
                        <th>{{ __('general.on_work1') }}</th>
                        <th>{{ __('general.off_work2') }}</th>
                        <th>{{ __('general.remark') }}</th>
                        <th>{{ __('general.action') }}</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>{{ __('general.staff_code') }}</th>
                        <th>{{ __('general.name') }}</th>
                        <th>{{ __('general.date') }}</th>
                        <th>{{ __('general.weeks') }}</th>
                        <th>{{ __('general.shift') }}</th>
                        <th>{{ __('general.on_work1') }}</th>
                        <th>{{ __('general.off_work2') }}</th>
                        <th>{{ __('general.remark') }}</th>
                        <th>{{ __('general.action') }}</th>
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
                    <h1 class="modal-title fs-5" id="forgetRequestLabel">{{ __('general.request') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="alert">
                        <div class="row justify-content-center my-3">
                            <div class="spinner-grow text-primary mx-3" role="status">
                                <span class="visually-hidden">{{ __('general.loading') }}...</span>
                            </div>
                            <div class="spinner-grow text-secondary mx-3" role="status">
                                <span class="visually-hidden">{{ __('general.loading') }}...</span>
                            </div>
                        </div>
                        <div class="row justify-content-center my-3">
                            <div class="spinner-grow text-secondary mx-3" role="status">
                                <span class="visually-hidden">{{ __('general.loading') }}...</span>
                            </div>
                            <div class="spinner-grow text-primary mx-3" role="status">
                                <span class="visually-hidden">{{ __('general.loading') }}...</span>
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
                            <label for="date" class="col-form-label">{{ __('general.check_date') }}:
                                <div class="text-info" id="date_show"></div>
                            </label>
                            <input type="hidden" id="check_date" name="check_date" value="">
                        </div>
                        <div class="mb-4">
                            <label for="subject" class="col-form-label">{{ __('general.subject') }}:</label>
                            <select class="form-select" name="subject" id="subject" onchange="handelRequestWithSubject()"
                                required>
                                <option value="">-- {{ __('general.subject') }} --</option>
                                <option value="Forgot Punch">{{ __('general.forgot_punch') }}</option>
                                <option value="Forgot Bring My Cart">{{ __('general.forgot_bring_my_cart') }}</option>
                                <option value="Consider OverTime">{{ __('general.consider_overTime') }}</option>
                                <option value="Work at Home (Remote)">{{ __('general.work_home') }}</option>
                                <option value="Mission">{{ __('general.mission') }}</option>
                                <option value="Change Shift">{{ __('general.change_shift') }}</option>
                            </select>
                        </div>
                        <div class="mb-4" id="descriptionData">
                        </div>
                        <div class="mb-4">
                            <label for="departmentRole" class="col-form-label">{{ __('general.referred_to') }}:</label>
                            <select class="form-select" name="departmentRole" id="departmentRole"
                                onchange="getRelateUserWithRole()" required>
                                <option value="">{{ __('general.select_department') }}</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="assigned_to" class="col-form-label">{{ __('general.assigned_to') }}:</label>
                            <div id="assigned_user">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">{{ __('general.send_message') }}</button>
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('general.close') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var translations = {
            send_message: "{{ __('general.send_message') }}",
            forgot_punch: "{{ __('general.forgot_punch') }}",
            forgot_bring_my_cart: "{{ __('general.forgot_bring_my_cart') }}",
            forgot_bring_my_cart_des: "{{ __('general.forgot_bring_my_cart_des') }}",
            consider_as_allow_leave: "{{ __('general.consider_as_allow_leave') }}",
            consider_overtime: "{{ __('general.consider_overtime') }}",
            work_at_home: "{{ __('general.work_at_home') }}",
            mission: "{{ __('general.mission') }}",
            other: "{{ __('general.other') }}",
            change_shift: "{{ __('general.change_shift') }}",
            at_what_time: "{{ __('general.at_what_time') }}",
            message: "{{ __('general.message') }}",
            how_many_hour: "{{ __('general.how_many_hour') }}",
            please_describe_it: "{{ __('general.please_describe_it') }}",
            start_work: "{{ __('general.start_work') }}"
        };
    </script>

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
            if (subject === translations.forgot_punch) {
                descriptionData.innerHTML = `<label for="description" class="col-form-label">${translations.at_what_time}</label>
                          <input type="time" class="form-control" name="description" id="description" required>`;
            }
            if (subject === translations.forgot_bring_my_cart) {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">${translations.message}</label>
                           <textarea class="form-control" name="description" id="description" required>${translations.forgot_bring_my_cart_des}</textarea>`;
            }
            if (subject === translations.consider_as_allow_leave) {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">${translations.how_many_hour}</label>
                            <input type="number" min="0" step="any" class="form-control" name="description" id="description" required>`;
            }
            if (subject === translations.consider_overtime) {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">${translations.please_describe_it}</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === translations.work_at_home) {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">${translations.start_work}</label>
                            <textarea class="form-control" name="description" id="description" required>${translations.work_at_home}</textarea>`;
            }
            if (subject === translations.mission) {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">${translations.please_describe_it}</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === translations.other) {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">${translations.message}</label>
                            <textarea class="form-control" name="description" id="description" required></textarea>`;
            }
            if (subject === translations.change_shift) {
                descriptionData.innerHTML =
                    `<label for="description" class="col-form-label">${translations.message}</label>
                           <textarea class="form-control" name="description" id="description" required>${translations.change_shift}</textarea>`;
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
