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
                <h1>Edit Visitor Event {{ '(' . $output . ')' }}</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">Profile Visitor</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            @foreach ($data as $value)
                                <div class="card-body">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <input value="{{ $value->id }}" name="id" id="id" hidden>
                                    <input value="{{ Auth::user()->username }}" id="username" name="username" hidden>
                                    <div class="row">
                                        <div class="form-group col-md-4 col-12">
                                            <label>Name</label>
                                            <input type="text" class="form-control" value="{{ $value->full_name }}"
                                                required="" name="name" id="name">
                                            <div class="invalid-feedback">
                                                Name is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Email</label>
                                            <input type="email" class="form-control" value="{{ $value->email }}"
                                                required="" name="email" id="email">
                                            <div class="invalid-feedback">
                                                Email is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Gender</label>
                                            <input type="email" class="form-control" value="{{ $value->gender }}"
                                                required="" name="gender" id="gender">
                                            <div class="invalid-feedback">
                                                Gender is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Instagram Account</label>
                                            <input type="text" class="form-control"
                                                value="{{ $value->account_instagram }}" required=""
                                                name="instagram_account" id="instagram_account">
                                            <div class="invalid-feedback">
                                                Instagram Account is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Phone Number</label>
                                            <input type="text" class="form-control" value="{{ $value->mobile }}"
                                                required="" name="phone_number" id="phone_number"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                                            <div class="invalid-feedback">
                                                Phone Number is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Invitation Type</label>
                                            <input type="text" class="form-control"
                                                value="{{ $value->type_invitation }}" required="" name="invitation_type"
                                                id="invitation_type">
                                            <div class="invalid-feedback">
                                                Invitation Type is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Name Of Agency / Company</label>
                                            <input type="text" class="form-control"
                                                value="{{ $value->invitation_name }}" required="" name="name_of_agency"
                                                id="name_of_agency">
                                            <div class="invalid-feedback">
                                                Name Of Agency / Company is required
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="card-body">
                                <meta name="csrf-token" content="{{ csrf_token() }}">
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
                            window.location.href = "{{ url('/') }}" + "/visitor-event/" + params;
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
                var id = $('#id').val();
                var username = $('#username').val();
                var name = $('#name').val();
                var email = $('#email').val();
                var gender = $('#gender').val();
                var instagramAccount = $('#instagram_account').val();
                var phoneNumber = $('#phone_number').val();
                var invitationType = $('#invitation_type').val();
                var nameOfAgency = $('#name_of_agency').val();

                var formData = new FormData();
                formData.append("id", id);
                formData.append("username", username);
                formData.append("name", name);
                formData.append("email", email);
                formData.append("gender", gender);
                formData.append("instagramAccount", instagramAccount);
                formData.append("phoneNumber", phoneNumber);
                formData.append("invitationType", invitationType);
                formData.append("nameOfAgency", nameOfAgency);

                $.ajax({
                    url: '{{ route('update-visitor') }}',
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

                        swal('Sukses', 'Data berhasil diupdate...', 'success').then(
                            okay => {
                                if (okay) {
                                    window.location.href = "{{ url('/') }}" +
                                        "/visitor-event/" + params;
                                }
                            });
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
