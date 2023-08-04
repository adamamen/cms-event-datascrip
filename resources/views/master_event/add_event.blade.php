@extends('layouts.app')

@section('title', 'Add Event')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Add Event</h1>
        </div>
        <div class="section-body">
            <h2 class="section-title">Add Event</h2>
            <!-- <p class="section-lead">This article component is based on card and flexbox.</p> -->
            <div class="row">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="row">
                                <div class="form-group col-md-4 col-12">
                                    <label>Nama Event</label>
                                    <input type="text" class="form-control" value="" required="" name="nama_event" id="nama_event">
                                    <div class="invalid-feedback">
                                        Nama Event Wajib Diisi
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-12">
                                    <label>Status</label>
                                    <select class="form-control select2">
                                        <option>Aktif</option>
                                        <option>Tidak Aktif</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Status Wajib Diisi
                                    </div>
                                </div>
                                <div class="form-group col-md-4 col-12">
                                    <label>Logo</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="customFile" name="logo" id="logo">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                    </div>
                                    <div class="invalid-feedback">
                                        Logo Wajib Diisi
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label>Start Event</label>
                                    <input type="text" class="form-control datepicker" value="" required="" name="start_event" id="start_event">
                                    <div class="invalid-feedback">
                                        Start Event Wajib Diisi
                                    </div>
                                </div>
                                <div class="form-group col-md-6 col-12">
                                    <label>End Event</label>
                                    <input type="text" class="form-control datepicker" value="" required="" name="end_event" id="end_event">
                                    <div class="invalid-feedback">
                                        End Event Wajib Diisi
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
                            <a href="#" class="btn btn-primary mr-1" type="submit" id="btn_submit" name="btn_submit">Submit</a>
                            <a href="#" class="btn disabled btn-primary btn-progress" id="btn_progress" name="btn_progress">Submit</a>
                            <a href="#" class="btn btn-danger" type="submit" id="btn_submit" name="btn_submit">Cancel</a>
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

<!-- Page Specific JS File -->
<!-- <script src="{{ asset('js/page/index-0.js') }}"></script> -->
<script src="{{ asset('js/page/modules-datatables.js') }}"></script>

<script>
    $("#btn_progress").hide();

    $(document).ready(function() {
        $("#btn_submit").click(function() {
            $("#btn_progress").show();
            $("#btn_submit").hide();

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var namaEvent = $('#nama_event').val();
            var status = $('#status').val();
            var logo = $('#logo').val();
            var startEvent = $('#start_event').val();
            var endEvent = $('#end_event').val();
            var deskripsi = $('#deskripsi').val();
            var lokasi = $('#lokasi').val();

            $.ajax({
                url: '{{ route("add") }}',
                type: "POST",
                data: {
                    namaEvent: namaEvent,
                    status: status,
                    logo: logo,
                    startEvent: startEvent,
                    endEvent: endEvent,
                    deskripsi: deskripsi,
                    lokasi: lokasi
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // 
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });
    });
</script>
@endpush