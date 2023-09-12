<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    
    @if(!empty($masterEvent)) 
        @foreach ($masterEvent as $value)
            <title>Login &mdash; {{ $value['title'] . ' Event' }}</title>
        @endforeach 
    @else 
        <title>Login &mdash; CMS Event</title>
    @endif 

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/bootstrap-social.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
</head>

<style>
    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 64%;
    }
</style>

<body>
    <div id="app">
        <section class="section">
            <div class="d-flex align-items-stretch flex-wrap">
                <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
                    <div class="m-3 p-4">

                        @if (!empty($masterEvent))
                            @foreach ($masterEvent as $value)
                                <img src="{{ asset('images/' . $value['logo']) }}" alt="logo" width="100%"
                                    class="center"><br><br>
                                <h4 class="text-dark font-weight-normal">Welcome to <span
                                        class="font-weight-bold">{{ strtoupper($value['title']) }} Event</span></h4>
                            @endforeach
                        @else
                            <img src="{{ asset('img/logo1.png') }}" alt="logo" width="100%"
                                class="center"><br><br>
                            <h4 class="text-dark font-weight-normal">Welcome to <span class="font-weight-bold">CMS
                                    Event</span></h4>
                        @endif

                        <p class="text-muted">
                            Before you get started, you must login or register if you don't already have an account.
                        </p>
                        <form method="POST" class="needs-validation" novalidate="" action="{{ route('login_action') }}">
                            @csrf
                            <input type="hidden" id="tokens" name="tokens">
                            @if ($errors->has('message'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('message') }}
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input id="username" type="username" class="form-control" name="username"
                                    tabindex="1" required autofocus>
                                <div class="invalid-feedback">
                                    Please fill in your username
                                </div>
                            </div>

                            @if (!empty($masterEvent))
                                @foreach ($masterEvent as $value)
                                    <input id="title" name="title" value="{{ strtolower($value['title_url']) }}"
                                        hidden>
                                @endforeach
                            @endif

                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password" class="control-label">Password</label>
                                </div>
                                <input id="password" type="password" class="form-control" name="password"
                                    tabindex="2" required>
                                <div class="invalid-feedback">
                                    Please fill in your password
                                </div>
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-primary btn-lg btn-icon icon-right" type="submit"
                                    id="btn_submit" name="btn_submit">Login</button>
                            </div>

                            <div class="mt-5 text-center">
                                @if (empty($masterEvent))
                                    Don't have an account? <a href="{{ route('register', ['page' => 'cms']) }}">Create
                                        new one for Admin</a>
                                @endif
                            </div>
                        </form>

                        <div class="text-small mt-5 text-center">
                            Copyright &copy; Datascrip. Made with 💙 by MIS Datascrip
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-12 order-lg-2 min-vh-100 background-walk-y position-relative overlay-gradient-bottom order-1"
                    data-background="{{ asset('img/unsplash/bromo-2.jpg') }}">
                    <div class="absolute-bottom-left index-2">
                        <div class="text-light p-5 pb-2">
                            <div class="mb-5 pb-3">
                                <h1 class="display-4 font-weight-bold mb-2">Mount Bromo</h1>
                                <h5 class="font-weight-normal text-muted-transparent">Bali, Indonesia</h5>
                            </div>
                            Photo by <a class="text-light bb" target="_blank"
                                href="https://unsplash.com/photos/gJegRRpCm1g">Atik sulianami</a> on <a
                                class="text-light bb" target="_blank" href="https://unsplash.com">Unsplash</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/popper.js/dist/umd/popper.js') }}"></script>
    <script src="{{ asset('library/tooltip.js/dist/umd/tooltip.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('js/stisla.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>

    <!-- JS Libraies -->

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js?render=6LcbfmIbAAAAAHPoz8CpApqVJNrh7_kKZhJfPZ3Q"></script>
    <script type="text/javascript">
        grecaptcha.ready(function() {
            grecaptcha.execute('6LcbfmIbAAAAAHPoz8CpApqVJNrh7_kKZhJfPZ3Q', {
                action: 'homepage_login'
            }).then(function(token) {
                // console.log("onload's token : " + token);
                document.getElementById("tokens").value = token;
            });
        });

        setInterval(function() {
            grecaptcha.ready(function() {
                grecaptcha.execute("6LcbfmIbAAAAAHPoz8CpApqVJNrh7_kKZhJfPZ3Q", {
                    action: "homepage_login_request_call_back"
                }).then(function(token) {
                    // console.log("refresh token : " + token);
                    document.getElementById("tokens").value = token;
                });
            });
        }, 90 * 1000);
    </script>
</body>

</html>
