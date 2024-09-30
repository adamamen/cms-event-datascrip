@extends('layouts.app')

@section('title', 'View Link QR')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <style>
        .image-thumbnail {
            max-width: 250px;
            max-height: 250px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>View Link QR</h1>
            </div>
            <div class="row">
                <div class="form-group col-md-4 col-12">
                    <label>QR Code</label>
                    <input type="text" class="form-control" value="" required="" id="qr_code"
                        placeholder="Enter your QR code" autocomplete="off" maxlength="10">
                    <div class="invalid-feedback">
                        QR Code Wajib Diisi
                    </div>
                </div>
            </div>
            {{-- <button type="button" class="btn btn-success" id="submitQrCode">Verify QR Code</button> --}}
        </section>
    </div>

@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/simpleweather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('library/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
    <script>
        $(document).ready(function() {
            function verifyQRCode(qrCode) {
                $.ajax({
                    url: "{{ route('verify.qr') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        qr_code: qrCode
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            swal({
                                title: "Success",
                                text: "QR Code is valid! Welcome " + response.visitorName,
                                icon: "success",
                            });

                            $('#qr_code').val('');
                        } else {
                            swal({
                                title: "Error",
                                text: response.message,
                                icon: "error",
                            });
                        }
                    },
                    error: function(response) {
                        var message = response.responseJSON.message ||
                            "Invalid QR Code. Please try again.";
                        swal({
                            title: "Error",
                            text: message,
                            icon: "error",
                        });
                    }
                });
            }

            $('#qr_code').on('input', function() {
                var qrCode = $(this).val();

                if (qrCode.length === 10) {
                    verifyQRCode(qrCode);
                }
            });
        });
    </script>
@endpush
