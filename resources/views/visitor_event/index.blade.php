@extends('layouts.app')

@section('title', 'Data Visitor Event')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            @if ($titleUrl == 'cms')
                <div class="section-header">
                    <h1>Data Visitor Event</h1>
                </div>
            @else
                <div class="section-header">
                    <h1>Data Visitor Event {{ '(' . $output . ')' }}</h1>
                </div>
            @endif

            <div class="row">

                <div class="col-md-6">
                    <div class="card">

                        <div class="card-header">
                            <h4>Import Excel</h4>
                            <a href="{{ route('template.excel', ['page' => 'cms']) }}" class="btn btn-success"><i
                                    class="fa-solid fa-file-excel"></i>&emsp; Download Template
                            </a>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="uploadForm" action="{{ route('import.excel', ['page' => $titleUrl]) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row align-items-center">
                                    <label class="form-control-label col-sm-3 text-md-right">Upload</label>
                                    <div class="col-sm-6 col-md-9">
                                        <div class="custom-file">
                                            <input type="file" name="excel_file" class="custom-file-input"
                                                id="excel-file"
                                                accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                            <label class="custom-file-label">Choose File</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-whitesmoke text-md-right">
                                <button class="btn btn-primary" id="save-btn"><i class="fa-solid fa-save"></i>
                                    &nbsp; Save Changes
                                </button>
                                <button class="btn btn-secondary" type="button" id="reset-btn">
                                    <i class="fa-solid fa-rotate-left"></i>
                                    &nbsp; Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

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

                            &emsp;
                            <a href="#" class="btn btn-success"><i class="fa-solid fa-users"></i>&nbsp;</i>&emsp;
                                Total Visitor = <b>{{ count($data) }}</b></a>
                            <div class="article-cta"></div>
                            &emsp;
                            <a href="#" class="btn btn-info"><i class="fa-solid fa-user-check"></i>&emsp; Total
                                Arrival Visitor = {{ $dataArrival }}</a>
                            <div class="article-cta"></div>
                            &emsp;
                            @foreach ($data as $value)
                                <a href="{{ route('landing.page', ['page' => $value['title_url']]) }}"
                                    class="btn btn-warning" id="view-link-qr" target="_blank">
                                    <i class="fas fa-qrcode"></i>&emsp; View Link QR
                                </a>
                            @break
                        @endforeach
                        &emsp;
                        <div class="btn-group">
                            <button type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-tasks"></i>&emsp; Actions Selected
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" id="delete-checkbox-btn">
                                    <i class="fas fa-trash"></i>&emsp; Delete Selected
                                </a>
                                {{-- <a class="dropdown-item" href="#" id="send-whatsapp-btn">
                                    <i class="fa-brands fa-whatsapp"></i>&emsp; Send Whatsapp Selected
                                </a> --}}
                                <a class="dropdown-item" href="#" id="send-email-btn">
                                    <i class="fas fa-envelope"></i>&emsp; Send Email Selected
                                </a>
                            </div>
                        </div>
                        {{-- <a href="#" class="btn btn-danger" id="delete-checkbox-btn">
                            <i class="fas fa-trash"></i>&emsp; Delete Selected
                        </a>
                        &emsp;
                        <a href="#" class="btn btn-success" id="send-whatsapp-btn">
                            <i class="fa-brands fa-whatsapp"></i>&emsp; Send Whatsapp
                        </a>
                        &emsp;
                        <a href="#" class="btn btn-primary" id="send-email-btn">
                            <i class="fas fa-envelope"></i>&emsp; Send Email
                        </a>
                        &emsp; --}}

                    </div>
                    <div class="card-body">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="table-responsive">
                            <table class="table-striped table" id="table-1">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="select-all"></th>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                        <th>Instagram Account</th>
                                        <th>Phone Number</th>
                                        <th>Invitation Type</th>
                                        <th>Nama Of Agency / Company</th>
                                        <th>Barcode No</th>
                                        <th>Date Arrival</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $value)
                                        <tr>
                                            <td><input type="checkbox" class="checkbox-item"
                                                    value="{{ $value['id'] }}">
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $value['full_name'] }}</td>
                                            <td>{{ $value['email'] }}</td>
                                            <td>{{ $value['gender'] }}</td>
                                            <td>{{ $value['account_instagram'] }}</td>
                                            <td>{{ $value['mobile'] }}</td>
                                            <td>{{ $value['type_invitation'] }}</td>
                                            <td>{{ $value['invitation_name'] }}</td>
                                            <td>{{ $value['barcode_no'] }}</td>
                                            <td>{{ $value['scan_date'] }}</td>
                                            <td>
                                                @if ($pages == 'cms')
                                                    <form method="POST"
                                                        action="{{ route('edit-visitor', ['page' => 'cms', 'id' => $value['id']]) }}">
                                                    @else
                                                        <form method="POST"
                                                            action="{{ route('edit-visitor', ['page' => $value['title_url'], 'id' => $value['id']]) }}">
                                                @endif

                                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                                <button class="btn btn-info" id="edit-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>&nbsp;

                                                <a class="btn btn-danger" id="delete-btn"
                                                    data-id="{{ $value['id'] }}" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </a>&nbsp;

                                                <a href="{{ route('visitor.event.qrcode', ['id' => encrypt($value['id'])]) }}"
                                                    target="_blank" class="btn btn-light" id="view-btn"
                                                    title="View QR">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('visitor.event.downloadQR', ['id' => $value['id']]) }}"
                                                    class="btn btn-primary" id="download-btn" title="Download QR">
                                                    <i class="fas fa-download"></i>
                                                </a>&nbsp;
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
    $(document).on('click', '#delete-btn', function() {
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
                                            window.location.href = "{{ url('/') }}" +
                                                "/visitor-event/" + params;
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

    $('#excel-file').on('change', function() {
        var fileName = $(this).val();
        $(this).next('.custom-file-label').html(fileName);
    })

    document.getElementById('reset-btn').addEventListener('click', function() {
        document.getElementById('uploadForm').reset();
        document.querySelector('.custom-file-label').textContent = 'Choose File';
    });

    $(document).ready(function() {
        $('#select-all').on('click', function() {
            $('.checkbox-item').prop('checked', this.checked);
        });

        $('.checkbox-item').on('change', function() {
            const anyChecked = $('.checkbox-item:checked').length > 0;
            $('#delete-checkbox-btn').prop('disabled', !anyChecked);
        });

        $('#delete-checkbox-btn').on('click', function(e) {
            e.preventDefault();

            let selectedIds = [];
            $('.checkbox-item:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                swal('Tidak ada data yang dipilih', 'Silakan pilih setidaknya satu item untuk dihapus.',
                    'warning');
                return;
            }

            swal({
                title: 'Apakah Kamu yakin?',
                text: "Apakah kamu yakin ingin menghapus data ini?",
                type: 'warning',
                icon: "warning",
                buttons: [
                    'Tidak',
                    'Iya'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ route('delete-multiple-visitors') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedIds
                        },
                        success: function(response) {
                            if (response.success) {
                                selectedIds.forEach(id => {
                                    $('input[value="' + id + '"]').closest(
                                        'tr').remove();
                                });

                                swal('Berhasil',
                                    'Data yang terpilih sudah dihapus',
                                    'success');

                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                swal('Gagal!',
                                    'Terjadi kesalahan saat menghapus item.',
                                    'error');
                            }
                        }
                    });
                }
            })
        });
    });

    document.getElementById('send-email-btn').addEventListener('click', function(e) {
        e.preventDefault();

        let selectedIds = [];
        $('.checkbox-item:checked').each(function() {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            swal('Tidak ada data yang dipilih', 'Silakan pilih setidaknya satu item untuk kirim email.',
                'warning');
            return;
        }

        swal({
                title: "Apakah anda yakin ingin mengirim email?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willSend) => {
                if (willSend) {
                    let ids = [];
                    document.querySelectorAll('.checkbox-item:checked').forEach((checkbox) => {
                        ids.push(checkbox.value);
                    });

                    fetch("{{ route('send.email') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ids: ids
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === 'Emails sent successfully!') {
                                swal("Berhasil!", "Email telah terkirim.", "success");
                            } else {
                                swal("Gagal!", "Email telah gagal dikirim.", "error");
                            }
                        })
                        .catch((error) => {
                            swal("Gagal!", "Email telah gagal dikirim.", "error");
                        });
                }
            });
    });
</script>
@endpush
