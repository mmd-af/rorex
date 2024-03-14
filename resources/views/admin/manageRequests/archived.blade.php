@extends('admin.layouts.index')

@section('title')
    Support
@endsection
@section('style')
    <style id="printStyle">
        @page {
            size: landscape;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .page {
            width: 210mm;
            height: 148mm;
            padding: 0;
            margin: 0 auto;
            border: 1px solid #ccc;
            display: flex;
        }

        .content-container {
            flex: 1;
            margin: 5mm;
            border: 2px solid #009799;
            padding: 5mm;
            box-sizing: border-box;
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
            width: 25%;
        }

        #statusPrint, #statusPrint th, #statusPrint td {
            border: 1px solid #c0c0c0;
            border-collapse: collapse;
        }

        #box {
            border: 2px solid black;
            padding: 2px;
        }

        #alignCenter {
            text-align: center;
        }

        #printSection {
            zoom: 150%;
        }
    </style>
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
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#supportTable').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 25,
                ajax: "{{ route('admin.manageRequests.ajax.getArchiveDataTable') }}",
                columns: [
                    {data: 'created_at', name: 'created_at', width: '10%'},
                    {data: 'user', name: 'user', width: '10%'},
                    {data: 'requests', name: 'requests', width: '60%'},
                    {data: 'status', name: 'status', width: '10%'},
                    {data: 'action', name: 'action', width: '10%'}
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

        function printDescription(id) {
            let content = ``;
            axios.post("{{route('admin.manageRequests.ajax.getDescriptionForPrint')}}", {id: id})
                .then(response => {
                    content = response.data.data[0].request.description;
                    content += `<table id="statusPrint" style="font-size: xx-small">
                       <tr>
                            <th>Applicant</th>
                            <th>to Department</th>
                            <th>Assigned_to</th>
                            <th>Approve</th>
                            <th>status</th>
                       </tr>`;
                    response.data.data.forEach(function (item) {
                        content += `<tr style="border: 1px solid #000;">
                            <td>${item.user.name}</td>
                            <td>${item.role.name}</td>
                            <td>${item.assigned_to.name}</td>
                            <td>${item.signed_by ? 'Sign' : ''}</td>
                            <td>${item.status}</td>
                                    </tr>`;
                    });
                    content += `</table>`;
                    printContent(content);
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function printContent(content) {
            var printStyle = document.getElementById('printStyle');
            let imageUrl = "{{asset('build/img/logo.png')}}";
            var newWin = window.open('', 'Print-Window');
            newWin.document.open();
            newWin.document.write(
                '<html><head><style>' + printStyle.innerHTML + '</style></head><body id="printSection" onload="window.print()">' +
                '<div class="page" id="printPage"><div class="content-container"><div class="logo-container">' +
                '<img class="img-fluid w-25" src="' + imageUrl + '" alt="Rorex - Pipe"></div><div class="message">' + content + '</div>' +
                '</div><div class="content-container"><div class="logo-container">' +
                '<img class="img-fluid w-25" src="' + imageUrl + '" alt="Rorex - Pipe"></div>' +
                '<div class="message">' + content + '</div></div></div>' +
                '</body></html>');
            newWin.document.close();
            setTimeout(function () {
                newWin.close();
            }, 10);
        }
    </script>
@endsection

