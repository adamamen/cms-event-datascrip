@extends('layouts.app')

@section('title', 'Report Visitor Event')

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
            @if ($titleUrl == 'cms')
                <div class="section-header">
                    <h1>Report Visitor Event</h1>
                </div>
            @else
                <div class="section-header">
                    <h1>Data Report Visitor Event</h1>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Report Visitor Event</h4>
                            &emsp;
                            <div class="article-cta">
                                <a href="{{ route('export.excel.report.visitor', ['page' => $page]) }}"
                                    class="btn btn-success"><i class="fa-solid fa-file-excel"></i>&emsp; Export Excel</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                            <div class="table-responsive">
                                <table class="table-striped table" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Event</th>
                                            <th>Divisi Event Name</th>
                                            <th>Source Original Name Cust</th>
                                            <th>Name</th>
                                            <th>Gender</th>
                                            <th>E-mail</th>
                                            <th>WhatsApp Number</th>
                                            <th>Institution</th>
                                            <th>Institution Name</th>
                                            <th>Approve Date</th>
                                            <th>Approve By</th>
                                            <th>Visit Date</th>
                                            <th>Source</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $value)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value->title_event }}</td>
                                                <td>{{ $value->nama_divisi }}</td>
                                                <td>{{ $value->name_mst_cust }}</td>
                                                <td>{{ $value->nama_registrasi_event }}</td>
                                                <td>{{ $value->gender_registrasi }}</td>
                                                <td>{{ $value->email_registrasi }}</td>
                                                <td>{{ $value->tlp_registrasi }}</td>
                                                <td>{{ $value->invitaion_registrasi }}</td>
                                                <td>{{ $value->invitation_name_registrasi }}</td>
                                                <td>{{ date('d-m-Y H:i', strtotime($value->tgl_approve_registrasi)) }}</td>
                                                <td>{{ $value->approveby_registrasi }}</td>
                                                <td>{{ date('d-m-Y H:i', strtotime($value->tgl_visit)) }}</td>
                                                <td>{{ $value->source_visitor }}</td>
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
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = '{{ $type_menu }}';

            if (currentPage === 'report_visitor_event') {
                document.body.classList.add('sidebar-mini');
            }
        });
    </script>
@endpush
