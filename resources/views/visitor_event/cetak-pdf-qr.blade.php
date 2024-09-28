<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }

        .qr-code {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1>QR Code for {{ $visitor->full_name }}</h1>
    <div class="qr-code">
        <img src="{{ public_path($qrCodePath) }}" alt="QR Code" width="200">
    </div>
    <h3>Barcode Number: {{ $visitor->barcode_no }}</h3>
</body>

</html>
