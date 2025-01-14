@extends('layouts.app')

@section('title', 'Master Event')

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
                <h1>Master Event</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Master Event</h4>
                            <div class="article-cta">
                                <a href="{{ route('add_index', ['page' => 'cms']) }}" class="btn btn-success"><i
                                        class="fas fa-plus"></i> Add Event</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="table-responsive">
                                <table class="table-striped table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>
                                                <center>No</center>
                                            </th>
                                            <th>
                                                <center>Event Name</center>
                                            </th>
                                            <th>
                                                <center>Status</center>
                                            </th>
                                            <th>
                                                <center>Logo</center>
                                            </th>
                                            <th>
                                                <center>Start Event</center>
                                            </th>
                                            <th>
                                                <center>Division</center>
                                            </th>
                                            <th>
                                                <center>End Event</center>
                                            </th>
                                            <th>
                                                <center>Description</center>
                                            </th>
                                            <th>
                                                <center>Start Registration</center>
                                            </th>
                                            <th>
                                                <center>Location</center>
                                            </th>
                                            <th>
                                                <center>End Registration</center>
                                            </th>
                                            <th>
                                                <center>Event Type</center>
                                            </th>
                                            <th>
                                                <center>Url Event</center>
                                            </th>
                                            <th>
                                                <center>Last Application Date</center>
                                            </th>
                                            <th>
                                                <center>Action</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value['title'] }}</td>
                                                <td>
                                                    @if ($value['status'] == 'A')
                                                        @php
                                                            $tgl_1 = strtotime(
                                                                date(
                                                                    'Y-m-d',
                                                                    strtotime(
                                                                        $value['tanggal_terakhir_aplikasi'] . '+1 days',
                                                                    ),
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
                                                <td> <img src="{{ asset('images/' . $value['logo']) }}" alt="Image"
                                                        class="image-thumbnail"></td>
                                                <td>{{ $value['start_event'] }}</td>
                                                <td>{{ $value['nama_divisi'] }}</td>
                                                <td>{{ $value['end_event'] }}</td>
                                                <td>{{ $value['desc'] }}</td>
                                                <td>
                                                    {{ !empty($value['start_registrasi']) ? $value['start_registrasi'] : '-' }}
                                                </td>
                                                <td>{{ $value['location'] }}</td>
                                                <td>
                                                    {{ !empty($value['end_registrasi']) ? $value['end_registrasi'] : '-' }}
                                                </td>
                                                <td>{{ $value['jenis_event'] == 'A' ? 'Berbayar' : 'Non Berbayar' }}</td>
                                                <td>
                                                    <a href="{{ url('/' . $value['title_url']) }}" target="_blank">
                                                        {{ $value['title_url'] }}
                                                    </a>
                                                </td>
                                                <td>{{ $value['tanggal_terakhir_aplikasi_indo'] }}</td>
                                                <td>
                                                    <center>
                                                        <form action="{{ route('edit', ['id' => $value['id_event']]) }}"
                                                            method="POST">
                                                            <input type="hidden" name="_token"
                                                                value="{{ csrf_token() }}" />
                                                            <button name="edit" id="edit" class="btn btn-primary"><i
                                                                    class="fas fa-edit"></i> Edit</button>
                                                            <meta name="csrf-token" content="{{ csrf_token() }}">
                                                            <a href="#" class="btn btn-danger"
                                                                data-id="{{ $value['id_event'] }}" name="btn_delete"
                                                                id="btn_delete"><i class="fas fa-trash"></i> Delete</a>
                                                        </form>
                                                    </center>
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
        $(document).on('click', '#btn_delete', function() {
            var recordId = $(this).data('id');

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
                            url: '{{ route('delete', ':id') }}'.replace(':id', recordId),
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
                                            window.location.href = "{{ url('/') }}" +
                                                "/master-event/cms";
                                        });
                                } else {
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
