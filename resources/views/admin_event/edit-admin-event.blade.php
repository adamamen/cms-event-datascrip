@extends('layouts.app')

@section('title', 'Edit Admin Event')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Admin Event</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Edit Admin Event</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            @foreach ($data as $value)
                                <div class="card-body">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="form-group col-md-4 col-12">
                                            <label>Username</label>
                                            <input name="events_id" id="events_id" value="{{ $value['event_id'] }}" hidden>
                                            <input type="text" class="form-control" value="{{ $value['username'] }}"
                                                required="" name="username" id="username" {{-- {{ $value['event_id'] == '0' ? 'readonly' : '' }} --}}>

                                            <input type="hidden" class="form-control" value="{{ $value['admin_id'] }}"
                                                required="" name="admin_id" id="admin_id">

                                            <div class="invalid-feedback"> Username is required </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Password </label>
                                            <input type="password" class="form-control"
                                                value="{{ $value['password_encrypts'] }}" required="" name="password"
                                                id="password">
                                            <div class="invalid-feedback">
                                                Password is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Full Name</label>
                                            <input type="text" class="form-control" value="{{ $value['full_name'] }}"
                                                required="" name="nama_lengkap" id="nama_lengkap">
                                            <div class="invalid-feedback">
                                                Full Name is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>E-mail</label>
                                            <input type="email" class="form-control" value="{{ $value['email'] }}"
                                                required="" name="email" id="email" autocomplete="off">
                                            <div class="invalid-feedback">
                                                E-mail is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Status</label>
                                            <select class="form-control select2" name="status" id="status"
                                                {{-- {{ $value['event_id'] == '0' ? 'disabled' : '' }} --}}>
                                                <option selected disabled>-- Please Select --</option>
                                                <option value="A" {{ $value['status'] == 'A' ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="D" {{ $value['status'] == 'D' ? 'selected' : '' }}>
                                                    Tidak Aktif</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Status is required
                                            </div>
                                        </div>
                                        {{-- Role Super Admin  --}}
                                        @if (empty(Auth::user()->divisi) && Auth::user()->event_id == 0)
                                            @if (empty($value['divisi']) && $value['event_id'] == 0)
                                                {{-- Button di hide  --}}
                                            @else
                                                <div class="form-group col-md-4 col-12">
                                                    <label>Division</label>
                                                    <select class="form-control select2" name="division" id="division">
                                                        <option selected disabled>-- Please Select --</option>
                                                        @foreach ($division as $divisi)
                                                            <option value="{{ $divisi['id'] }}"
                                                                {{ $divisi['id'] == $value['divisi'] ? 'selected' : '' }}>
                                                                {{ ucwords($divisi['name']) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Division is required
                                                    </div>
                                                </div>
                                            @endif
                                        @else
                                            @if (Auth::user()->event_id == $value['event_id'])
                                                <div class="form-group col-md-4 col-12">
                                                    <label>Event Name</label>
                                                    <input type="text" class="form-control" value="CMS Event Datascrip"
                                                        required="" name="event" id="event" autocomplete="off"
                                                        disabled>
                                                    <div class="invalid-feedback">
                                                        Event Name is required
                                                    </div>
                                                </div>
                                            @else
                                                <div class="form-group col-md-4 col-12">
                                                    <label>Event Name</label>
                                                    <select class="form-control select2" name="event" id="event"
                                                        {{-- {{ $value['event_id'] == '0' ? 'disabled' : '' }} --}}>
                                                        <option selected disabled>-- Please Select --</option>
                                                        @foreach ($event as $event)
                                                            <option value="{{ $event['id_event'] }}"
                                                                {{ $event['id_event'] == $value['event_id'] ? 'selected' : $event['id_event'] }}>
                                                                {{ $event['title'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback">
                                                        Status is required
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                    <a href="#" class="btn btn-primary mr-1" type="submit" id="btn_submit"
                                        name="btn_submit"><i class="fas fa-check"></i> Submit</a>
                                    <a href="#" class="btn disabled btn-primary btn-progress" id="btn_progress"
                                        name="btn_progress">Submit</a>
                                    <a href="#" class="btn btn-danger" type="submit" id="btn_cancel"
                                        name="btn_cancel"><i class="fas fa-xmark"></i> Cancel</a>
                                </div>
                            @endforeach
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
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

    <script>
        $("#btn_progress").hide();
        $(document).ready(function() {
            var params = "<?php echo $titleUrl; ?>";

            $("#btn_cancel").click(function() {
                swal({
                        title: 'Are you sure?',
                        text: 'Are you sure you want to go back to the previous page?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((ok) => {
                        if (ok) {
                            window.location.href = "{{ url('/') }}" + "/admin-event/" + params;
                        }
                    });
            });
        });

        $(document).ready(function() {
            var params = "<?php echo $titleUrl; ?>";

            $("#btn_submit").click(function() {
                $("#btn_progress").show();
                $("#btn_submit").hide();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var admin_id = $('#admin_id').val();
                var event = $('#event').val();
                var username = $('#username').val();
                var password = $('#password').val();
                var nama_lengkap = $('#nama_lengkap').val();
                var status = $('#status').val();
                var events_id = $('#events_id').val();
                var email = $('#email').val();
                var formData = new FormData();
                var strongPasswordRegex =
                    /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                if (!strongPasswordRegex.test(password)) {
                    swal({
                        title: 'Weak Password',
                        text: 'Password must be at least 8 characters long, include uppercase, lowercase, numbers, and special characters.',
                        icon: 'warning',
                    }).then(() => {
                        $("#btn_progress").hide();
                        $("#btn_submit").show();
                    });
                    return;
                }

                formData.append("admin_id", admin_id);
                formData.append("event", event);
                formData.append("username", username);
                formData.append("password", password);
                formData.append("nama_lengkap", nama_lengkap);
                formData.append("status", status);
                formData.append("events_id", events_id);
                formData.append("email", email);

                if (email == "") {
                    var name = "E-mail";
                    var content = document.createElement('div');
                    content.innerHTML = '<strong>' + name +
                        '</strong> cannot be empty, please try again';
                    swal({
                        title: 'Warning',
                        content: content,
                        icon: "warning",
                    }).then(() => {
                        $("#btn_progress").hide();
                        $("#btn_submit").show();
                    });
                } else {
                    $.ajax({
                        url: '{{ route('update-admin') }}',
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            var alerts = response.message
                            $("#btn_progress").hide();
                            $("#btn_submit").show();

                            if (alerts == "success") {
                                swal('Success',
                                    'Data has been successfully updated and email has been sent.',
                                    'success').then(
                                    () => {
                                        window.location.href = "{{ url('/') }}" +
                                            "/admin-event/" + params;
                                    });
                            } else {
                                swal('Failed', 'Failed to update data', 'warning');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $("#btn_progress").hide();
                            $("#btn_submit").show();

                            console.log(textStatus, errorThrown);
                        }
                    });
                }
            });
        });
    </script>
@endpush
