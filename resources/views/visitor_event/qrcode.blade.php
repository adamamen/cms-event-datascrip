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
        <h1>{{ ucwords($visitorEvent[0]->full_name) }}</h1>
        <img src="{{ $visitor->barcode_link }}" alt="QR Code" id="barcode" class="img-fluid">
        <br>
        <h3>Barcode Number: {{ $visitor->barcode_no }}</h3>
    </div>

    <script>
        $(document).ready(function() {
            // Simulate QR code scanning
            $('#barcode').on('click', function() {
                const barcodeNo = '{{ $visitor->barcode_no }}';

                // AJAX request to check the scan status
                $.ajax({
                    url: '/verify-scan', // Backend route to handle the scan verification
                    type: 'POST',
                    data: {
                        barcode_no: barcodeNo,
                        _token: '{{ csrf_token() }}' // Laravel CSRF protection
                    },
                    success: function(response) {
                        if (response.status === 'already_scanned') {
                            alert('QR Code Already Used');
                        } else if (response.status === 'success') {
                            alert('Verification Success (' + response.full_name + ')');
                        } else if (response.status === 'not_found') {
                            alert('Verification not successful, QR code not valid');
                        }
                    },
                    error: function(error) {
                        console.log('Error:', error);
                    }
                });
            });
        });
    </script>
</body>

</html>
