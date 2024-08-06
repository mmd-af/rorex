@extends('user.layouts.index')

@section('title')
    Monthly Reports
@endsection
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Monthly Reports</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <form class="form-control px-5">
                @csrf
                <label for="date">Select Date:</label>
                <select id="date" name="date" class="form-control">
                    <?php
                    $startMonth = 3;
                    $startYear = 2024;
                    $currentMonth = date('n');
                    $currentYear = date('Y');
                    $totalMonths = ($currentYear - $startYear) * 12 + ($currentMonth - $startMonth + 1);
                    for ($i = $totalMonths - 1; $i >= 0; $i--) {
                        $monthValue = (($startMonth + $i - 1) % 12) + 1;
                        $yearValue = $startYear + floor(($startMonth + $i - 1) / 12);
                        $formattedMonth = sprintf('%02d', $monthValue);
                        $dateOutput = "$yearValue-$formattedMonth";
                        $monthName = date('F', mktime(0, 0, 0, $monthValue, 1, $yearValue));
                        echo "<option value=\"$dateOutput\">$monthName $yearValue</option>";
                    }
                    ?>
                </select>
                <button type="button" class="btn btn-primary mt-3" onclick="monthlyReportWithDate()">Show
                </button>
            </form>
            <div id="showResult"></div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function monthlyReportWithDate() {
            let showResult = document.getElementById('showResult');
            var date = document.getElementById('date').value;
            showResult.innerHTML = `
                    <div class="row justify-content-center">
                        <div class="spinner-grow text-primary m-3 p-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="spinner-grow text-secondary m-3 p-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="spinner-grow text-secondary m-3 p-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="spinner-grow text-primary m-3 p-3" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>`;
            formData = {
                date: date
            }
            axios.post('{{ route('user.monthlyReports.ajax.monthlyReportWithDate') }}', formData)
                .then(function(response) {
                    let codeStaff = response.data.codeStaff;
                    let monthDate = response.data.monthDate;
                    let url = "{{ route('user.monthlyReports.userMonthlyReportExport') }}"
                    showResult.innerHTML = `
            <div class="row justify-content-between">
               <div class="col"></div>
                   <div class="col">
                        <form method="POST" action="${url}">
                             @csrf
                            <input type="hidden" name="cod_staff" value="${codeStaff}">
                            <input type="hidden" name="date" value="${monthDate}">
                             <button type="submit" class="btn btn-outline-success float-end">
                                <i class="fa-solid fa-file-csv fa-xl"></i>
                            </button>
                        </form>
                </div>
                </div>
                 <div class="row justify-content-center mt-5">
                                    <table class="table table-striped border text-center table-responsive p-5 rounded-3">
                                        <thead>
                                        <tr>
                                            <th scope="col">Total</th>
                                            <th scope="col">value</th>
                                            <th scope="col">unit</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>Night</td>
                                            <td>${response.data.hourNight}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Morning</td>
                            <td>${response.data.hourMorning}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Afternoon</td>
                            <td>${response.data.hourAfternoon}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Daily</td>
                            <td>${response.data.hourDaily}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>plus_week_day</td>
                            <td class="bg-success text-light">${response.data.plus_week_day}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>plus_week_night</td>
                            <td class="bg-success text-light">${response.data.plus_week_night}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>plus_holiday_day</td>
                            <td class="bg-success text-light">${response.data.plus_holiday_day}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>plus_holiday_night</td>
                            <td class="bg-success text-light">${response.data.plus_holiday_night}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Compensation</td>
                            <td class="bg-warning">
                                <b>${response.data.compensation}</b><hr>
                                <small>If this number is negative, it will be automatically deducted from your working hours</small>
                                </td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <th>Daily Absence</th>
                            <th class="bg-danger">${response.data.dailyAbsence}</th>
                            <th>per day</th>
                        </tr>
                        <tr>
                            <th>Allowed Leave</th>
                            <th class="bg-info">${response.data.concediu_ore}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Without Paid Leave</th>
                            <th class="bg-info">${response.data.without_paid_leave}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Total working hours</th>
                            <th><h4 class="shadow bg-white rounded-3"><strong>${response.data.totalHours}</strong></h4></th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Unknown</th>
                            <th>${response.data.hourUnknown}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Default Shift</th>
                            <th>${response.data.turaImplicita}</th>
                            <th>per hour</th>
                        </tr>
                        </tbody>
                    </table>
                    </div>`;
                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        function calculateTotalNotAllowed(data) {
            const ot_ore = Number(data.ot_ore) || 0;
            const plus_week_day = Number(data.plus_week_day) || 0;
            const plus_week_night = Number(data.plus_week_night) || 0;
            const plus_holiday_day = Number(data.plus_holiday_day) || 0;
            const plus_holiday_night = Number(data.plus_holiday_night) || 0;
            const delayWork = Number(data.delayWork) || 0;
            const earlyExit = Number(data.earlyExit) || 0;
            const result = ot_ore - (plus_week_day + plus_week_night + plus_holiday_day + plus_holiday_night) - (delayWork +
                earlyExit);
            return result.toFixed(2);
        }
    </script>
@endsection
