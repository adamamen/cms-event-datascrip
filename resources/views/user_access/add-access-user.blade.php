@extends('layouts.app')

@section('title', 'Add Access User')

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
                <h1>Add Access User</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Add Access User</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="form-group col-md-4 col-12">
                                        <label>Division</label>
                                        {{-- <select class="form-control select2" name="division" id="division"
                                            onchange="fetchDivisionOwners(this.value)"> --}}
                                        <select class="form-control select2" name="division" id="division">
                                            <option selected disabled>-- Please Select --</option>
                                            @foreach ($division as $value)
                                                <option value="{{ $value->id }}">{{ ucwords($value->name) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Division is required
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Division Owner</label>
                                        <select class="form-control select2" name="division_owner" id="division_owner">
                                            <option selected disabled>-- Please Select --</option>
                                            @foreach ($division as $value)
                                                <option value="{{ $value->id }}">{{ ucwords($value->name) }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Division Owner is required
                                        </div>
                                    </div>
                                    {{-- <div class="form-group col-md-4 col-12">
                                        <label>Division Owner</label>
                                        <select class="form-control select2" name="division_owner" id="division_owner">
                                            <option selected disabled>-- Please Select --</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Division Owner is required
                                        </div>
                                    </div> --}}
                                    <div class="form-group col-md-4 col-12">
                                        <label>Start Date</label>
                                        <input type="text" class="form-control datepicker" value="" required=""
                                            name="start_date" id="start_date">
                                        <div class="invalid-feedback">
                                            Start Date is required
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>End Date</label>
                                        <input type="text" class="form-control datepicker" value="" required=""
                                            name="end_date" id="end_date" autocomplete="off">
                                        <div class="invalid-feedback">
                                            End Date is required
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Status</label>
                                        <select class="form-control select2" name="status" id="status">
                                            <option selected disabled>-- Please Select --</option>
                                            <option value="A">Active</option>
                                            <option value="D">Inactive</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Status is required
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
                        title: 'Are you sure?',
                        text: 'Are you sure you want to go back to the previous page?',
                        icon: 'warning',
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((ok) => {
                        if (ok) {
                            window.location.href = "{{ url('/') }}" + "/user-access/" + params;
                        }
                    });
            });
        });

        $(document).ready(function() {
            var pages = "<?php echo $pages; ?>";
            var params = "<?php echo $titleUrl; ?>";

            $("#btn_submit").click(function() {
                $("#btn_progress").show();
                $("#btn_submit").hide();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var division = $('#division').val();
                var division_owner = $('#division_owner').val();
                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                var status = $('#status').val();

                var formData = new FormData();
                formData.append("division", division);
                formData.append("division_owner", division_owner);
                formData.append("start_date", start_date);
                formData.append("end_date", end_date);
                formData.append("status", status);

                if (division == null) {
                    var name = "Division";
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
                } else if (division_owner == null) {
                    var name = "Division Owner";
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
                } else if (status == null) {
                    var name = "Status";
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
                        url: '{{ route('add-user-access') }}',
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
                                swal('Success', 'Data saved successfully', 'success').then(
                                    () => {
                                        window.location.href = "{{ url('/') }}" +
                                            "/user-access/" + params;
                                    });
                            } else {
                                swal('Failed', 'Failed to save data', 'warning');
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

        function fetchDivisionOwners(divisionId) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            if (divisionId) {
                $.ajax({
                    url: '{{ route('fetch-division-owners') }}',
                    type: 'POST',
                    data: {
                        division_id: divisionId,
                        _token: csrfToken
                    },
                    success: function(response) {
                        $('#division_owner').empty();
                        $('#division_owner').append('<option selected disabled>-- Please Select --</option>');
                        response.data.forEach(function(owner) {
                            $('#division_owner').append(
                                `<option value="${owner.id}">${owner.username}</option>`);
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        alert('Failed to fetch Division Owners. Please try again.');
                    }
                });
            }
        }
    </script>
@endpush