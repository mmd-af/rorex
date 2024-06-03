<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Notification</title>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .email-container {
            background-color: #ffffff;
            padding: 20px;
            margin: 0 auto;
            width: 90%;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #009799;
            padding: 10px;
            text-align: center;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .content {
            margin: 20px 0;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #009799;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ $title }}</h1>
        </div>
        <div class="content">
            <p>{{ $content }}</p>
            <a href="{{ $button_url }}" class="button">{{ $button_text }}</a>
        </div>
    </div>
</body>

</html>
