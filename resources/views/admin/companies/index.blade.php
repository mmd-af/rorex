@extends('admin.layouts.index')

@section('title')
    Companies
@endsection
@section('style')
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Companies</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="companyTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Activity Domain</th>
                        <th>City</th>
                        <th>Email</th>
                        <th>is_active</th>
                        <th>action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Company Name</th>
                        <th>Activity Domain</th>
                        <th>City</th>
                        <th>Email</th>
                        <th>is_active</th>
                        <th>action</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="show" tabindex="-1" aria-labelledby="ShowLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showLabel">Companies</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form id="editForm" action="" method="post">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="company_name">Company Name:</label>
                                <input type="text" id="edit-company_name" name="company_name" class="form-control"
                                    required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="activity_domain">Activity Domain:</label>
                                <input type="text" id="edit-activity_domain" name="activity_domain" class="form-control"
                                    required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="vat_id">Vat ID:</label>
                                <input type="text" id="edit-vat_id" name="vat_id" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="registration_number">Registration Number:</label>
                                <input type="text" id="edit-registration_number" name="registration_number"
                                    class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="country">Country:</label>
                                <input type="text" id="edit-country" name="country" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="county">County:</label>
                                <input type="text" id="edit-county" name="county" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="city">City:</label>
                                <input type="text" id="edit-city" name="city" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="zip_code">Zip Code:</label>
                                <input type="text" id="edit-zip_code" name="zip_code" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="address">Address:</label>
                                <input type="text" id="edit-address" name="address" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="building">Building:</label>
                                <input type="text" id="edit-building" name="building" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="person_name">Person Name:</label>
                                <input type="text" id="edit-person_name" name="person_name" class="form-control"
                                    required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="job_title">Job Title:</label>
                                <input type="text" id="edit-job_title" name="job_title" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="phone_number">Phone Number:</label>
                                <input type="text" id="edit-phone_number" name="phone_number" class="form-control"
                                    required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="email">Email:</label>
                                <input type="text" id="edit-email" name="email" class="form-control" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Submit</button>
                        </form>
                    </div>
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
        $(document).ready(function() {
            $('#companyTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.companies.ajax.getDataTable') }}",
                columns: [{
                        data: 'company_name',
                        name: 'company_name'
                    },
                    {
                        data: 'activity_domain',
                        name: 'activity_domain'
                    },
                    {
                        data: 'city',
                        name: 'city'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active'
                    },
                    {
                        data: 'action',
                        name: 'action'
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

        function show(id) {
            let editForm = document.getElementById("editForm");
            let url = "{{ route('admin.companies.update', ':id') }}";
            url = url.replace(':id', id);
            editForm.setAttribute("action", url);
            let data = {
                id: id
            }
            axios.post("{{ route('admin.companies.ajax.show') }}", data)
                .then(function(response) {
                    const data = response.data;
                    document.getElementById('edit-company_name').value = data.company_name;
                    document.getElementById('edit-activity_domain').value = data.activity_domain;
                    document.getElementById('edit-vat_id').value = data.vat_id;
                    document.getElementById('edit-registration_number').value = data.registration_number;
                    document.getElementById('edit-country').value = data.country;
                    document.getElementById('edit-county').value = data.county;
                    document.getElementById('edit-city').value = data.city;
                    document.getElementById('edit-zip_code').value = data.zip_code;
                    document.getElementById('edit-address').value = data.address;
                    document.getElementById('edit-building').value = data.building;
                    document.getElementById('edit-person_name').value = data.person_name;
                    document.getElementById('edit-job_title').value = data.job_title;
                    document.getElementById('edit-phone_number').value = data.phone_number;
                    document.getElementById('edit-email').value = data.users.email;
                })
                .catch(function(error) {
                    console.log(error);
                });
        }

        function handleActive(event, id) {
            event.preventDefault();
            axios.post("{{ route('admin.companies.ajax.active') }}", {
                    id: id
                })
                .then(response => {
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                });

        }
    </script>
@endsection
