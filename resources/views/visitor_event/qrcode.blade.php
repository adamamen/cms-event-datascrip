<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            flex-direction: column;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>{{ ucwords($masterEvent[0]->title) }}</h1>
        <img src="{{ $visitor->barcode_link }}" alt="QR Code" class="img-fluid">
        <br>
        <h3>Barcode Number: {{ $visitor->barcode_no }}</h3>
    </div>
</body>

</html>
