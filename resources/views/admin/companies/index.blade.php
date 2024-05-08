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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="showLabel">Companies</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th colspan="2">Information</th>
                                    </tr>
                                </thead>
                                <tbody id="company_information">
                                </tbody>
                            </table>
                        </div>
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
            let companyInformation = document.getElementById('company_information');
            companyInformation.innerHTML = ``;
            let data = {
                id: id
            }
            axios.post("{{ route('admin.companies.ajax.show') }}", data)
                .then(response => {
                    companyInformation.innerHTML = `<tr>
                                        <th scope="row">Company Name</th>
                                        <td>${response.data.company_name}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Activity Domain</th>
                                        <td>${response.data.activity_domain}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Vat id</th>
                                        <td>${response.data.vat_id}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Registration Number</th>
                                        <td>${response.data.registration_number}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Country</th>
                                        <td>${response.data.country}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">County</th>
                                        <td>${response.data.county}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">City</th>
                                        <td>${response.data.city}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Zip Code</th>
                                        <td>${response.data.zip_code}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Address</th>
                                        <td>${response.data.address}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Building</th>
                                        <td>${response.data.building}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Person Name</th>
                                        <td>${response.data.person_name}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Job Title</th>
                                        <td>${response.data.job_title}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Phone Number</th>
                                        <td>${response.data.phone_number}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email</th>
                                        <td>${response.data.users.email}</td>
                                    </tr>`;

                })
                .catch(error => {
                    console.error('Error:', error);
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
