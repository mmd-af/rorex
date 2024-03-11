@extends('admin.layouts.index')

@section('title')
    Support
@endsection
@section('style')

@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Support</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="supportTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Tracking Number</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>organization</th>
                    <th>Read By</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Tracking Number</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>organization</th>
                    <th>Read By</th>
                    <th>Action</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="messgeRequest" tabindex="-1" aria-labelledby="messgeRequestLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="messgeRequestLabel">Message</h1>
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
                    <div id="showMessage">

                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <form action="{{ route('admin.supports.reArchiveMessage') }}" method="post">
                            @csrf
                            @method('put')
                            <input type="hidden" name="support_id" id="support_id" value="">
                            <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure?')">
                               ReStore
                            </button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#supportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.supports.ajax.getArchiveDataTable') }}",
                columns: [
                    {data: 'id', name: 'id', width: '10%'},
                    {data: 'name', name: 'subject'},
                    {data: 'subject', name: 'subject'},
                    {data: 'organization', name: 'organization', width: '10%'},
                    {data: 'read_by', name: 'read_by', width: '10%'},
                    {data: 'button', name: 'button', orderable: false, searchable: false, width: '10%'}
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

        let alert = document.getElementById('alert');
        let showMessage = document.getElementById('showMessage');

        function showMessageModal(id) {
            $('#messgeRequest').modal('show');
            let support_id = document.getElementById('support_id');
            support_id.value = id;
            let config = {
                id: id
            }
            axios.post('{{ route('admin.supports.ajax.archivedShow') }}', config)
                .then(function (response) {
                    alert.innerHTML = ``;
                    const dateString = response.data.created_at;
                    const date = new Date(dateString);
                    const formattedDate = date.toISOString().split('T')[0];
                    showMessage.innerHTML = `
                        <table class="table table-striped table-responsive-sm table-responsive">

                            <th>Tracking</th>
                            <th>Name</th>
                            <th>Code Staff</th>
                            <th>Email</th>
                            <th>Mobile Phone</th>
                            <th>Organization</th>
                            <th>Date Of Request</th>

                            <tr>
                                <td>${response.data.id}</td>
                                <td>${response.data.name}</td>
                                <td>${response.data.cod_staff}</td>
                                <td>${response.data.email}</td>
                                <td>${response.data.mobile_phone}</td>
                                <td>${response.data.organization}</td>
                                <td>${formattedDate}</td>
                            </tr>

                        </table>
                        <div class="p-5 m-3">
                            <h3>${response.data.subject}</h3>
                            <p>
                               ${response.data.description}

                            </p>
                        </div>
                    `;
                })
                .catch(function (error) {
                    console.error(error);
                });
        }

        $(document).on('hidden.bs.modal', function () {
            location.reload(true);
        });

    </script>
@endsection

