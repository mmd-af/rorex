@extends('admin.layouts.index')

@section('title')
    Manage Requests
@endsection
@section('style')
    <style>
        #box {
            border: 2px solid black;
            padding: 2px;
        }
    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Manage Requests</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="d-flex justify-content-between">
        <div>
            @role('Support')
                <a class="btn btn-outline-info my-2" href="{{ route('admin.manageRequests.fullLetters') }}">Full Letters</a>
            @endrole
        </div>
        <div>
            <a class="btn btn-outline-warning my-2 text-warning"
                href="{{ route('admin.manageRequests.archived') }}">Archive</a>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="manageRequestTable" class="table table-bordered table-striped text-center">
                <thead>
                    <tr>
                        <th>Requests</th>
                        <th>Status</th>
                        <th>Sign</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Requests</th>
                        <th>Status</th>
                        <th>Sign</th>
                        <th>Action</th>
                    </tr>
                </tfoot>

                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="setReferred" tabindex="-1" aria-labelledby="setReferredLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="setReferredLabel">Leave Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="setReferredForm" action="{{ route('admin.manageRequests.store') }}" method="post">
                        @csrf
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
                        <div class="row">
                            <div class="col-12 mt-3">
                                <label for="description">description: (optional)</label>
                                <textarea class="form-control" name="description" id="description" cols="20" rows="4"></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="letter_assign_id" id="letter_assign_id" value="">
                        <div class="modal-footer mt-3">
                            <button type="submit" class="btn btn-success">Send</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#manageRequestTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.manageRequests.ajax.getDataTable') }}",
                columns: [{
                        data: 'requests',
                        name: 'requests',
                        width: '50%'
                    },
                    {
                        data: 'progress',
                        name: 'progress',
                        width: '20%'
                    },
                    {
                        data: 'sign',
                        name: 'sign',
                        width: '10%'
                    },
                    {
                        data: 'action',
                        name: 'action',
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

        function handleSign(event, id) {
            if (!confirm('Do you want to submit the signature for this item?')) {
                event.preventDefault();
            } else {
                event.preventDefault();
                axios.post("{{ route('admin.manageRequests.ajax.sign') }}", {
                        id: id
                    })
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error(error);
                    });
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

        function setReferred(id) {
            let letter_assign_id = document.getElementById('letter_assign_id');
            letter_assign_id.value = id;
        }

        function setPass(id) {
            var confirmationMessage = prompt(
                'Are you sure for Accept this? You can write a message for sender (optional):');
            if (confirmationMessage || confirmationMessage === '') {
                axios.post("{{ route('admin.manageRequests.ajax.setPass') }}", {
                        id: id,
                        confirmationMessage: confirmationMessage
                    })
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }

        function setRejected(id) {
            var confirmationMessage = prompt(
                'Are you sure for Reject this? You can write a message for sender (optional):');
            if (confirmationMessage || confirmationMessage === '') {
                axios.post("{{ route('admin.manageRequests.ajax.setReject') }}", {
                        id: id,
                        confirmationMessage: confirmationMessage
                    })
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }
    </script>
@endsection
