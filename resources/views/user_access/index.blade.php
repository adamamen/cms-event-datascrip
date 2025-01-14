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
                <h1>User Access</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>User Access </h4>
                            <div class="article-cta">
                                <a href="{{ route('add_user_access_index', ['page' => $pages]) }}"
                                    class="btn btn-success"><i class="fas fa-plus"></i> Add User Access</a>
                                &nbsp;
                                <a href="{{ route('export.excel.user.access', ['page' => 'cms']) }}"
                                    class="btn btn-primary"><i class="fa-solid fa-file-excel"></i>&emsp; Export Excel</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="table-responsive">
                                <table class="table-striped table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Division</th>
                                            <th>Owner Division</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Created By</th>
                                            <th>Created Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->nama_divisi }}</td>
                                                <td>{{ $value->nama_divisi_owner }}</td>
                                                <td>{{ date('d/m/Y', strtotime($value->start_date)) }}</td>
                                                <td>{{ date('d/m/Y', strtotime($value->end_date)) }}</td>
                                                <td>{{ $value->created_by }}</td>
                                                <td>{{ date('d/m/Y H:i:s', strtotime($value->created_date)) }}</td>
                                                <td>
                                                    @if ($value['status'] == 'A')
                                                        @php
                                                            $tgl_1 = strtotime(
                                                                date(
                                                                    'Y-m-d',
                                                                    strtotime($value['end_date'] . '+1 days'),
                                                                ),
                                                            );
                                                            $tgl_2 = strtotime(date('Y-m-d'));
                                                        @endphp

                                                        @if ($tgl_1 > $tgl_2)
                                                            <center><span class="badge badge-success">Active</span></center>
                                                        @else
                                                            <center><span class="badge badge-danger">Inactive</span>
                                                            </center>
                                                        @endif
                                                    @else
                                                        <center><span class="badge badge-danger">Inactive</span></center>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form method="POST"
                                                        action="{{ route('edit-user-access', ['page' => 'cms', 'id' => $value->id, 'id_divisi' => $value->id_divisi]) }}">
                                                        @csrf
                                                        <input type="hidden" name="page" value="{{ $pages }}">
                                                        <input type="hidden" name="id" value="{{ $value->id }}">
                                                        <a href="{{ route('view-user-access', ['page' => 'cms', 'id' => $value->id, 'id_divisi' => $value->id_divisi]) }}"
                                                            class="btn btn-info"><i class="fas fa-eye"></i> View</a>
                                                        <button name="edit" id="edit" class="btn btn-primary"><i
                                                                class="fas fa-edit"></i> Edit</button>
                                                        <a href="#" class="btn btn-danger"
                                                            data-id="{{ $value->id }}" name="btn_delete"
                                                            id="btn_delete"><i class="fas fa-trash"></i> Delete</a>
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
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
    <script>
        $(document).on('click', '#btn_delete', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var token = $('meta[name="csrf-token"]').attr('content');

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '/user-access/delete-user-access/' + id,
                        type: 'DELETE',
                        data: {
                            "_token": token,
                        },
                        success: function(response) {
                            swal('Success', 'Data deleted successfully', 'success').then(
                                () => {
                                    location.reload(true);
                                });
                        },
                        error: function() {
                            swal("Failed to delete the data!", {
                                icon: "error",
                            });
                        }
                    });
                }
            });
        });
    </script>
@endpush
