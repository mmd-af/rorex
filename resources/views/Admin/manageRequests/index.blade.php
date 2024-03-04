@extends('admin.layouts.index')

@section('title')
    Manage Requests
@endsection
@section('style')

@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Manage Requests</li>
    </ol>
    @include('admin.layouts.partial.errors')
    <div class="d-flex justify-content-between">
        <div></div>
        <div>
            <a class="btn btn-outline-warning btn-sm text-warning" href="{{route('admin.manageRequests.archived')}}">Archive</a>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="manageRequestTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Date of Request</th>
                    <th>User Name</th>
                    <th>Requests</th>
                    <th>Sign</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Date of Request</th>
                    <th>User Name</th>
                    <th>Requests</th>
                    <th>Sign</th>
                    <th>Action</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="setReferred" tabindex="-1"
         aria-labelledby="setReferredLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="setReferredLabel">Leave Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="setReferredForm" action="{{route('admin.manageRequests.store')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <label for="departamentRole" class="col-form-label">Referred to:</label>
                                <select class="form-control" name="departamentRole" id="departamentRole"
                                        onclick="getRelateUserWithRole()">
                                    <option value="">SELECT DEPARTMENT</option>
                                </select>
                            </div>
                            <div class="col-6">
                                User:
                                <label for="assigned_to" class="col-form-label">Referred to:</label>
                                <select class="form-control" name="assigned_to" id="assigned_to">
                                    <option value="">SELECT USER</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="letter_assign_id" id="letter_assign_id" value="">
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
        $(document).ready(function () {
            $('#manageRequestTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.manageRequests.ajax.getDataTable') }}",
                columns: [
                    {data: 'created_at', name: 'created_at', width: '10%'},
                    {data: 'user', name: 'user', width: '10%'},
                    {data: 'requests', name: 'requests', width: '60%'},
                    {data: 'sign', name: 'sign', width: '10%'},
                    {data: 'action', name: 'action', width: '20%'}
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

        function handleSign(event, id) {
            if (!confirm('Do you want to submit the signature for this item?')) {
                event.preventDefault();
            } else {
                event.preventDefault();
                axios.post("{{route('admin.manageRequests.ajax.sign')}}", {id: id})
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error(error);
                    });
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

        function setReferred(id) {
            let letter_assign_id = document.getElementById('letter_assign_id');
            letter_assign_id.value = id;
        }

        function setPass(id) {
            if (confirm('Do you want to submit the signature for this item?')) {
                axios.post("{{route('admin.manageRequests.ajax.setPass')}}", {id: id})
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }

        function setRejected(id) {
            if (confirm('Do you want to submit the signature for this item?')) {
                axios.post("{{route('admin.manageRequests.ajax.setReject')}}", {id: id})
                    .then(response => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        }

        function printDescription(id) {
            axios.post("{{route('admin.manageRequests.ajax.getDescriptionForPrint')}}", {id: id})
                .then(response => {
                    printContent(response.data);
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function printContent(content) {
            var printWindow = window.open('', '_blank');
            let imageUrl = "{{asset('build/img/logo.png')}}";
            printWindow.document.write('<html><head><title>Print Description</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('@media print {');
            printWindow.document.write('  body { background-color: white; display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }');
            printWindow.document.write('  .description-container { border: 2px solid #009799; padding: 20px; }');
            printWindow.document.write('  .logo-container { grid-column: span 2; text-align: center; }');
            printWindow.document.write('  .logo-container img { max-width: 100%; height: auto; }');
            printWindow.document.write('  img { max-width: 100%; height: auto; display: block; margin: 0 auto; }');
            printWindow.document.write('  @page { size: A4 landscape; }');
            printWindow.document.write('}');
            printWindow.document.write('</style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<div class="logo-container">');
            printWindow.document.write('<img src="' + imageUrl + '" alt="Rorex - Pipe">');
            printWindow.document.write('</div>');
            printWindow.document.write('<div class="description-container">');
            printWindow.document.write(content);
            printWindow.document.write('</div>');
            printWindow.document.write('<div class="description-container">');
            printWindow.document.write(content);
            printWindow.document.write('</div>');
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();




        }
    </script>

@endsection
