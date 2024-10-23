@extends('layouts.app')

@section('title', 'Edit E-mail Event')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/codemirror/lib/codemirror.css') }}">
    <link rel="stylesheet" href="{{ asset('library/codemirror/theme/duotone-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>E-mail Event</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Form E-mail Event</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4>Edit E-mail Event</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group row mb-4">
                                                    <label
                                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Type</label>
                                                    <div class="col-sm-12 col-md-7">
                                                        <input type="text" class="form-control" id="type"
                                                            name="type"
                                                            value="{{ $page == 'cms' ? 'Registrasi' : 'Undangan' }}"
                                                            readonly>
                                                        <input type="hidden" class="form-control" id="page"
                                                            name="page" value="{{ $page }}" readonly>
                                                        <input type="hidden" class="form-control" id="id"
                                                            name="id" value="{{ $data->id }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-4">
                                                    <label
                                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                                                    <div class="col-sm-12 col-md-7">
                                                        <textarea class="summernote-simple" id="content" name="content">{{ $data->content }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group row mb-4">
                                                    <label
                                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                                    <div class="col-sm-12 col-md-7">
                                                        <button class="btn btn-primary" id="btn_submit" name="btn_submit">
                                                            <i class="fas fa-check"></i> Submit </button>
                                                        &nbsp;
                                                        <a href="#" class="btn disabled btn-primary btn-progress"
                                                            id="btn_progress" name="btn_progress">Submit</a>
                                                        <a href="#" class="btn btn-danger" type="submit"
                                                            id="btn_cancel" name="btn_cancel"><i class="fas fa-xmark"></i>
                                                            Cancel</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/codemirror/lib/codemirror.js') }}"></script>
    <script src="{{ asset('library/codemirror/mode/javascript/javascript.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <!-- JS Libraies -->
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

    <script>
        $(document).ready(function() {
            var params = "<?php echo $titleUrl; ?>";

            $("#btn_progress").hide();

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
                            window.location.href = "{{ url('/') }}" + "/email-event/" + params;
                        }
                    });
            });

            $("#btn_submit").click(function() {
                $("#btn_progress").show();
                $("#btn_submit").hide();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var id = $('#id').val();
                var type = $('#type').val();
                var content = $('#content').val();
                var page = $('#page').val();

                var formData = new FormData();
                formData.append("id", id);
                formData.append("type", type);
                formData.append("content", content);
                formData.append("page", page);

                if (content == "") {
                    var name = "Content";
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
                        url: '{{ route('update-email-event') }}',
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
                                swal('Success', 'The data has been successfully saved',
                                    'success').then(
                                    () => {
                                        window.location.href = "{{ url('/') }}" +
                                            "/email-event/" + params;
                                    });
                            } else {
                                swal('Failed',
                                    'The data has been entered previously',
                                    'warning');
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
