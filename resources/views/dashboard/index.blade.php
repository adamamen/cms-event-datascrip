@extends('layouts.app')

@section('title', 'Home')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/owl.carousel/dist/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/flag-icon-css/css/flag-icon.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row">
                @if (!empty($masterEvent))
                    @foreach ($masterEvent as $value)
                        <div class="col-md-6">
                            <div class="card card-hero">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <h4>{{ !empty($totalVisitor) ? $totalVisitor : '0' }}</h4>
                                    <a href="{{ route('visitor_event.index', ['page' => $value['title_url']]) }}">
                                        <div class="card-description" style="color:white">Total Visitor</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-hero">
                                <div class="card-header">
                                    <div class="card-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <h4>{{ $totalAdmin }}</h4>
                                    <a href="{{ route('admin_event.index', ['page' => $value['title_url']]) }}">
                                        <div class="card-description" style="color:white">Total Admin</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <h4>{{ $totalDivisi }}</h4>
                                <a href="{{ route('company_event.index', ['page' => 'cms']) }}">
                                    <div class="card-description" style="color:white">Total Divisi</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <h4>{{ $totalEvent }}</h4>
                                <a href="{{ route('index', ['page' => 'cms']) }}">
                                    <div class="card-description" style="color:white">Total Event</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <h4>{{ $totalVisitor }}</h4>
                                <a href="{{ route('visitor_event.index', ['page' => 'cms']) }}">
                                    <div class="card-description" style="color:white">Total Visitor</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-hero">
                            <div class="card-header">
                                <div class="card-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <h4>{{ $totalAdmin }}</h4>
                                <a href="{{ route('admin_event.index', ['page' => 'cms']) }}">
                                    <div class="card-description" style="color:white">Total Admin</div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('library/chart.js/dist/Chart.js') }}"></script>
    <script src="{{ asset('library/owl.carousel/dist/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('library/summernote/dist/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('library/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>
@endpush
