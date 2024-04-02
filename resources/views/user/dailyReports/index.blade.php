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
    <div class="alert alert-primary" id="last_update"></div>
    @include('user.layouts.partial.errors')
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
                        <th>cod_staff</th>
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
                        <div class="mb-3">
                            <label for="cod_staff" class="col-form-label">Staff:
                                <div class="text-info" id="name_show"></div>
                                <div class="text-info" id="cod_staff_show"></div>
                            </label>
                            <input type="hidden" id="first_name" name="first_name" value="">
                            <input type="hidden" id="name" name="name" value="">
                            <input type="hidden" id="departament" name="departament" value="">
                            <input type="hidden" id="email" name="email" value="">
                            <input type="hidden" id="cod_staff" name="cod_staff" value="">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="col-form-label">Date:
                                <div class="text-info" id="date_show"></div>
                            </label>
                            <input type="hidden" id="date" name="date" value="">
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="col-form-label">Subject:</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="">
                        </div>
                        <div class="mb-3">
                            <label for="departamentRole" class="col-form-label">Referred to:</label>
                            <select class="form-control" name="departamentRole" id="departamentRole"
                                onchange="getRelateUserWithRole()">
                                <option value="">SELECT DEPARTMENT</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            User:
                            <label for="assigned_to" class="col-form-label">Referred to:</label>
                            <div id="assigned_user">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="col-form-label">Message:</label>
                            <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Send message</button>

                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
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
            let departamentRole = document.getElementById('departamentRole');
            axios.get('{{ route('user.dailyReports.ajax.getRoles') }}')
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
            axios.post('{{ route('user.dailyReports.ajax.getUserWithRole') }}', data)
                .then(function(response) {
                    assigned_user.innerHTML = `
                    <select class="form-control" name="assigned_to" id="assigned_to">
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

        function requestForm(id, data) {
            let alert = document.getElementById('alert');
            let name = document.getElementById('name');
            let first_name = document.getElementById('first_name');
            let departament = document.getElementById('departament');
            let email = document.getElementById('email');
            let name_show = document.getElementById('name_show');
            let cod_staff = document.getElementById('cod_staff');
            let cod_staff_show = document.getElementById('cod_staff_show');
            let date = document.getElementById('date');
            let date_show = document.getElementById('date_show');
            date.value = data;
            date_show.innerHTML = data;
            let configInformation = {
                id: id
            }
            axios.post('{{ route('user.dailyReports.ajax.getData') }}', configInformation)
                .then(function(response) {
                    alert.innerHTML = ``;
                    name.value = response.data.data.name;
                    first_name.value = response.data.data.first_name;
                    departament.value = response.data.data.departament;
                    email.value = response.data.data.email;
                    name_show.innerHTML = response.data.data.name;
                    cod_staff.value = response.data.data.cod_staff;
                    cod_staff_show.innerHTML = response.data.data.cod_staff;
                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        $(document).ready(function() {
            let last_update = document.getElementById('last_update');
            last_update.innerHTML = `<div class="spinner-border" role="status">
            <span class="visually-hidden">Loading...</span>
            </div>`;

            axios.post('{{ route('user.dailyReports.ajax.getLastUpdate') }}')
                .then(function(response) {
                    const formattedDateTime = moment(response.data.updated_at).format('YYYY-MM-DD HH:mm:ss');
                    last_update.innerHTML = `Last Update: ${formattedDateTime}`;
                })
                .catch(function(error) {
                    console.error(error);
                });
        });
    </script>
@endsection
