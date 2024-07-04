<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body>
    <div>
        <label for="year">سال:</label>
        <input type="number" id="year" name="year">
        <button onclick="getLeaveDays()">مشاهده روزهای مرخصی</button>
    </div>
    <div id="result"></div>

    <script>
        function getLeaveDays() {
            const year = document.getElementById('year').value;

            axios.get('{{ route('leave.days') }}', {
                params: {
                    year: year
                }
            })
            .then(response => {
                document.getElementById('result').innerText = 'مجموع روزهای مرخصی: ' + response.data.total_leave_days;
            })
            .catch(error => {
                console.error(error);
            });
        }
    </script>
</body>
</html>
