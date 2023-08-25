@extends('layouts.auth')

@section('title', 'Register')

@push('style')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
<div class="card card-primary">
    <div class="card-header">
        <h4>Register</h4>
    </div>

    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="form-group col-12">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" autofocus>
                </div>
                <div class="form-group col-12">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" autofocus>
                </div>
                <div class="form-group col-12">
                    <label for="password_confirmation">Password Confirmation</label>
                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" autofocus>
                </div>
                <div class="form-group col-12">
                    <label for="full_name">Full Name</label>
                    <input id="full_name" type="text" class="form-control" name="full_name" autofocus>
                </div>
            </div>
            <div class="form-group">
                <!-- <button type="submit" class="btn btn-primary btn-lg btn-block">Register</button> -->
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <a href="#" class="btn btn-primary btn-lg btn-block" type="submit" id="btn_register" name="btn_register">Register</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<!-- JS Libraies -->
<script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
<script src="{{ asset('library/jquery.pwstrength/jquery.pwstrength.min.js') }}"></script>
<script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
<script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('js/page/auth-register.js') }}"></script>
<script>
    $(document).ready(function() {
        $("#btn_register").click(function() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var username = $('#username').val();
            var password = $('#password').val();
            var password_confirmation = $('#password_confirmation').val();
            var full_name = $('#full_name').val();
            var last_name = $('#last_name').val();
            var no_handphone = $('#no_handphone').val();

            var formData = new FormData();
            formData.append("username", username);
            formData.append("password", password);
            formData.append("password_confirmation", password_confirmation);
            formData.append("full_name", full_name);
            formData.append("last_name", last_name);
            formData.append("no_handphone", no_handphone);

            $.ajax({
                url: '{{ route("register.action") }}',
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    var message = response.message

                    if (message == "user") {
                        swal('Gagal', 'Register sudah pernah dilakukan oleh admin', 'error')
                    } else if (message == "username") {
                        swal('Gagal', 'Username tidak boleh kosong, silahkan coba lagi', 'error')
                    } else if (message == "passwordEmpty") {
                        swal('Gagal', 'Password tidak boleh kosong, silahkan coba lagi', 'error')
                    } else if (message == "full_name") {
                        swal('Gagal', 'Full Name tidak boleh kosong, silahkan coba lagi', 'error')
                    } else if (message == "password") {
                        swal('Gagal', 'Password minimal 8 karakter, silahkan coba lagi', 'error')
                    } else if (message == "password doesnt same") {
                        swal('Gagal', 'Password Konfirmasi harus sama dengan Password, silahkan coba lagi', 'error')
                    } else if (message == "username") {
                        swal('Gagal', 'Username tidak boleh kosong, silahkan coba lagi', 'error')
                    } else {
                        swal('Sukses', 'Data berhasil disimpan', 'success').then(okay => {
                            if (okay) {
                                window.location.href = "/";
                            }
                        });
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