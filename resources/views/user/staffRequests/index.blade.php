@extends('user.layouts.index')

@section('title')
    Staff Requests
@endsection
@section('style')

@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Staff Requests</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="d-flex justify-content-center my-3">
        <button type="button"
                class="btn btn-primary mx-3" data-bs-toggle="modal"
                data-bs-target="#sendRequestStaffRequest">
            Leave Request for Rest <i class="fa-solid fa-square-arrow-up-right"></i>
        </button>
        <button type="button"
                class="btn btn-primary mx-3" data-bs-toggle="modal"
                data-bs-target="#sendRequestStaffRequest">
            Leave Request for Special Events <i class="fa-solid fa-square-arrow-up-right"></i>
        </button>
        <button type="button"
                class="btn btn-primary mx-3" data-bs-toggle="modal"
                data-bs-target="#sendRequestStaffRequest">
            Request for Hourly leave <i class="fa-solid fa-square-arrow-up-right"></i>
        </button>
    </div>
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <table id="staffRequestTable" class="table table-bordered table-striped text-center">
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
    <div class="modal fade" id="sendRequestStaffRequest" tabindex="-1" aria-labelledby="sendRequestStaffRequestLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="sendRequestStaffRequestLabel">Leave Request</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{route('user.staffRequests.store')}}" method="post">
                        @csrf
                        <div class="mb-3 lh-lg h5">
                            Name:
                            @if(empty(Auth::user()->name))
                                <input type="text" class="form-control" name="name" id="name" value="">
                            @else
                                <text
                                    class="text-primary"> {{Auth::user()->name}} {{Auth::user()->prenumele_tatalui}}  </text>
                                <input type="hidden" name="name" value="{{Auth::user()->name}}">
                            @endif
                            with Code Staff:
                            @if(empty(Auth::user()->cod_staff))
                                <input type="text" class="form-control" name="cod_staff" id="cod_staff" value="">
                            @else
                                <text class="text-primary">{{Auth::user()->cod_staff}}</text>
                                <input type="hidden" name="cod_staff" value="{{Auth::user()->cod_staff}}">
                            @endif
                            as an employee of S.C. ROREX PIPE S.R.L. in the Departament of
                            @if(empty(Auth::user()->departament))
                                <input type="text" class="form-control" name="departament" id="departament" value="">
                            @else
                                <text class="text-primary">{{Auth::user()->departament}}</text>
                                <input type="hidden" name="departament" value="{{Auth::user()->departament}}">
                            @endif
                            please approve my request for vacation during the period:
                            {{--                            <input type="text" class="form-control form-control-sm" name="vacation_day" id="vacation_day" value="" placeholder="write something...">--}}
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <label for="startDate" class="form-label">Start Date:</label>
                                    <input type="date" class="form-control" id="startDate"
                                           onchange="calculateDateDifference()">
                                </div>
                                <div class="col-md-6">
                                    <label for="endDate" class="form-label">End Date:</label>
                                    <input type="date" class="form-control" id="endDate"
                                           onchange="calculateDateDifference()">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <h6 id="dateDifference"></h6>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="col-form-label">Your Email:</label>
                            @if(empty(Auth::user()->email))
                                <input type="text" class="form-control" name="email" id="email" value="">
                            @else
                                <p class="text-primary">{{Auth::user()->email}}</p>
                                <input type="hidden" name="email" value="{{Auth::user()->email}}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="organization" class="col-form-label">Organization:</label>
                            <select class="form-control" name="organization" id="organization">
                                <option value="accounting">Mr. Sorell</option>
                            </select>
                        </div>
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
            $('#staffRequestTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('user.staffRequests.ajax.getDataTable') }}",
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

        function calculateDateDifference() {
            var startDate = new Date(document.getElementById('startDate').value);
            var endDate = new Date(document.getElementById('endDate').value);
            if (endDate < startDate) {
                document.getElementById('dateDifference').innerHTML = `<p class="text-danger">The second date cannot be before the first date</p>`;
                return;
            }
            var timeDifference = Math.abs(endDate - startDate + 1);
            var dayDifference = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));
            document.getElementById('dateDifference').innerHTML = `<p class="text-success">${dayDifference} days</p>`;
        }
    </script>
@endsection
