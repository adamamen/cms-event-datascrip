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
                            <h4>Admin Event</h4>
                            <div class="article-cta">
                                @if ($pages == 'cms')
                                    <a href="{{ route('add_admin_index', ['page' => 'cms']) }}" class="btn btn-success">Add
                                        Admin</a>
                                @else
                                    <a href="{{ route('add_admin_index', ['page' => $data[0]['title_url']]) }}"
                                        class="btn btn-success">Add Admin</a>
                                @endif
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
                                            <th>Nama Lengkap</th>
                                            <th>Nama Event</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @if ($value['status'] == 'A')
                                                        <span class="badge badge-success">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td>{{ $value['username'] }}</td>
                                                <td>{{ $value['password_encrypts'] }}</td>
                                                <td>{{ $value['full_name'] }}</td>
                                                <td>{{ $value['title'] }}</td>
                                                <td>
                                                    {{-- <form action="{{ route('edit-admin', ['id' => $value['admin_id']]) }}" method="POST"> --}}
                                                    @if ($pages == 'cms')
                                                        <form method="POST"
                                                            action="{{ route('edit-admin', ['page' => 'cms', 'id' => $value['admin_id']]) }}">
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('edit-admin', ['page' => $value['title_url'], 'id' => $value['admin_id']]) }}">
                                                    @endif
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <input type="hidden" name="page"
                                                        value="{{ $pages == 'cms' ? 'cms' : $value['title_url'] }}">
                                                    <input type="hidden" name="id" value="{{ $value['admin_id'] }}">
                                                    <button name="edit" id="edit"
                                                        class="btn btn-primary">Edit</button>
                                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                                    <a href="#" class="btn btn-danger"
                                                        data-id="{{ $value['admin_id'] }}" name="btn_delete"
                                                        id="btn_delete">Delete</a>
                                                    </form>
                                                </td>
                                            </tr>
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
    <!-- <script src="{{ asset('js/page/index-0.js') }}"></script> -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
    <script>
        $(document).on('click', '#btn_delete', function() {
            var recordId = $(this).data('id');

            swal({
                    title: 'Apakah kamu yakin?',
                    text: 'Apakah kamu yakin ingin menghapus data ini?',
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
                                    swal('Sukses', 'Data berhasil di delete...', 'success').then(
                                        okay => {
                                            if (okay) {
                                                window.location.href = "/admin-event/cms";
                                            }
                                        });
                                } else if (alerts == "failed") {
                                    swal('Gagal', 'Data gagal di delete...', 'warning');
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
