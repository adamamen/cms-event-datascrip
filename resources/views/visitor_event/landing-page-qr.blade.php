@extends('layouts.app')

@section('title', 'View Link QR')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <style>
        .card-header h1,
        .card-header h4 {
            text-align: center;
            width: 100%;
            margin: 0 auto;
        }

        .form-group {
            text-align: center;
        }

        .form-control {
            width: 50%;
            margin: 0 auto;
        }

        .custom-card-height {
            height: 100%;
            min-height: 70vh;
        }

        @media (max-width: 1024px) {
            .custom-card-height {
                height: auto;
            }
        }

        .custom-section {
            display: flex;
            justify-content: center;
            padding-right: 16%;
        }

        @media (max-width: 1024px) {
            .custom-section {
                padding-right: 5%;
            }
        }

        @media (max-width: 768px) {
            .custom-section {
                padding-right: 0%;
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content"
        style="background-image: url('{{ asset('img/BG-LANDING-PAGE-SCAN-QR-EVENT.jpg') }}'); height:50px;">
        <section class="section custom-section">
            <br><br>
            <div class="row">
                <div class="col-12">
                    <div class="card custom-card-height">
                        <div class="card-header text-center">
                            <h1 class="font-weight-bold mb-6">Event</h1>
                        </div>
                        <div class="card-header text-center">
                            <h1 class="font-weight-bold mb-6">{{ ucwords($page[0]['title']) }}</h1>
                        </div>
                        <div class="card-header text-center">
                            <h4 class="font-weight-bold mb-6">{{ ucwords($page[0]['location']) }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="qr_code" class="font-weight-bold"><span style="color:red">*</span> QR Code
                                </label>
                                <div class="container">
                                    <input type="text" class="form-control" id="qr_code" required
                                        placeholder="Enter your QR code" autocomplete="off" maxlength="10">
                                    <p>
                                        <span style="color:red">*</span>
                                        <span style="font-size: 14px;"><strong>Enter 10 characters in the input
                                                form</strong></span>
                                    </p>
                                </div>
                                <div class="invalid-feedback">
                                    QR Code Wajib Diisi
                                </div>
                            </div>
                            <p>
                                <center>
                                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptate in velit neque quod
                                    perspiciatis eligendi sapiente adipisci, blanditiis ex, molestiae esse tempore? Error
                                    ipsam,
                                    suscipit cupiditate voluptatum tenetur inventore tempore.
                                </center>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

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
        //         $(document).ready(function() {
        //             function verifyQRCode(qrCode) {
        //                 $.ajax({
        //                     url: "{{ route('verify.qr') }}",
        //                     type: "POST",
        //                     dataType: 'json',
        //                     data: {
        //                         _token: "{{ csrf_token() }}",
        //                         qr_code: qrCode
        //                     },
        //                     success: function(response) {
        //                         if (response.status === 'success') {
        //                             var name = response.visitorName;
        //                             var content = document.createElement('div');
        //                             content.innerHTML = 'QR Code is valid! <br> Welcome <strong>' + name +
        //                                 '</strong>';
        //                             swal({
        //                                 title: 'Sukses',
        //                                 content: content,
        //                                 icon: "success",
        //                             });
        // 
        //                             $('#qr_code').val('');
        //                         } else if (response.status === 'error_arrival') {
        //                             var name = response.message;
        //                             var content = document.createElement('div');
        //                             content.innerHTML =
        //                                 'QR Code sudah pernah digunakan. Silahkan coba lagi. <br> <strong>' +
        //                                 name + '</strong>';
        //                             swal({
        //                                 title: 'Error',
        //                                 content: content,
        //                                 icon: "error",
        //                             });
        // 
        //                             $('#qr_code').val('');
        //                         } else {
        //                             swal({
        //                                 title: 'Error',
        //                                 content: content,
        //                                 icon: "error",
        //                             });
        // 
        //                             $('#qr_code').val('');
        //                         }
        //                     },
        //                     error: function(response) {
        //                         var message = response.responseJSON.message ||
        //                             "Invalid QR Code. Please try again.";
        //                         swal({
        //                             title: "Error",
        //                             text: message,
        //                             icon: "error",
        //                         });
        // 
        //                         $('#qr_code').val('');
        //                     }
        //                 });
        //             }
        // 
        //             $('#qr_code').on('input', function() {
        //                 var qrCode = $(this).val();
        // 
        //                 if (qrCode.length === 10) {
        //                     verifyQRCode(qrCode);
        //                 }
        //             });
        //         });

        //         $(document).ready(function() {
        //             $('#qr_code').focus();
        // 
        //             function verifyQRCode(qrCode) {
        //                 $.ajax({
        //                     url: "{{ route('verify.qr') }}",
        //                     type: "POST",
        //                     dataType: 'json',
        //                     data: {
        //                         _token: "{{ csrf_token() }}",
        //                         qr_code: qrCode
        //                     },
        //                     success: function(response) {
        //                         if (response.status === 'success') {
        //                             var name = response.visitorName;
        //                             var content = document.createElement('div');
        //                             content.innerHTML = 'QR Code is valid! <br> Welcome <strong>' + name +
        //                                 '</strong>';
        //                             swal({
        //                                 title: 'Sukses',
        //                                 content: content,
        //                                 icon: "success",
        //                             }).then(() => {
        //                                 $('#qr_code').focus();
        //                             });
        // 
        //                             $('#qr_code').val('');
        //                         } else if (response.status === 'error_arrival') {
        //                             var name = response.message;
        //                             var content = document.createElement('div');
        //                             content.innerHTML =
        //                                 'QR Code sudah pernah digunakan. Silahkan coba lagi. <br> <strong>' +
        //                                 name + '</strong>';
        //                             swal({
        //                                 title: 'Error',
        //                                 content: content,
        //                                 icon: "error",
        //                             }).then(() => {
        //                                 $('#qr_code').focus();
        //                             });
        // 
        //                             $('#qr_code').val('');
        //                         } else {
        //                             swal({
        //                                 title: 'Error',
        //                                 content: content,
        //                                 icon: "error",
        //                             }).then(() => {
        //                                 $('#qr_code').focus();
        //                             });
        // 
        //                             $('#qr_code').val('');
        //                         }
        //                     },
        //                     error: function(response) {
        //                         var message = response.responseJSON.message ||
        //                             "Invalid QR Code. Please try again.";
        //                         swal({
        //                             title: "Error",
        //                             text: message,
        //                             icon: "error",
        //                         }).then(() => {
        //                             $('#qr_code').focus();
        //                         });
        // 
        //                         $('#qr_code').val('');
        //                     }
        //                 });
        //             }
        // 
        //             $('#qr_code').on('input', function() {
        //                 var qrCode = $(this).val();
        // 
        //                 if (qrCode.length === 10) {
        //                     verifyQRCode(qrCode);
        //                 }
        //             });
        //         });

        $(document).ready(function() {
            function keepFocusOnQRCode() {
                $('#qr_code').focus();
            }

            keepFocusOnQRCode();

            $(document).on('click', function(event) {
                if (!$(event.target).closest('#qr_code').length) {
                    keepFocusOnQRCode();
                }
            });

            function verifyQRCode(qrCode) {
                swal({
                    title: "Processing scan",
                    text: "Please wait...",
                    icon: "info",
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false
                });

                $.ajax({
                    url: "{{ route('verify.qr') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        qr_code: qrCode
                    },
                    success: function(response) {

                        swal.close();

                        if (response.status === 'success') {
                            var name = response.visitorName;
                            var content = document.createElement('div');
                            content.innerHTML = 'QR Code is valid! <br> Welcome <strong>' + name +
                                '</strong>';
                            swal({
                                title: 'Success',
                                content: content,
                                icon: "success",
                            }).then(() => {
                                keepFocusOnQRCode();
                            });

                            $('#qr_code').val('');
                        } else if (response.status === 'error_arrival') {
                            var name = response.message;
                            var content = document.createElement('div');
                            content.innerHTML =
                                'The QR Code has already been used. Please try again. <br> <strong>' +
                                name + '</strong>';
                            swal({
                                title: 'Error',
                                content: content,
                                icon: "error",
                            }).then(() => {
                                keepFocusOnQRCode();
                            });

                            $('#qr_code').val('');
                        } else {
                            swal({
                                title: 'Error',
                                content: content,
                                icon: "error",
                            }).then(() => {
                                keepFocusOnQRCode();
                            });

                            $('#qr_code').val('');
                        }
                    },
                    error: function(response) {

                        swal.close();

                        var message = response.responseJSON.message ||
                            "Invalid QR Code. Please try again.";
                        swal({
                            title: "Error",
                            text: message,
                            icon: "error",
                        }).then(() => {
                            keepFocusOnQRCode();
                        });

                        $('#qr_code').val('');
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
