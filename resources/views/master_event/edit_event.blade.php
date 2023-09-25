@extends('layouts.app')

@section('title', 'Edit Event')

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
                <h1>Edit Event</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Edit Events</h2>
                <!-- <p class="section-lead">This article component is based on card and flexbox.</p> -->
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            @foreach ($data as $value)
                                <div class="card-body">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <div class="row">
                                        <input value="{{ Auth::user()->username }}" id="username" name="username" hidden>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Nama Event</label>
                                            <input type="text" class="form-control" value="{{ $value->title }}"
                                                required="" name="nama_event" id="nama_event">
                                            <input type="hidden" class="form-control" value="{{ $value->id_event }}"
                                                required="" name="id_event" id="id_event">
                                            <div class="invalid-feedback"> Nama Event Wajib Diisi </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Title Url</label>
                                            <input type="text" class="form-control" value="{{ $value->title_url }}"
                                                required="" name="title_url" id="title_url">
                                            <input type="text" class="form-control" value="{{ $value->title_url }}"
                                                required="" name="title_url_before" id="title_url_before" hidden>
                                            <div class="invalid-feedback">
                                                Title Url Wajib Diisi
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Status </label>
                                            <select class="form-control select2" name="status" id="status">
                                                <option value="A"
                                                    {{ $value->status_master_event == 'A' ? 'selected' : '' }}> Aktif
                                                </option>
                                                <option value="D"
                                                    {{ $value->status_master_event == 'D' ? 'selected' : '' }}> Tidak Aktif
                                                </option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Status Wajib Diisi
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Jenis Event</label>
                                            <select class="form-control select2" name="jenis_event" id="jenis_event">
                                                <option value="A" {{ $value->jenis_event == 'A' ? 'selected' : '' }}>
                                                    Berbayar</option>
                                                <option value="D" {{ $value->jenis_event == 'D' ? 'selected' : '' }}>
                                                    Non Berbayar</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Jenis Event Wajib Diisi
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Logo</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input"
                                                    accept="image/png, image/jpg, image/jpeg" name="logo" id="logo"
                                                    value="{{ $value->logo }}">
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
                                            <input type="text" class="form-control datepicker"
                                                value="{{ $value->start_event }}" required="" name="start_event"
                                                id="start_event">
                                            <div class="invalid-feedback">
                                                Start Event Wajib Diisi
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label>End Event</label>
                                            <input type="text" class="form-control datepicker"
                                                value="{{ $value->end_event }}" required="" name="end_event"
                                                id="end_event">
                                            <div class="invalid-feedback">
                                                End Event Wajib Diisi
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label>Tanggal Terakhir Aplikasi</label>
                                            <input type="text" class="form-control datepicker"
                                                value="{{ $value->end_event }}" required=""
                                                name="end_event_application" id="end_event_application">
                                            <div class="invalid-feedback">
                                                Tanggal Terakhir Aplikasi Wajib Diisi
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-12">
                                            <label>Divisi</label>
                                            <select class="form-control select2" name="divisi" id="divisi">
                                                <option selected disabled>-- Silahkan Pilih --</option>
                                                @foreach ($listDivisi as $divisi)
                                                    <option value="{{ $divisi->id }}"
                                                        {{ $divisi->id == $value->company ? 'selected' : '' }}>
                                                        {{ $divisi->name }}</option>
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
                                                <textarea class="form-control" data-height="150" name="deskripsi" id="deskripsi">{{ $value->desc }}</textarea>
                                            </div>
                                            <div class="invalid-feedback">
                                                Deskripsi Wajib Diisi
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Lokasi</label>
                                                <textarea class="form-control" data-height="150" name="lokasi" id="lokasi">{{ $value->location }}</textarea>
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
    <!-- <script src="{{ asset('js/page/index-0.js') }}"></script> -->
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
                            window.location.href = "/master-event/cms";
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

            titleUrl.prop('', true);

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

        $(document).ready(function() {
            $("#btn_submit").click(function() {
                $("#btn_progress").show();
                $("#btn_submit").hide();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var id_event = $('#id_event').val();
                var namaEvent = $('#nama_event').val();
                var title_url = $('#title_url').val();
                var title_url_before = $('#title_url_before').val();
                var status = $('#status').val();
                var logo = $("#logo")[0].files[0];
                var startEvent = $('#start_event').val();
                var endEvent = $('#end_event').val();
                var deskripsi = $('#deskripsi').val();
                var lokasi = $('#lokasi').val();
                var username = $('#username').val();
                var divisi = $('#divisi').val();
                var jenis_event = $('#jenis_event').val();
                var end_event_application = $('#end_event_application').val();

                var formData = new FormData();
                formData.append("namaEvent", namaEvent);
                formData.append("title_url", title_url);
                formData.append("title_url_before", title_url_before);
                formData.append("status", status);
                formData.append("logo", logo);
                formData.append("startEvent", startEvent);
                formData.append("endEvent", endEvent);
                formData.append("deskripsi", deskripsi);
                formData.append("lokasi", lokasi);
                formData.append("id_event", id_event);
                formData.append("username", username);
                formData.append("divisi", divisi);
                formData.append("jenis_event", jenis_event);
                formData.append("end_event_application", end_event_application);

                $.ajax({
                    url: '{{ route('update') }}',
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
                        } else if (title_url == "") {
                            swal('Gagal', 'Title Url harus diisi, silahkan coba lagi...',
                                'warning');
                        } else if (alerts == "url has been input") {
                            swal('Gagal',
                                'Title Url sudah pernah di input, silahkan coba lagi...',
                                'warning');
                        } else if (alerts == "success") {
                            swal('Sukses', 'Data berhasil diupdate...', 'success').then(
                                okay => {
                                    if (okay) {
                                        window.location.href = "/master-event/cms";
                                    }
                                });
                        } else {
                            swal('Gagal', 'Data gagal diupdate...', 'warning');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        $("#btn_progress").hide();
                        $("#btn_submit").show();

                        console.log(textStatus, errorThrown);
                    }
                });
            });
        });
    </script>
@endpush
