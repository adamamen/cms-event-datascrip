<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
@extends('layouts_registrasi_visitor.app')

@section('title', 'Add Visitor Event')

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
                <h1>Add Visitor Event</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Add Visitor Event</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="form-group col-md-4 col-12">
                                        <label>Name</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="name" id="name" autocomplete="off">
                                        <div class="invalid-feedback">
                                            Name is required
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Gender</label>
                                        <select class="form-control select2" name="gender" id="gender">
                                            <option selected disabled>-- Please Select --</option>
                                            <option value="L">Laki-laki</option>
                                            <option value="P">Perempuan</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Gender is required
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Email</label>
                                        <input type="email" class="form-control" value="" required=""
                                            name="email" id="email" autocomplete="off">
                                        <div class="invalid-feedback">
                                            Email is required
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4 col-12">
                                        <label>Instagram Account</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="instagram_account" id="instagram_account" autocomplete="off">
                                        <div class="invalid-feedback">
                                            Instagram Account is required
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Phone Number</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="phone_number" id="phone_number"
                                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');"
                                            autocomplete="off">
                                        <div class="invalid-feedback">
                                            Phone Number is required
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-12">
                                        <label>Invitation Type</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="invitation_type" id="invitation_type" autocomplete="off">
                                        <div class="invalid-feedback">
                                            Invitation Type is required
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4 col-12">
                                        <label>Name Of Agency / Company</label>
                                        <input type="text" class="form-control" value="" required=""
                                            name="name_of_agency" id="name_of_agency" autocomplete="off">
                                        <div class="invalid-feedback">
                                            Name Of Agency / Company is required
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
                            location.reload(true)
                        }
                    });
            });
        });

        $(document).ready(function() {

            $("#btn_submit").click(function() {
                $("#btn_progress").show();
                $("#btn_submit").hide();

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var name = $('#name').val();
                var gender = $('#gender').val();
                var email = $('#email').val();
                var instagram_account = $('#instagram_account').val();
                var phone_number = $('#phone_number').val();
                var invitation_type = $('#invitation_type').val();
                var name_of_agency = $('#name_of_agency').val();

                var pathArray = window.location.pathname.split('/');
                var page = pathArray[pathArray.length - 1];

                var formData = new FormData();
                formData.append("name", name);
                formData.append("gender", gender);
                formData.append("email", email);
                formData.append("instagram_account", instagram_account);
                formData.append("phone_number", phone_number);
                formData.append("invitation_type", invitation_type);
                formData.append("name_of_agency", name_of_agency);

                if (name == "") {
                    var names = "Name";
                    var content = document.createElement('div');
                    content.innerHTML = '<strong>' + names +
                        '</strong> cannot be empty, please try again';
                    swal({
                        title: 'Warning',
                        content: content,
                        icon: "warning",
                    }).then(() => {
                        $("#btn_progress").hide();
                        $("#btn_submit").show();
                    });
                } else if (gender == null) {
                    var name = "Gender";
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
                } else if (email == "") {
                    var name = "Email";
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
                } else if (instagram_account == "") {
                    var name = "Instagram Account";
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
                } else if (phone_number == "") {
                    var name = "Phone Number";
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
                } else if (invitation_type == "") {
                    var name = "Invitation Type";
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
                } else if (name_of_agency == "") {
                    var name = "Name Of Agency / Company";
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
                        url: '{{ route('add-visitor', ['page' => ':page']) }}'.replace(':page',
                            page),
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
                                swal('Success', 'Data has been successfully saved', 'success')
                                    .then(
                                        () => {
                                            location.reload(true)
                                        });
                            } else {
                                swal('Failed', 'Data failed to save', 'warning');
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
