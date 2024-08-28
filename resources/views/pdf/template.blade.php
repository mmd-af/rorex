<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Export</title>
    <style>
        @page {
            size: vertical;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
        }

        .page {
            /* height: 210mm; */
            /* width: 148mm; */
            padding: 0;
            margin: 0 auto;
            border: 1px solid #ccc;
            display: flex;
        }

        .content-container {
            flex: 1;
            margin: 5mm;
            border: 2px solid #009799;
            padding: 10mm;
            box-sizing: border-box;
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
            width: 25%;
        }

        #statusPrint,
        #statusPrint th,
        #statusPrint td {
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

        /* #printSection {
            zoom: 300%;
        } */
    </style>
</head>

<body>
    <div class="page" id="printPage">
        <div class="content-container">
            <div class="logo-container">
                <img class="img-fluid w-25" src="{{ asset('build/img/logo.png') }}">
            </div>
            <div class="message">
                {!! $description !!}
                <table id="statusPrint" style="font-size: xx-small">
                    <tr>
                        <th>Applicant</th>
                        <th>to Department</th>
                        <th>Assigned_to</th>
                        <th>Approve</th>
                        <th>status</th>
                    </tr>`;
                    @foreach ($data as $assign)
                        <tr style="border: 1px solid #000;">
                            <td>{{ $assign->user->name }} {{ $assign->user->first_name }}</td>
                            <td>{{ $assign->role->name }}</td>
                            <td>{{ $assign->assignedTo->name }} {{ $assign->assignedTo->first_name }}</td>
                            <td>{{ $assign->signed_by ? 'Sign' : '' }}</td>
                            <td>{{ $assign->status }}</td>
                        </tr>
                        @if (isset($assign->description))
                            <tr>
                                <td style="color:red" colspan="5"><small>{{ $assign->description }}</small></td>
                            </tr>
                        @endif
                        @if ($loop->last)
                        <tr>
                           <th colspan="5"> Tracking number= {{ $assign->request_id }} </th>
                        <tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</body>

</html>
