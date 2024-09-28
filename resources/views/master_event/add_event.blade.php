@extends('layouts.app')

@section('title', 'Add Event')

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
                <h1>Add Event</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Add Event</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="row">
                                    <input value="{{ Auth::user()->username }}" id="username" name="username" hidden>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Nama Event</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="nama_event" id="nama_event">
                                        <div class="invalid-feedback">
                                            Nama Event Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Title Url</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="title_url" id="title_url">
                                        <div class="invalid-feedback">
                                            Title Url Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Jenis Event</label>
                                        <select class="form-control select2" name="jenis_event" id="jenis_event">
                                            <option selected disabled>-- Silahkan Pilih --</option>
                                            <option value="A">Berbayar</option>
                                            <option value="D">Non Berbayar</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Jenis Event Wajib Diisi
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3 col-12">
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
                                    <div class="form-group col-md-3 col-12">
                                        <label>Start Registrasi</label>
                                        <input type="text" class="form-control datetimepicker" value=""
                                            required="" name="start_registrasi" id="start_registrasi">
                                        <div class="invalid-feedback">
                                            Start Event Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label>End Registrasi</label>
                                        <input type="text" class="form-control datetimepicker" value=""
                                            required="" name="end_registrasi" id="end_registrasi">
                                        <div class="invalid-feedback">
                                            End Event Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label>Logo</label>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input"
                                                accept="image/png, image/jpg, image/jpeg" name="logo" id="logo">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                        <div class="invalid-feedback">
                                            Logo Wajib Diisi
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3 col-12">
                                        <label>Start Event</label>
                                        <input type="text" class="form-control datepicker" value="" required=""
                                            name="start_event" id="start_event">
                                        <div class="invalid-feedback">
                                            Start Event Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label>End Event</label>
                                        <input type="text" class="form-control datepicker" value="" required=""
                                            name="end_event" id="end_event">
                                        <div class="invalid-feedback">
                                            End Event Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label>Tanggal Terakhir Aplikasi</label>
                                        <input type="text" class="form-control datepicker" value=""
                                            required="" name="end_event_application" id="end_event_application">
                                        <div class="invalid-feedback">
                                            Tanggal Terakhir Aplikasi Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3 col-12">
                                        <label>Divisi</label>
                                        <select class="form-control select2" name="divisi" id="divisi">
                                            <option selected disabled>-- Silahkan Pilih --</option>
                                            @foreach ($listDivisi as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Divisi Wajib Diisi
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea class="form-control" data-height="150" name="deskripsi" id="deskripsi"></textarea>
                                        </div>
                                        <div class="invalid-feedback">
                                            Deskripsi Wajib Diisi
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <div class="form-group">
                                            <label>Lokasi</label>
                                            <textarea class="form-control" data-height="150" name="lokasi" id="lokasi"></textarea>
                                        </div>
                                        <div class="invalid-feedback">
                                            Lokasi Wajib Diisi
                                        </div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary mr-1" type="submit" id="btn_submit"
                                    name="btn_submit"><i class="fas fa-check"></i> Submit</a>
                                <a href="#" class="btn disabled btn-primary btn-progress" id="btn_progress"
                                    name="btn_progress">Submit</a>
                                <a href="#" class="btn btn-danger" type="submit" id="btn_cancel"
                                    name="btn_cancel"><i class="fas fa-xmark"></i> Cancel</a>
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
        // Antisipasi ketika "-" lebih dari 1, dan tidak bisa uppercase
        var inputElement = document.getElementById("title_url");

        inputElement.addEventListener("input", function() {
            var currentValue = inputElement.value;

            // Convert to lowercase, remove spaces, and consecutive hyphens, and update the input field value
            var cleanedValue = currentValue.toLowerCase().replace(/ /g, '').replace(/-+/g, '-');
            inputElement.value = cleanedValue;
        });

        $("#btn_progress").hide();
        $(document).ready(function() {
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
                            window.location.href = "{{ url('/') }}" + "/master-event/cms";
                        }
                    });
            });
        });

        // Date picker + 1 day
        const currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + 1);
        const formattedDate = currentDate.toISOString().slice(0, 10);
        document.getElementById("end_event_application").value = formattedDate;

        // Ketika Nama Event Diisi maka title url pun juga terisi 
        $(document).ready(function() {

            var namaEvent = $('#nama_event');
            var titleUrl = $('#title_url');

            titleUrl.prop('disabled', true);

            titleUrl.on('input', function() {
                if (titleUrl.val().trim() === '' && namaEvent.val().trim() === '') {
                    swal('Gagal', 'Silahkan isi "Nama Event" terlebih dahulu, silahkan coba lagi...',
                        'warning');
                }
            });

            namaEvent.on('input', function() {
                var namaEventValue = $(this).val();
                var titleUrlValue = namaEventValue.replace(/\s+/g, '-').toLowerCase();
                titleUrl.val(titleUrlValue);

                titleUrl.prop('disabled', namaEventValue.trim() === '');
            });
        });

        // Show File Name Upload
        $('input[type="file"]').change(function(e) {
            var fileName = e.target.files[0].name;
            $('.custom-file-label').html(fileName);
        });

        $(document).ready(function() {
            $("#btn_submit").click(function() {
                $("#btn_progress").show();
                $("#btn_submit").hide();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var namaEvent = $('#nama_event').val();
                var title_url = $('#title_url').val();
                var status = $('#status').val();
                var start_registrasi = $('#start_registrasi').val();
                var end_registrasi = $('#end_registrasi').val();
                var divisi = $('#divisi').val();
                var logo = $("#logo")[0].files[0];
                var startEvent = $('#start_event').val();
                var endEvent = $('#end_event').val();
                var deskripsi = $('#deskripsi').val();
                var lokasi = $('#lokasi').val();
                var username = $('#username').val();
                var jenis_event = $('#jenis_event').val();
                var end_event_application = $('#end_event_application').val();

                var formData = new FormData();
                formData.append("namaEvent", namaEvent);
                formData.append("title_url", title_url);
                formData.append("status", status);
                formData.append("start_registrasi", start_registrasi);
                formData.append("end_registrasi", end_registrasi);
                formData.append("logo", logo);
                formData.append("startEvent", startEvent);
                formData.append("endEvent", endEvent);
                formData.append("deskripsi", deskripsi);
                formData.append("lokasi", lokasi);
                formData.append("username", username);
                formData.append("divisi", divisi);
                formData.append("jenis_event", jenis_event);
                formData.append("end_event_application", end_event_application);

                if (namaEvent == "") {
                    var name = "Nama Event";
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
                } else if (jenis_event == null) {
                    var name = "Jenis Event";
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
                } else if (start_registrasi == null) {
                    var name = "Start Registrasi";
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
                } else if (end_registrasi == null) {
                    var name = "End Registrasi";
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
                } else if (divisi == null) {
                    var name = "Divisi";
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
                } else if (logo == undefined) {
                    var name = "Logo";
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
                } else if (startEvent == "") {
                    var name = "Start Event";
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
                } else if (endEvent == "") {
                    var name = "End Event";
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
                } else if (deskripsi == "") {
                    var name = "Deskripsi";
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
                } else if (lokasi == "") {
                    var name = "Lokasi";
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
                        url: '{{ route('add') }}',
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

                            if (alerts == "failed last character") {
                                swal('Gagal',
                                    'Title Url Tidak boleh diakhiri dengan simbol, silahkan coba lagi...',
                                    'warning');
                            } else if (alerts == "success") {
                                swal('Sukses', 'Data berhasil disimpan...', 'success').then(
                                    okay => {
                                        if (okay) {
                                            window.location.href = "{{ url('/') }}" +
                                                "/master-event/cms";
                                        }
                                    });
                            } else if (alerts == "failed") {
                                swal('Gagal',
                                    'Title Url sudah pernah ada, silahkan coba lagi...',
                                    'warning');
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
