@extends('user.layouts.index')

@section('title')
    Users
@endsection
@section('style')

@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Support</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="d-flex justify-content-between my-3">
        <div></div>
        <div>
            <button type="button"
                    class="btn btn-outline-info" data-bs-toggle="modal"
                    data-bs-target="#sendMessageSupport">
                Send Message <i class="fa-solid fa-square-arrow-up-right"></i>
            </button>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="supportTable" class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Tracking Number</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Organization</th>
                    <th>Date of Request</th>
                    <th>Read At</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Tracking Number</th>
                    <th>Subject</th>
                    <th>Description</th>
                    <th>Organization</th>
                    <th>Date of Request</th>
                    <th>Read At</th>
                </tr>
                </tfoot>
                <body>
                </body>
            </table>
        </div>
    </div>
    <div class="modal fade" id="sendMessageSupport" tabindex="-1" aria-labelledby="sendMessageSupportLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="sendMessageSupportLabel">Message</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('user.supports.store')}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="cod_staff" class="col-form-label">Code Staff:</label>
                            @if(empty(Auth::user()->cod_staff))
                                <input type="text" class="form-control" name="cod_staff" id="cod_staff" value="">
                            @else
                                <p class="text-primary">{{Auth::user()->cod_staff}}</p>
                                <input type="hidden" name="cod_staff" value="{{Auth::user()->cod_staff}}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="name" class="col-form-label">Name:</label>
                            @if(empty(Auth::user()->name))
                                <input type="text" class="form-control" name="name" id="name" value="">
                            @else
                                <p class="text-primary">{{Auth::user()->name}}</p>
                                <input type="hidden" name="name" value="{{Auth::user()->name}}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="email" class="col-form-label">Email:</label>
                            @if(empty(Auth::user()->email))
                                <input type="text" class="form-control" name="email" id="email" value="">
                            @else
                                <p class="text-primary">{{Auth::user()->email}}</p>
                                <input type="hidden" name="email" value="{{Auth::user()->email}}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="col-form-label">Subject: *</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="">
                        </div>
                        <div class="mb-3">
                            <label for="organization" class="col-form-label">Organization:</label>
                            <select class="form-control" name="organization" id="organization">
                                <option value="manager">Manager</option>
                                <option value="accounting">Accounting</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="col-form-label">Message: *</label>
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
        $(document).ready(function () {
            $('#supportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('user.supports.ajax.getDataTable') }}",
                columns: [
                    {data: 'id', name: 'id', width: '10%'},
                    {data: 'subject', name: 'subject'},
                    {data: 'description', name: 'description'},
                    {data: 'organization', name: 'organization', width: '10%'},
                    {data: 'created_at', name: 'created_at', width: '10%'},
                    {data: 'read_at', name: 'read_at', width: '10%'}
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
    </script>
@endsection

