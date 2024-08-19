@extends('user.layouts.index')

@section('title')
    {{ __('monthlyReport.monthly_report') }}
@endsection

@section('style')
@endsection

@section('content')
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">{{ __('monthlyReport.monthly_report') }}</li>
    </ol>
    @include('user.layouts.partial.errors')
    <div class="card mb-4">
        <div class="card-body table-responsive">
            <form class="form-control px-5">
                @csrf
                <label for="date">{{ __('monthlyReport.select_date') }}</label>
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
                <button type="button" class="btn btn-primary mt-3"
                    onclick="monthlyReportWithDate()">{{ __('monthlyReport.show') }}
                </button>
            </form>
            <div id="showResult"></div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var translations = {
            loading: "{{ __('monthlyReport.loading') }}",
            total: "{{ __('monthlyReport.total') }}",
            value: "{{ __('monthlyReport.value') }}",
            unit: "{{ __('monthlyReport.unit') }}",
            per_hour: "{{ __('monthlyReport.per_hour') }}",
            per_day: "{{ __('monthlyReport.per_day') }}",
            night: "{{ __('monthlyReport.night') }}",
            morning: "{{ __('monthlyReport.morning') }}",
            afternoon: "{{ __('monthlyReport.afternoon') }}",
            daily: "{{ __('monthlyReport.daily') }}",
            plus_week_day: "{{ __('monthlyReport.plus_week_day') }}",
            plus_week_night: "{{ __('monthlyReport.plus_week_night') }}",
            plus_holiday_day: "{{ __('monthlyReport.plus_holiday_day') }}",
            plus_holiday_night: "{{ __('monthlyReport.plus_holiday_night') }}",
            compensation: "{{ __('monthlyReport.compensation') }}",
            text_of_compensation: "{{ __('monthlyReport.text_of_compensation') }}",
            daily_absence: "{{ __('monthlyReport.daily_absence') }}",
            allowed_leave: "{{ __('monthlyReport.allowed_leave') }}",
            without_paid_leave: "{{ __('monthlyReport.without_paid_leave') }}",
            total_working_hours: "{{ __('monthlyReport.total_working_hours') }}",
            unknown: "{{ __('monthlyReport.unknown') }}",
            default_shift: "{{ __('monthlyReport.default_shift') }}",
        };


        function monthlyReportWithDate() {
            let showResult = document.getElementById('showResult');
            var date = document.getElementById('date').value;
            showResult.innerHTML = `
                    <div class="row justify-content-center">
                        <div class="spinner-grow text-primary m-3 p-3" role="status">
                            <span class="visually-hidden">${translations.message}</span>
                        </div>
                        <div class="spinner-grow text-secondary m-3 p-3" role="status">
                            <span class="visually-hidden">${translations.message}</span>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="spinner-grow text-secondary m-3 p-3" role="status">
                            <span class="visually-hidden">${translations.message}</span>
                        </div>
                        <div class="spinner-grow text-primary m-3 p-3" role="status">
                            <span class="visually-hidden">${translations.message}</span>
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
                            <th scope="col">${translations.total}</th>
                            <th scope="col">${translations.value}</th>
                            <th scope="col">${translations.unit}</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                            <td>${translations.night}</td>
                            <td>${response.data.hourNight}</td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <td>${translations.morning}</td>
                            <td>${response.data.hourMorning}</td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <td>${translations.afternoon}</td>
                            <td>${response.data.hourAfternoon}</td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <td>${translations.daily}</td>
                            <td>${response.data.hourDaily}</td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <td>${translations.plus_week_day}</td>
                            <td class="bg-success text-light">${response.data.plus_week_day}</td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <td>${translations.plus_week_night}</td>
                            <td class="bg-success text-light">${response.data.plus_week_night}</td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <td>${translations.plus_holiday_day}</td>
                            <td class="bg-success text-light">${response.data.plus_holiday_day}</td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <td>${translations.plus_holiday_night}</td>
                            <td class="bg-success text-light">${response.data.plus_holiday_night}</td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <td>${translations.compensation}</td>
                            <td class="bg-warning">
                                <b>${response.data.compensation}</b><hr>
                                <small>${translations.text_of_compensation}</small>
                                </td>
                            <td>${translations.per_hour}</td>
                        </tr>
                        <tr>
                            <th>${translations.daily_absence}</th>
                            <th class="bg-danger">${response.data.dailyAbsence}</th>
                            <th>${translations.per_day}</th>
                        </tr>
                        <tr>
                            <th>${translations.allowed_leave}</th>
                            <th class="bg-info">${response.data.concediu_ore}</th>
                            <th>${translations.per_hour}</th>
                        </tr>
                        <tr>
                            <th>${translations.without_paid_leave}</th>
                            <th class="bg-info">${response.data.without_paid_leave}</th>
                            <th>${translations.per_hour}</th>
                        </tr>
                        <tr>
                            <th>${translations.total_working_hours}</th>
                            <th><h4 class="shadow bg-white rounded-3"><strong>${response.data.totalHours}</strong></h4></th>
                            <th>${translations.per_hour}</th>
                        </tr>
                        <tr>
                            <th>${translations.unknown}</th>
                            <th>${response.data.hourUnknown}</th>
                            <th>${translations.per_hour}</th>
                        </tr>
                        <tr>
                            <th>${translations.default_shift}</th>
                            <th>${response.data.turaImplicita}</th>
                            <th>${translations.per_hour}</th>
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
