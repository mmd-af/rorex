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
                    $selectMonths = 4;
                    $currentMonth = date('n');
                    $currentYear = date('Y');
                    for ($i = 0; $i < 12; $i++) {
                        $monthValue = (($currentMonth - $i + 12) % 12) + 1;
                        $yearValue = $currentYear + floor(($currentMonth - $i - 1) / 12);
                        if ($monthValue <= $currentMonth && $i < $selectMonths) {
                            $formattedMonth = sprintf('%02d', $monthValue);
                            $dateOutput = "$yearValue-$formattedMonth";
                            $monthName = date('F', mktime(0, 0, 0, $monthValue, 1, $yearValue));
                            echo "<option value=\"$dateOutput\">$monthName $yearValue</option>";
                        }
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
                                    <table class="table table-striped border text-center table-responsive p-5">
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
                            <td>Total Overtime Work</td>
                            <td class="bg-info text-light">${response.data.ot_ore}</td>
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
                            <td>Delay Work</td>
                            <td class="bg-warning">${response.data.delayWork}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <td>Early Exit</td>
                            <td class="bg-warning">${response.data.earlyExit}</td>
                            <td>per hour</td>
                        </tr>
                        <tr>
                            <th>Daily Absence</th>
                            <th class="bg-danger">${response.data.dailyAbsence}</th>
                            <th>per day</th>
                        </tr>
                        <tr>
                            <th>Concediu ore</th>
                            <th class="bg-warning">${response.data.concediu_ore}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Without Paid Leave</th>
                            <th class="bg-warning">${response.data.without_paid_leave}</th>
                            <th>per hour</th>
                        </tr>
                        <tr>
                            <th>Total working hours</th>
                            <th>${response.data.totalHours}</th>
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
                        <tr>
                            <th>Forgot Punch</th>
                            <th>${response.data.forgotPunch}</th>
                            <th>pcs</th>
                        </tr>
                        </tbody>
                    </table>
                    </div>`;
                })
                .catch(function(error) {
                    console.error(error);
                });
        }
    </script>
@endsection
