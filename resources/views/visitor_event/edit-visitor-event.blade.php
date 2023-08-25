@extends('layouts.app')

@section('title', 'Edit Visitor Event')

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
            <h1>Edit Visitor Event</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Edit Visitor Event</h2>
            <!-- <p class="section-lead">This article component is based on card and flexbox.</p> -->
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        @foreach($data as $value)
                        <div class="card-body">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <input value="{{ $value->id }}" name="id" id="id" hidden>
                            <div class="row">
                                <div class="form-group col-md-4 col-12">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ $value->full_name }}" required="" name="nama_lengkap" id="nama_lengkap">
                                    <div class="invalid-feedback">
                                        Nama Lengkap Wajib Diisi
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-12">
                                    <label>Event</label>
                                    <select class="form-control select2" name="event" id="event">
                                        <option selected disabled>-- Silahkan Pilih --</option>
                                        @foreach ($event as $event)
                                        <option value="{{ $value->id_event }}" {{ $event->id_event == $value->event_id ? 'selected' : '' }}>{{ $event->title }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Status Wajib Diisi
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-12">
                                    <label>No Handphone</label>
                                    <input type="text" class="form-control" value="{{ $value->mobile }}" required="" name="no_handphone" id="no_handphone" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    <div class="invalid-feedback">
                                        No Handphone Wajib Diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <input value="{{ Auth::user()->username }}" id="username" name="username" hidden>
                                <div class="form-group col-md-4 col-12">
                                    <label>No Tiket</label>
                                    <input type="text" class="form-control" value="{{ $value->ticket_no }}" required="" name="no_tiket" id="no_tiket" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                    <div class="invalid-feedback">
                                        No Tiket Wajib Diisi
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-12">
                                    <label>Email</label>
                                    <input type="email" class="form-control" value="{{ $value->email }}" required="" name="email" id="email">
                                    <div class="invalid-feedback">
                                        Email Wajib Diisi
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-12">
                                    <label>Tanggal Registrasi</label>
                                    <input type="text" class="form-control datepicker" value="{{ $value->registration_date }}" required="" name="tanggal_registrasi" id="tanggal_registrasi">
                                    <div class="invalid-feedback">
                                        Tanggal Registrasi Wajib Diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <div class="form-group">
                                        <label>Alamat</label>
                                        <textarea class="form-control" data-height="150" name="alamat" id="alamat">{{ $value->address }}</textarea>
                                    </div>
                                    <div class="invalid-feedback">
                                        Alamat Wajib Diisi
                                    </div>
                                </div>
                            </div>
                            <a href="#" class="btn btn-primary mr-1" type="submit" id="btn_submit" name="btn_submit">Submit</a>
                            <a href="#" class="btn disabled btn-primary btn-progress" id="btn_progress" name="btn_progress">Submit</a>
                            <a href="#" class="btn btn-danger" type="submit" id="btn_cancel" name="btn_cancel">Cancel</a>
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
                        window.location.href = "/visitor-event/cms";
                    }
                });
        });
    });

    $(document).ready(function() {
        $("#btn_submit").click(function() {
            $("#btn_progress").show();
            $("#btn_submit").hide();

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var namaLengkap = $('#nama_lengkap').val();
            var namaEvent = $('#nama_event').val();
            var email = $('#email').val();
            var noHandphone = $('#no_handphone').val();
            var noTiket = $('#no_tiket').val();
            var tanggalRegistrasi = $('#tanggal_registrasi').val();
            var alamat = $('#alamat').val();
            var username = $('#username').val();
            var id = $('#id').val();

            var formData = new FormData();
            formData.append("namaLengkap", namaLengkap);
            formData.append("namaEvent", namaEvent);
            formData.append("email", email);
            formData.append("noHandphone", noHandphone);
            formData.append("noTiket", noTiket);
            formData.append("tanggalRegistrasi", tanggalRegistrasi);
            formData.append("alamat", alamat);
            formData.append("username", username);
            formData.append("id", id);

            $.ajax({
                url: '{{ route("update-visitor") }}',
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
                        swal('Sukses', 'Data berhasil diupdate...', 'success').then(okay => {
                            if (okay) {
                                window.location.href = "/visitor-event/cms";
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