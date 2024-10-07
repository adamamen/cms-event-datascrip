@extends('layouts.app')

@section('title', 'Data Visitor Event')

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
                                <a href="#" class="btn disabled btn-primary btn-progress" id="btn_progress"
                                    name="btn_progress" style="display: none;">
                                    Submit
                                </a>
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
                                    <i class="fas fa-qrcode"></i>&emsp; View Link QR Verification
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

                    </div>
                    <div class="card-body">
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <div class="table-responsive">
                            <table class="table-striped table" id="tbl_data_visitor">
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
                                        <th>E-mail Status</th>
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
                                            @if ($value['flag_email'] == '1')
                                                <td>
                                                    <span class="badge badge-success">Delivered</span>
                                                </td>
                                            @else
                                                <td>
                                                    <span class="badge badge-danger">Not Delivered</span>
                                                </td>
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
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-dark dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="fas fa-tasks"></i>&emsp; Actions
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <button type="submit" class="dropdown-item" title="Edit"
                                                            style="font-size: 14px">
                                                            <i class="fas fa-edit"></i>&emsp; Edit
                                                        </button>
                                                        <a class="dropdown-item" id="delete-btn"
                                                            data-id="{{ $value['id'] }}" title="Delete">
                                                            <i class="fas fa-trash"></i>&emsp; Delete
                                                        </a>
                                                        <a href="{{ route('visitor.event.qrcode', ['id' => encrypt($value['id'])]) }}"
                                                            target="_blank" class="dropdown-item" title="View QR">
                                                            <i class="fas fa-eye"></i>&emsp; View QR
                                                        </a>
                                                        <a href="{{ route('visitor.event.downloadQR', ['id' => $value['id']]) }}"
                                                            class="dropdown-item" title="Download QR">
                                                            <i class="fas fa-download"></i>&emsp; Download QR
                                                        </a>
                                                        <a class="send-email-btn-id dropdown-item"
                                                            data-id="{{ $value['id'] }}" title="Send Email">
                                                            <i class="fas fa-envelope"></i>&emsp; Send Email
                                                        </a>
                                                        @if (empty($value['scan_date']))
                                                            <a href="#" class="dropdown-item arrival-btn"
                                                                data-id="{{ $value['id'] }}"
                                                                data-name="{{ $value['full_name'] }}"
                                                                title="Arrival">
                                                                <i class="fas fa-plane-arrival"></i>&emsp; Arrival
                                                            </a>
                                                        @endif

                                                    </div>
                                                </div>
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
<script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('js/page/modules-datatables.js') }}"></script>
<script src="{{ asset('library/sweetalert/dist/sweetalert.min.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('js/page/modules-sweetalert.js') }}"></script>
@if (session('success'))
    <script>
        swal({
            title: "Success!",
            text: "{{ session('success') }}",
            icon: "success",
            button: "OK",
        });
    </script>
@endif

@if (session('error'))
    <script>
        swal({
            title: "Error!",
            text: "{{ session('error') }}",
            icon: "error",
            button: "OK",
        });
    </script>
@endif

<script>
    // Email Satuan 
    $(document).ready(function() {
        $('.send-email-btn-id').on('click', function() {
            const id = $(this).data('id');
            const url = "{{ route('send.email.id', ['id' => '__ID__']) }}".replace('__ID__',
                id);

            swal({
                title: "Are you sure you want to send the email?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willSend) => {
                if (willSend) {
                    swal({
                        title: "Sending email, please wait...",
                        text: "The process is ongoing.",
                        icon: "info",
                        buttons: false,
                        closeOnClickOutside: false,
                    });

                    fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            const emailsSent = data.emails_sent ||
                                0;

                            if (emailsSent > 0) {
                                var content = document.createElement('div');
                                content.innerHTML =
                                    "Email has been successfully sent";

                                swal({
                                    title: "Success!",
                                    content: content,
                                    icon: "success",
                                }).then(okay => {
                                    if (okay) {
                                        window.location.reload();
                                    }
                                });
                            } else {
                                swal("Failed!", "Emails failed to send.", "error").then(
                                    okay => {
                                        if (okay) {
                                            window.location.reload();
                                        }
                                    });
                            }
                        })
                        .catch((error) => {
                            swal("Failed!", "An error occurred while sending the email.",
                                "error").then(
                                okay => {
                                    if (okay) {
                                        window.location.reload();
                                    }
                                });
                        });
                }
            });
        });
    });

    // Upload Excel 
    $(document).ready(function() {
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();

            var fileInput = $('#excel-file').val();
            if (!fileInput) {
                var content = document.createElement('div');
                content.innerHTML =
                    '<strong>The upload file</strong> cannot be empty, please try again...';
                swal({
                    title: 'Warning',
                    content: content,
                    icon: "warning",
                }).then(okay => {
                    if (okay) {
                        $("#btn_progress").hide();
                        $("#btn_submit").show();
                    }
                });
                return;
            }

            $('#save-btn').hide();
            $('#btn_progress').show();

            var form = $(this);
            var formData = new FormData(this);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    var content = document.createElement('div');
                    content.innerHTML = 'Data has been successfully imported for <strong>' +
                        response.count + ' people. </strong> ';
                    swal({
                        title: 'Success!',
                        content: content,
                        icon: "success",
                    }).then(okay => {
                        if (okay) {
                            $('#save-btn').show();
                            $('#btn_progress').hide();
                            window.location.reload();
                        }
                    });
                },
                error: function(xhr) {
                    swal({
                        title: "Error!",
                        text: "Failed to import data, please try again.",
                        icon: "error",
                        button: "OK",
                    });

                    $('#btn_progress').addClass('disabled');
                    $('#save-btn').show();
                    $('#btn_progress').hide();
                },
            });
        });
    });

    // Delete satuan 
    $(document).on('click', '#delete-btn', function() {
        var recordId = $(this).data('id');
        var params = "<?php echo $titleUrl; ?>";

        swal({
                title: "Are you sure?",
                text: 'Are you sure you want to delete this data?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((ok) => {
                if (ok) {
                    swal({
                        title: "Process Delete...",
                        text: "The process is ongoing.",
                        icon: "info",
                        buttons: false,
                        closeOnClickOutside: false,
                    });

                    $.ajax({
                        url: '{{ route('delete-visitor', ':id') }}'.replace(':id', recordId),
                        type: "DELETE",
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            var alerts = response.message

                            // if (alerts == "failed") {
                            // swal('Gagal',
                            // 'No Tiket sudah pernah digunakan, silahkan coba lagi...',
                            // 'warning');
                            // } else
                            if (alerts == "success") {
                                swal('Success', 'Data has been successfully deleted...',
                                    'success').then(
                                    okay => {
                                        if (okay) {
                                            window.location.href = "{{ url('/') }}" +
                                                "/visitor-event/" + params;
                                        }
                                    });
                            } else if (alerts == "failed") {
                                swal('Failed', 'Failed to delete data...', 'warning');
                            } else {
                                swal('Failed', 'Failed to delete data...', 'warning');
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

    // Agar muncul path nya
    $('#excel-file').on('change', function() {
        var fileName = $(this).val();
        $(this).next('.custom-file-label').html(fileName);
    })

    // Reset upload excel 
    document.getElementById('reset-btn').addEventListener('click', function() {
        document.getElementById('uploadForm').reset();
        document.querySelector('.custom-file-label').textContent = 'Choose File';
    });

    // Arrival 
    $(document).ready(function() {
        $('.arrival-btn').click(function(e) {
            e.preventDefault();

            const visitorId = $(this).data('id');
            const userName = $(this).data('name');

            var content = document.createElement('div');
            content.innerHTML =
                `Are you sure <strong>${userName}</strong> is already at the place?`;
            swal({
                title: "Confirmation",
                content: content,
                icon: "warning",
                buttons: {
                    cancel: "No",
                    confirm: {
                        text: "Yes",
                        value: true,
                    }
                },
            }).then((isConfirm) => {
                if (isConfirm) {
                    const arrivalDate = new Date().toISOString().slice(0, 10);

                    $.ajax({
                        url: '{{ route('visitor.arrival') }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            visitorId: visitorId,
                            dateArrival: arrivalDate
                        },
                        success: function(response) {
                            if (response.success) {
                                swal("Success", response.message, "success").then(
                                    () => {
                                        $(`.arrival-btn[data-id="${visitorId}"]`)
                                            .hide();
                                        window.location
                                            .reload();
                                    });
                            } else {
                                swal("Failed", response.message, "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            swal("Failed", "Terjadi kesalahan, coba lagi.",
                                "error");
                        }
                    });
                }
            });
        });
    });

    // Select All Checkbox, Delete Selected, Send Email Selected
    $(document).ready(function() {
        const dt = $('#tbl_data_visitor').DataTable({
            "bSort": false,
            "lengthMenu": [10, 25, 50, 100, -1],
            "order": [],
            "drawCallback": function(settings) {
                updateSelectAllCheckbox();
            },
            "initComplete": function() {
                const select = this.api().table().container().querySelector('select');
                select.options[select.options.length - 1].text =
                    'All';
            }
        });

        let selectedCheckboxes = [];

        // Select All Checkbox
        $('#select-all').on('click', function() {
            const isChecked = this.checked;

            dt.rows().nodes().each(function(row) {
                $(row).find('.checkbox-item').prop('checked', isChecked);
                const id = $(row).find('.checkbox-item').val();
                if (isChecked) {
                    if (!selectedCheckboxes.includes(id)) {
                        selectedCheckboxes.push(id);
                    }
                } else {
                    selectedCheckboxes = selectedCheckboxes.filter(selectedId => selectedId !==
                        id);
                }
            });

            updateSendEmailButton();
        });

        $('.checkbox-item').on('change', function() {
            const id = $(this).val();

            if (this.checked) {
                if (!selectedCheckboxes.includes(id)) {
                    selectedCheckboxes.push(id);
                }
            } else {
                selectedCheckboxes = selectedCheckboxes.filter(selectedId => selectedId !== id);
            }

            $('#select-all').prop('checked', $('.checkbox-item:checked').length === $('.checkbox-item')
                .length);
            updateSendEmailButton();
        });

        function updateSelectAllCheckbox() {
            const totalCheckboxes = $('.checkbox-item').length;
            const totalChecked = $('.checkbox-item:checked').length;
            $('#select-all').prop('checked', totalChecked === totalCheckboxes);
        }

        function updateSendEmailButton() {
            const anyChecked = selectedCheckboxes.length > 0;
            $('#send-email-btn').prop('disabled', !anyChecked);
        }

        // Send Email Selected
        document.getElementById('send-email-btn').addEventListener('click', function(e) {
            e.preventDefault();

            if (selectedCheckboxes.length === 0) {
                swal('No data selected.', 'Please select at least one item to send the email.',
                    'warning');
                return;
            }

            swal({
                    title: "Are you sure you want to send the email?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willSend) => {
                    if (willSend) {
                        swal({
                            title: "Sending email, please wait...",
                            text: "The process is ongoing.",
                            icon: "info",
                            buttons: false,
                            closeOnClickOutside: false,
                        });

                        fetch("{{ route('send.email') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    ids: selectedCheckboxes
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                const emailsSent = data.emails_sent;
                                const totalSelected = data.total_selected;

                                if (emailsSent > 0) {
                                    var content = document.createElement('div');
                                    content.innerHTML =
                                        "Email has been successfully sent to <b>" + emailsSent +
                                        " out of " + totalSelected + " people.</b>";

                                    swal({
                                        title: "Success!",
                                        content: content,
                                        icon: "success",
                                    }).then(okay => {
                                        if (okay) {
                                            window.location.reload();
                                        }
                                    });
                                } else {
                                    swal("Failed!", "Emails failed to send.", "error").then(
                                        okay => {
                                            if (okay) {
                                                window.location.reload();
                                            }
                                        });
                                }
                            })
                            .catch((error) => {
                                swal("Failed!", "An error occurred while sending the email.",
                                    "error").then(
                                    okay => {
                                        if (okay) {
                                            window.location.reload();
                                        }
                                    });
                            });
                    }
                });
        });

        // Delete Selected
        $('#delete-checkbox-btn').on('click', function(e) {
            e.preventDefault();

            if (selectedCheckboxes.length === 0) {
                swal('No data selected.', 'Please select at least one item to delete.', 'warning');
                return;
            }

            swal({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this data?",
                icon: "warning",
                buttons: [
                    'No',
                    'Yes'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    swal({
                        title: "Process Delete...",
                        text: "The process is ongoing.",
                        icon: "info",
                        buttons: false,
                        closeOnClickOutside: false,
                    });

                    $.ajax({
                        url: "{{ route('delete-multiple-visitors') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedCheckboxes
                        },
                        success: function(response) {
                            if (response.success) {
                                selectedCheckboxes.forEach(id => {
                                    $('input[value="' + id + '"]').closest(
                                        'tr').remove();
                                });

                                swal('Success',
                                    'The selected data has been deleted.',
                                    'success');

                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                swal('Failed!',
                                    'An error occurred while deleting the item.',
                                    'error');
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush
