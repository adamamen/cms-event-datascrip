@extends('layouts.app')

@section('title', 'Admin Event')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <style>
        .image-thumbnail {
            max-width: 250px;
            max-height: 250px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Admin Event</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Admin Event </h4>
                            <div class="article-cta">
                                <a href="{{ route('add_admin_index', ['page' => $pages]) }}" class="btn btn-success"><i
                                        class="fas fa-plus"></i> Add Admin Event</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="table-responsive">
                                <table class="table-striped table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Status</th>
                                            <th>Username</th>
                                            <th>Password</th>
                                            <th>Full Name</th>
                                            <th>Event Name</th>
                                            <th>E-mail</th>
                                            <th>Division</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $value)
                                            {{-- @if ($value['divisi'] == Auth::user()->divisi) --}}
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($value['status'] == 'A')
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $value['username'] }}</td>
                                                <td>{{ $value['password_encrypts'] }}</td>
                                                <td>{{ $value['full_name'] }}</td>
                                                <td>
                                                    {{ !empty($value['title']) ? ucwords($value['title']) : 'CMS Event Datascrip' }}
                                                </td>
                                                <td>{{ $value['email'] ?? '-' }}</td>
                                                <td>{{ $value['nama_divisi'] ?? '-' }}</td>
                                                <td>
                                                    @if ($pages == 'cms')
                                                        <form method="POST"
                                                            action="{{ route('edit-admin', ['page' => 'cms', 'id' => $value['admin_id']]) }}">
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('edit-admin', ['page' => $pages, 'id' => $value['admin_id']]) }}">
                                                    @endif
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <input type="hidden" name="page" value="{{ $pages }}">
                                                    <input type="hidden" name="id" value="{{ $value['admin_id'] }}">
                                                    <button name="edit" id="edit" class="btn btn-primary"><i
                                                            class="fas fa-edit"></i> Edit</button>
                                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                                    {{-- @if ($value['event_id'] != '0') --}}
                                                    {{-- <a href="#" class="btn btn-danger"
                                                        data-id="{{ $value['admin_id'] }}" name="btn_delete"
                                                        id="btn_delete"><i class="fas fa-trash"></i> Delete</a> --}}
                                                    {{-- @endif --}}
                                                    @if ($value['event_id'] == 0 && is_null($value['divisi']))
                                                        {{-- BUtton di hide --}}
                                                    @else
                                                        <a href="#" class="btn btn-danger"
                                                            data-id="{{ $value['admin_id'] }}" name="btn_delete"
                                                            id="btn_delete"><i class="fas fa-trash"></i> Delete</a>
                                                    @endif

                                                    </form>
                                                </td>
                                            </tr>
                                            {{-- @endif --}}
                                        @endforeach
                                    </tbody>
                                </table>
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

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
    <script>
        $(document).on('click', '#btn_delete', function() {
            var recordId = $(this).data('id');
            var params = "<?php echo $pages; ?>";

            swal({
                    title: 'Are you sure?',
                    text: 'Are you sure you want to delete this data?',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((ok) => {
                    if (ok) {
                        $.ajax({
                            url: '{{ route('delete-admin', ':id') }}'.replace(':id', recordId),
                            type: "DELETE",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                var alerts = response.message

                                if (alerts == "success") {
                                    swal('Success', 'Data deleted successfully', 'success').then(
                                        () => {
                                            location.reload(true);
                                        });
                                } else if (alerts == "failed") {
                                    swal('Failed', 'Failed to delete data', 'warning');
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
