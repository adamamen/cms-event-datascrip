@extends('layouts.app')

@section('title', 'View User Access')

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
                <h1>View User Access</h1>
            </div>
            <div class="section-body">
                <h2 class="section-title">View User Access</h2>
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            @foreach ($data as $value)
                                <div class="card-body">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="form-group col-md-4 col-12">
                                            <label>Division</label>
                                            <select class="form-control select2" name="division" id="division" disabled>
                                                <option selected disabled>-- Please Select --</option>
                                                @foreach ($division as $divisi)
                                                    <option value="{{ $divisi['id'] }}"
                                                        {{ $divisi['id'] == $value['id_divisi'] ? 'selected' : '' }}>
                                                        {{ ucwords($divisi['name']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Division is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Division Owner</label>
                                            <select class="form-control select2" name="division_owner" id="division_owner"
                                                disabled>
                                                <option selected disabled>-- Please Select --</option>
                                                @foreach ($division as $divisi)
                                                    <option value="{{ $divisi['id'] }}"
                                                        {{ $divisi['id'] == $value['id_divisi_owner'] ? 'selected' : '' }}>
                                                        {{ ucwords($divisi['name']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Division Owner is required
                                            </div>
                                        </div>

                                        {{-- <div class="form-group col-md-4 col-12">
                                            <label>Division Owner</label>
                                            <select class="form-control select2" name="division_owner" id="division_owner"
                                                disabled>
                                                <option selected disabled>-- Please Select --</option>
                                                @foreach ($user as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ $user->id == $value->id_divisi_owner ? 'selected' : '' }}>
                                                        {{ $user->username }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Division Owner is required
                                            </div>
                                        </div> --}}
                                        <div class="form-group col-md-4 col-12">
                                            <label>Start Date</label>
                                            <input type="text" class="form-control datepicker"
                                                value="{{ $value['start_date'] }}" required="" name="start_date"
                                                id="start_date" disabled>
                                            <div class="invalid-feedback">
                                                Start Date is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>End Date</label>
                                            <input type="text" class="form-control datepicker"
                                                value="{{ $value['end_date'] }}" required="" name="end_date"
                                                id="end_date" autocomplete="off" disabled>
                                            <div class="invalid-feedback">
                                                End Date is required
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-12">
                                            <label>Status</label>
                                            <select class="form-control select2" name="status" id="status" disabled>
                                                <option selected disabled>-- Please Select --</option>
                                                <option value="A" {{ $value['status'] == 'A' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="D" {{ $value['status'] == 'D' ? 'selected' : '' }}>
                                                    Inactive</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Status is required
                                            </div>
                                        </div>
                                        <input type="hidden" class="form-control" value="{{ $value['id'] }}"
                                            required="" name="admin_id" id="admin_id">
                                    </div>
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
            var params = "<?php echo $pages; ?>";

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

            $("#division").change(function() {
                let divisionId = $(this).val();

                if (divisionId) {
                    $.ajax({
                        url: "{{ route('fetch-division-owners') }}",
                        type: "POST",
                        data: {
                            division_id: divisionId,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            let divisionOwnerDropdown = $("#division_owner");
                            divisionOwnerDropdown.empty();

                            divisionOwnerDropdown.append(
                                '<option selected disabled>-- Please Select --</option>');

                            if (response.status === 'success' && response.data.length > 0) {
                                response.data.forEach(function(owner) {
                                    divisionOwnerDropdown.append(
                                        `<option value="${owner.id}">${owner.username}</option>`
                                    );
                                });
                            } else {
                                divisionOwnerDropdown.append(
                                    '<option disabled>No owners available</option>');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endpush
