@extends('layouts.app')

@section('title', 'Add Admin')

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
                <h1>Add Admin</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Add Admin</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="form-group col-md-4 col-12">
                                        <label>Username</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="username" id="username">
                                        <div class="invalid-feedback">
                                            Username Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Password</label>
                                        <input type="password" class="form-control" value="" required=""
                                            name="password" id="password">
                                        <div class="invalid-feedback">
                                            Password Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Nama Lengkap</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="nama_lengkap" id="nama_lengkap">
                                        <div class="invalid-feedback">
                                            Nama Lengkap Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Event</label>
                                        <select class="form-control select2" name="event" id="event">
                                            <option selected disabled>-- Silahkan Pilih --</option>
                                            @foreach ($data as $value)
                                                <option value="{{ $value->id_event }}">{{ $value->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Status Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Status</label>
                                        <select class="form-control select2" name="status" id="status">
                                            <option selected disabled>-- Silahkan Pilih --</option>
                                            <option value="A">Aktif</option>
                                            <option value="D">Tidak Aktif</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Status Wajib Diisi
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary mr-1" type="submit" id="btn_submit"
                                    name="btn_submit"><i class="fas fa-check"></i> Submit</a>
                                <a href="#" class="btn disabled btn-primary btn-progress" id="btn_progress"
                                    name="btn_progress">Submit</a>
                                <a href="#" class="btn btn-danger" type="submit" id="btn_cancel" name="btn_cancel"><i
                                        class="fas fa-xmark"></i> Cancel</a>
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
                        title: 'Apakah kamu yakin?',
                        text: 'Apakah kamu yakin ingin kembali ke halaman sebelumnya?',
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
                var username = $('#username').val();
                var password = $('#password').val();
                var nama_lengkap = $('#nama_lengkap').val();
                var event = $('#event').val();
                var status = $('#status').val();

                var formData = new FormData();
                formData.append("username", username);
                formData.append("password", password);
                formData.append("nama_lengkap", nama_lengkap);
                formData.append("event", event);
                formData.append("status", status);

                if (username == "") {
                    var name = "Username";
                    var content = document.createElement('div');
                    content.innerHTML = '<strong>' + name +
                        '</strong> tidak boleh kosong, silahkan coba lagi...';
                    swal({
                        title: 'Warning',
                        content: content,
                        icon: "warning",
                    }).then(okay => {
                        if (okay) {
                            $("#btn_progress").hide();
                            $("#btn_submit").show();
                        }
                    });
                } else if (password == "") {
                    var name = "Password";
                    var content = document.createElement('div');
                    content.innerHTML = '<strong>' + name +
                        '</strong> tidak boleh kosong, silahkan coba lagi...';
                    swal({
                        title: 'Warning',
                        content: content,
                        icon: "warning",
                    }).then(okay => {
                        if (okay) {
                            $("#btn_progress").hide();
                            $("#btn_submit").show();
                        }
                    });
                } else if (nama_lengkap == "") {
                    var name = "Nama Lengkap";
                    var content = document.createElement('div');
                    content.innerHTML = '<strong>' + name +
                        '</strong> tidak boleh kosong, silahkan coba lagi...';
                    swal({
                        title: 'Warning',
                        content: content,
                        icon: "warning",
                    }).then(okay => {
                        if (okay) {
                            $("#btn_progress").hide();
                            $("#btn_submit").show();
                        }
                    });
                } else if (event == null) {
                    var name = "Event";
                    var content = document.createElement('div');
                    content.innerHTML = '<strong>' + name +
                        '</strong> tidak boleh kosong, silahkan coba lagi...';
                    swal({
                        title: 'Warning',
                        content: content,
                        icon: "warning",
                    }).then(okay => {
                        if (okay) {
                            $("#btn_progress").hide();
                            $("#btn_submit").show();
                        }
                    });
                } else if (status == null) {
                    var name = "Status";
                    var content = document.createElement('div');
                    content.innerHTML = '<strong>' + name +
                        '</strong> tidak boleh kosong, silahkan coba lagi...';
                    swal({
                        title: 'Warning',
                        content: content,
                        icon: "warning",
                    }).then(okay => {
                        if (okay) {
                            $("#btn_progress").hide();
                            $("#btn_submit").show();
                        }
                    });
                } else {
                    $.ajax({
                        url: '{{ route('add-admin') }}',
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

                            if (alerts == "failed_space") {
                                swal('Gagal',
                                    'Username tidak boleh terdapat spasi, silahkan coba lagi...',
                                    'warning');
                            } else if (alerts == "failed") {
                                swal('Gagal', 'Username sudah terdaftar, silahkan coba lagi...',
                                    'warning');
                            } else if (alerts == "success") {
                                swal('Sukses', 'Data berhasil disimpan...', 'success').then(
                                    () => {
                                        window.location.href = "{{ url('/') }}" +
                                            "/admin-event/" + params;
                                    });
                            } else {
                                swal('Gagal', 'Data gagal disimpan...', 'warning');
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
