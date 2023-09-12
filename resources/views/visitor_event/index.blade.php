@extends('layouts.app')

@section('title', 'Data Visitor Event')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">

    <link rel="stylesheet" href="assets/modules/datatables/datatables.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
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
                <h1>Data Visitor Event</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Visitor Event</h4>

                            @if (!empty($data))
                                @if ($pages == 'cms')
                                    <a href="{{ route('export.excel', ['page' => 'cms']) }}" class="btn btn-primary"><i
                                            class="fa-solid fa-file-excel"></i>&emsp; Export Excel</a>
                                @else
                                    <a href="{{ route('export.excel', ['page' => $data[0]['title_url']]) }}"
                                        class="btn btn-primary"><i class="fa-solid fa-file-excel"></i>&emsp; Export
                                        Excel</a>
                                @endif
                            @endif

                            <div class="article-cta"></div>
                        </div>
                        <div class="card-body">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="table-responsive">
                                <table class="table-striped table" id="tbl_visitor">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Tiket</th>
                                            <th>Nama Event</th>
                                            <th>Profile</th>
                                            @if ($jenis_events != '' || $jenis_events == 'A')
                                                <th>No Invoice / SN Product</th>
                                                <th>Status Pembayaran</th>
                                                <th>Metode Pembayaran</th>
                                            @elseif (Auth::user()->username == 'admin' || Auth::user()->username == 'mis')
                                                <th>No Invoice / SN Product</th>
                                                <th>Status Pembayaran</th>
                                                <th>Metode Pembayaran</th>
                                            @endif
                                            <th>Tanggal Registrasi</th>
                                            @if (Auth::user()->username == 'admin' || Auth::user()->username == 'mis')
                                                <th>Jenis Event</th>
                                            @endif
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value['ticket_no'] }}</td>
                                                <td>{{ $value['title'] }}</td>
                                                <td>
                                                    <i class="fas fa-user"></i> <b>{{ $value['full_name'] }}</b><br>
                                                    <i class="fas fa-mobile-alt"></i> <b>{{ $value['mobile'] }}</b><br>
                                                    <i class="fas fa-id-card"></i> <b>{{ $value['email'] }}</b><br>
                                                    <i class="fas fa-home"></i> <b>{{ $value['address'] }}</b>
                                                </td>
                                                @if ($value['jenis_event'] == 'A')
                                                    <td>
                                                        <i class="fas fa-newspaper"></i>
                                                        <b>{{ $value['no_invoice'] }}</b><br>
                                                        <i class="far fa-newspaper"></i> <b>{{ $value['sn_product'] }}</b>
                                                    </td>
                                                    @if ($value['status_pembayaran'] == 'Belum Dibayar')
                                                        <td><span
                                                                class="badge badge-warning">{{ $value['status_pembayaran'] }}</span>
                                                        </td>
                                                    @else
                                                        <td><span
                                                                class="badge badge-success">{{ $value['status_pembayaran'] }}</span>
                                                        </td>
                                                    @endif
                                                    <td>
                                                        <center>
                                                            {{ empty($value['metode_bayar']) || $value['metode_bayar'] == 'null' ? '-' : $value['metode_bayar'] }}
                                                        </center>
                                                    </td>
                                                @else
                                                    @if (Auth::user()->username == 'admin' || Auth::user()->username == 'mis')
                                                        <td>
                                                            <center>-</center>
                                                        </td>
                                                        <td>
                                                            <center>-</center>
                                                        </td>
                                                        <td>
                                                            <center>-</center>
                                                        </td>
                                                    @endif
                                                @endif
                                                <td>{{ $value['created_at'] }}</td>
                                                @if (Auth::user()->username == 'admin' || Auth::user()->username == 'mis')
                                                    @if ($value['jenis_event'] == 'A')
                                                        <td>Berbayar</td>
                                                    @else
                                                        <td>Non Berbayar</td>
                                                    @endif
                                                @endif
                                                <td>
                                                    @if ($pages == 'cms')
                                                        <form method="POST"
                                                            action="{{ route('edit-visitor', ['page' => 'cms', 'id' => $value['id']]) }}">
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('edit-visitor', ['page' => $value['title_url'], 'id' => $value['id']]) }}">
                                                    @endif
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                    <input type="hidden" name="page"
                                                        value="{{ $pages == 'cms' ? 'cms' : $value['title_url'] }}">
                                                    <input type="hidden" name="id" value="{{ $value['id'] }}">
                                                    <button name="edit" id="edit" class="btn btn-primary"><i
                                                            class="fas fa-edit"></i> Edit</button>
                                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                                    {{-- <a href="#" class="btn btn-danger" data-id="{{ $value['id'] }}" name="btn_delete" id="btn_delete">Delete</a> --}}
                                                    @if ($pages == 'cms')
                                                        @if ($value['jenis_event'] == 'A')
                                                            <a href="{{ route('generate.pdf', ['page' => 'cms', 'id' => $value['id']]) }}"
                                                                class="btn btn-success" target="_blank"><i
                                                                    class="fas fa-print"></i> Cetak Invoice</a>
                                                        @endif
                                                    @else
                                                        @if ($value['jenis_event'] == 'A')
                                                            <a href="{{ route('generate.pdf', ['page' => $value['title_url'], 'id' => $value['id']]) }}"
                                                                class="btn btn-success" target="_blank"><i
                                                                    class="fas fa-print"></i> Cetak Invoice</a>
                                                        @endif
                                                    @endif
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
    <!-- <script src="assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
                                    <script src="assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js"></script> -->

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.4.1/css/buttons.dataTables.min.css">
    <!-- <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tbl_visitor').DataTable({
                // dom: 'Bfrtip',
                // buttons: [{
                //     extend: 'excel',
                //     exportOptions: {
                //         columns: [0, 1, 2, 3, 4, 5, 6, 7]
                //     },
                // }],
            });
        });

        $(document).on('click', '#btn_delete', function() {
            var recordId = $(this).data('id');
            var params = "<?php echo $titleUrl; ?>";

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
                            url: '{{ route('delete-visitor', ':id') }}'.replace(':id', recordId),
                            type: "DELETE",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                var alerts = response.message

                                if (alerts == "failed") {
                                    swal('Gagal',
                                        'No Tiket sudah pernah digunakan, silahkan coba lagi...',
                                        'warning');
                                } else if (alerts == "success") {
                                    swal('Sukses', 'Data berhasil di delete...', 'success').then(
                                        okay => {
                                            if (okay) {
                                                window.location.href = "/visitor-event/" +
                                                    params;
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
