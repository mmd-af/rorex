<!DOCTYPE html>
<html>

<head>
    <title>Leave Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Leave Report</h1>
    <table>
        <thead>
            <tr>
                <th>Tracking Number</th>
                <th>User Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Type</th>
                <th>Leave Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($leaves as $leave)
                <tr>
                    <td>{{ $leave->id }}/{{ $leave->request_id }}</td>
                    <td>{{ $leave->users->employee->last_name . " " . $leave->users->employee->first_name }}</td>
                    <td>{{ $leave->formatted_start_date }}</td>
                    <td>{{ $leave->formatted_end_date }}</td>
                    <td>{{ $leave->type }}</td>
                    <td>{{ $leave->formatted_leave_value }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
