@extends('layouts.app')

@section('title', 'Edit Divisi Event')

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
                <h1>Edit Divisi Event</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Edit Divisi Event</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            @foreach ($data as $value)
                                <div class="card-body">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Division Name</label>
                                            <input type="text" class="form-control" value="{{ $value->name }}"
                                                required="" name="name" id="name" autocomplete="off">
                                            <input type="hidden" class="form-control" value="{{ $value->id }}"
                                                required="" name="id" id="id">
                                            <input type="hidden" value="{{ Auth::user()->username }}" id="username"
                                                name="username">
                                            <div class="invalid-feedback">
                                                Division Name is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Status</label>
                                            <select class="form-control select2" name="status" id="status">
                                                <option selected disabled>-- Silahkan Pilih --</option>
                                                <option value="A" {{ $value->status == 'A' ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="D" {{ $value->status == 'D' ? 'selected' : '' }}>
                                                    Tidak Aktif</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Status is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea class="form-control" data-height="150" name="deskripsi" id="deskripsi">{{ $value->description }}</textarea>
                                            </div>
                                            <div class="invalid-feedback">
                                                Description is required
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
                        title: 'Are you sure?',
                        text: 'Are you sure you want to go back to the previous page?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((ok) => {
                        if (ok) {
                            window.location.href = "{{ url('/') }}" + "/company-event/cms";
                        }
                    });
            });
        });

        $(document).ready(function() {
            $("#btn_submit").click(function() {
                $("#btn_progress").show();
                $("#btn_submit").hide();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var id = $('#id').val();
                var name = $('#name').val();
                var status = $('#status').val();
                var deskripsi = $('#deskripsi').val();
                var username = $('#username').val();

                var formData = new FormData();
                formData.append("id", id);
                formData.append("name", name);
                formData.append("status", status);
                formData.append("deskripsi", deskripsi);
                formData.append("username", username);

                $.ajax({
                    url: '{{ route('update-company') }}',
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
                            swal('Success', 'Data updated successfully', 'success').then(
                                () => {
                                    window.location.href = "{{ url('/') }}" +
                                        "/company-event/cms";
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
            });
        });
    </script>
@endpush
