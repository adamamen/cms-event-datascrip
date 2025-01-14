@extends('layouts.app')

@section('title', 'Master User')

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
                <h1>Master User</h1>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="card">

                        <div class="card-header">
                            <h4>Import Excel</h4>
                            <a href="{{ route('template.excel.master.user', ['page' => 'cms']) }}"
                                class="btn btn-success"><i class="fa-solid fa-file-excel"></i>&emsp; Download Template
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

                        <form id="uploadForm" action="{{ route('import.excel.master.user', ['page' => $titleUrl]) }}"
                            method="POST" enctype="multipart/form-data">
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
                                <button class="btn btn-primary" id="save-btn" type="button"><i
                                        class="fa-solid fa-save"></i>
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
                            <h4>Data Master User</h4>

                            {{-- <a href="{{ route('export.excel', ['page' => 'cms']) }}" class="btn btn-primary"><i
                                    class="fa-solid fa-file-excel"></i>&emsp; Export Excel</a> --}}
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
                                    <a class="dropdown-item" href="#" id="send-whatsapp-btn" data-type="1">
                                        <i class="fa-brands fa-whatsapp" style="margin-left: 2%"></i>&emsp; Send Whatsapp
                                        Selected
                                    </a>
                                    <a class="dropdown-item" href="#" id="send-email-btn" data-type="0">
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
                                            <th>Gender</th>
                                            <th>Email</th>
                                            <th>Whatsapp Number</th>
                                            <th>City</th>
                                            <th>Division</th>
                                            <th>Institution</th>
                                            <th>Institution Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $value)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="checkbox-item"
                                                        value="{{ $value['id'] }}">
                                                </td>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $value['name'] }}</td>
                                                <td>{{ $value['gender'] == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                <td>{{ $value['email'] }}</td>
                                                <td>{{ $value['phone_no'] }}</td>
                                                <td>{{ $value['city'] }}</td>
                                                <td>{{ $value['name_divisi'] }}</td>
                                                <td>{{ $value['institution'] }}</td>
                                                <td>{{ $value['name_institution'] }}</td>
                                                <td>
                                                    @if ($page == 'cms')
                                                        <form method="POST"
                                                            action="{{ route('edit-master-user', ['page' => 'cms', 'id' => $value['id']]) }}">
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('edit-master-user', ['page' => $value['title_url'], 'id' => $value['id']]) }}">
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
                                                            <a class="send-whatsapp-btn-id dropdown-item"
                                                                data-id="{{ $value['id'] }}" data-action="1"
                                                                title="Send WhatsApp">
                                                                <i class="fa-brands fa-whatsapp"
                                                                    style="margin-left: 2%;"></i>&emsp; Send WhatsApp
                                                            </a>
                                                            <a class="send-email-btn-id dropdown-item"
                                                                data-id="{{ $value['id'] }}" data-action="0"
                                                                title="Send Email">
                                                                <i class="fas fa-envelope"></i>&emsp; Send Email
                                                            </a>
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

            <div class="modal fade" id="divisionModal" tabindex="-1" role="dialog"
                aria-labelledby="divisionModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="divisionModalLabel"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- <label for="divisionSelect">Choose a division:</label> --}}
                            <select class="form-control select2" name="divisionSelect" id="divisionSelect">
                                <option selected disabled>-- Please Select --</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="sendButton">Send</button>
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

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
        // Modal tidak blank page
        $("body").children().first().before($(".modal"));

        document.getElementById('save-btn').addEventListener('click', function() {
            $('#btn_progress').show();
            $('#save-btn').hide();
            var formData = new FormData(document.getElementById('uploadForm'));

            fetch("{{ route('import.excel.master.user', ['page' => $titleUrl]) }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        $('#btn_progress').hide();
                        $('#save-btn').show();
                        swal("Warning", data.message, "warning");
                    }
                    if (data.count) {
                        swal({
                            title: "Success",
                            text: "Data successfully imported " + data.count + " people.",
                            icon: "success",
                        }).then(() => {
                            $('#btn_progress').hide();
                            $('#save-btn').show();
                            window.location.reload();
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    $('#btn_progress').hide();
                    $('#save-btn').show();
                    swal("Error", "An error occurred while sending the data", "error");
                });
        });

        $(document).ready(function() {
            $('#divisionModal').on('shown.bs.modal', function() {
                $('#divisionSelect').select2({
                    dropdownParent: $(
                        '#divisionModal'),
                    placeholder: "-- Please Select --",
                    allowClear: true
                });
            });
        });

        $(document).ready(function() {
            // Email Satuan 
            $('.send-email-btn-id').on('click', function() {
                const id = $(this).data('id');
                const url_list = "{{ route('list_event', ['id' => 0]) }}";

                $('#divisionSelect').html('<option selected disabled>Loading...</option>');
                $('#divisionModal').modal('show');

                fetch(url_list, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === 'email_failed') {
                            $('#divisionModal').modal('hide');
                            swal("Failed!", "Make sure email settings are correct.", "error");
                            return;
                        }

                        function ucwords(str) {
                            return str.replace(/\b\w/g, char => char.toUpperCase());
                        }

                        $('#divisionModalLabel').empty().append(
                            '<h5 class="modal-title" id="divisionModalLabel">Select Event Name (Send Email Selected)</h5>'
                        );

                        $('#divisionSelect').empty().append(
                            '<option selected disabled>-- Please Select --</option>');

                        data.listEvent.forEach(event => {
                            $('#divisionSelect').append(
                                `<option value="${event.id_event}">${ucwords(event.title)}</option>`
                            );
                        });
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        swal("Error!", "Failed to load events.", "error");
                    });

                $('#sendButton').off('click').on('click', function() {
                    const selectedDivision = $('#divisionSelect').val();

                    if (!selectedDivision) {
                        swal("Warning!", "Please select the event name.", "warning");
                        return;
                    }

                    const url = "{{ route('send.email.id.master.user', ['id' => '__ID__']) }}"
                        .replace('__ID__', id);

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
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        division: selectedDivision
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const emailsSent = data.emails_sent || 0;
                                    if (emailsSent > 0) {
                                        swal("Success!",
                                            "Email has been successfully sent",
                                            "success").then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        swal("Failed!",
                                            "Emails failed to send.",
                                            "error")
                                    }
                                })
                                .catch(error => {
                                    console.error(error);
                                    swal("Failed!",
                                        "An error occurred while sending the email.",
                                        "error").then(() => {
                                        // window.location.reload();
                                    });
                                });
                        }
                        $('#divisionModal').modal('hide');
                    });
                });
            });

            // Whatsapp Satuan
            $('.send-whatsapp-btn-id').on('click', function() {
                const id = $(this).data('id');
                const action = $(this).data('action');
                const url_list = "{{ route('list_event', ['id' => 1]) }}";

                $('#divisionSelect').html('<option selected disabled>Loading...</option>');

                $('#divisionModal').modal('show');

                fetch(url_list, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        function ucwords(str) {
                            return str.replace(/\b\w/g, char => char.toUpperCase());
                        }

                        $('#divisionModalLabel').empty().append(
                            '<h5 class="modal-title" id="divisionModalLabel">Select Event Name (Send WhatsApp Selected)</h5>'
                        );

                        $('#divisionSelect').empty().append(
                            '<option selected disabled>-- Please Select --</option>');

                        data.listEvent.forEach(event => {
                            $('#divisionSelect').append(
                                `<option value="${event.id_event}">${ucwords(event.title)}</option>`
                            );
                        });
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        swal("Error!", "Failed to load events.", "error");
                    });

                $('#sendButton').off('click').on('click', function() {
                    const selectedDivision = $('#divisionSelect').val();

                    if (!selectedDivision) {
                        swal("Warning!", "Please select the event name.", "warning");
                        return;
                    }

                    const url = "{{ route('send.whatsapp.id.master.user', ['id' => '__ID__']) }}"
                        .replace('__ID__', id);

                    swal({
                        title: "Are you sure you want to send the WhatsApp?",
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    }).then((willSend) => {
                        if (willSend) {
                            swal({
                                title: "Sending WhatsApp, please wait...",
                                text: "The process is ongoing.",
                                icon: "info",
                                buttons: false,
                                closeOnClickOutside: false,
                            });

                            fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        division: selectedDivision
                                    }),
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const whatsappSent = data.whatsapp_sent || 0;
                                    if (whatsappSent == 1) {
                                        swal("Success!",
                                            "WhatsApp has been successfully sent",
                                            "success").then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        swal("Failed!", "WhatsApp failed to send.",
                                            "error").then(() => {
                                            window.location.reload();
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error(error);
                                    swal("Failed!",
                                        "An error occurred while sending the WhatsApp.",
                                        "error").then(() => {
                                        // window.location.reload();
                                    });
                                });
                        }
                        $('#divisionModal').modal('hide');
                    });
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
                        }).then(() => {
                            $('#save-btn').show();
                            $('#btn_progress').hide();
                            window.location.reload();
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
                            url: '{{ route('delete-master-user', ':id') }}'.replace(':id', recordId),
                            type: "DELETE",
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                var alerts = response.message

                                if (alerts == "failed_delete") {
                                    swal('Failed',
                                        'The user is not allowed to delete other divisions.',
                                        'warning');
                                } else if (alerts == "success") {
                                    swal('Success', 'Data has been successfully deleted...',
                                        'success').then(
                                        () => {
                                            window.location.href = "{{ url('/') }}" +
                                                "/master-user/" + params;
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
                                    swal("Success", response.message, "success")
                                        .then(
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

                dt.rows({
                    'search': 'applied'
                }).nodes().each(function(row) {
                    $(row).find('.checkbox-item').prop('checked', isChecked);
                    const id = $(row).find('.checkbox-item').val();
                    if (isChecked) {
                        if (!selectedCheckboxes.includes(id)) {
                            selectedCheckboxes.push(id);
                        }
                    } else {
                        selectedCheckboxes = selectedCheckboxes.filter(selectedId =>
                            selectedId !==
                            id);
                    }
                });

                updateSendEmailButton();
                updateSendWhatsappButton();
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

                $('#select-all').prop('checked', $('.checkbox-item:checked').length === $(
                        '.checkbox-item')
                    .length);
                updateSendEmailButton();
                updateSendWhatsappButton();
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

            function updateSendWhatsappButton() {
                const anyChecked = selectedCheckboxes.length > 0;
                $('#send-whatsapp-btn').prop('disabled', !anyChecked);
            }

            document.getElementById('send-email-btn').addEventListener('click', function(e) {
                e.preventDefault();

                $(this).addClass('active');
                $('#send-whatsapp-btn').removeClass('active');

                if (selectedCheckboxes.length === 0) {
                    swal('No data selected.', 'Please select at least one item to send the email.',
                        'warning');
                    return;
                }

                const id = $(this).data('id');
                const type = $(this).data('type');
                const url_list = "{{ route('list_event', ['id' => 0]) }}";
                // const url_list = "{{ route('list_event', ['id' => 0]) }}";

                $('#divisionSelect').html('<option selected disabled>Loading...</option>');
                $('#divisionModal').modal('show');

                fetch(url_list, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === 'email_failed') {
                            $('#divisionModal').modal('hide');
                            swal("Failed!", "Make sure email settings are correct.", "error");
                            return;
                        }

                        function ucwords(str) {
                            return str.replace(/\b\w/g, char => char.toUpperCase());
                        }

                        $('#divisionModalLabel').empty().append(
                            '<h5 class="modal-title" id="divisionModalLabel">Select Event Name (Send Email Selected)</h5>'
                        );

                        $('#divisionSelect').empty().append(
                            '<option selected disabled>-- Please Select --</option>');

                        data.listEvent.forEach(event => {
                            $('#divisionSelect').append(
                                `<option value="${event.id_event}">${ucwords(event.title)}</option>`
                            );
                        });
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        swal("Error!", "Failed to load events.", "error");
                    });
            });

            document.getElementById('send-whatsapp-btn').addEventListener('click', function(e) {
                e.preventDefault();

                $(this).addClass('active');
                $('#send-email-btn').removeClass('active');

                if (selectedCheckboxes.length === 0) {
                    swal('No data selected.', 'Please select at least one item to send the whatsapp.',
                        'warning');
                    return;
                }

                const id = $(this).data('id');
                const type = $(this).data('type');
                const url_list = "{{ route('list_event', ['id' => 1]) }}";

                $('#divisionSelect').html('<option selected disabled>Loading...</option>');
                $('#divisionModal').modal('show');

                fetch(url_list, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        function ucwords(str) {
                            return str.replace(/\b\w/g, char => char.toUpperCase());
                        }

                        $('#divisionModalLabel').empty().append(
                            '<h5 class="modal-title" id="divisionModalLabel">Select Event Name (Send WhatsApp Selected)</h5>'
                        );

                        $('#divisionSelect').empty().append(
                            '<option selected disabled>-- Please Select --</option>');

                        data.listEvent.forEach(event => {
                            $('#divisionSelect').append(
                                `<option value="${event.id_event}">${ucwords(event.title)}</option>`
                            );
                        });
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        swal("Error!", "Failed to load events.", "error");
                    });
            });

            // Send Email dan WhatsApp Selected
            document.getElementById('sendButton').addEventListener('click', function() {
                const selectedDivision = $('#divisionSelect').val();

                if (!selectedDivision) {
                    swal("Warning!", "Please select the event name.", "warning");
                    return;
                }

                const type = $('#send-email-btn').hasClass('active') ? 0 : 1;

                const route = type === 0 ?
                    "{{ route('send.email.master.user') }}" :
                    "{{ route('send.whatsapp.master.user') }}";

                swal({
                        title: `Are you sure you want to send the ${type === 0 ? 'email' : 'whatsapp'}?`,
                        icon: "warning",
                        buttons: true,
                        dangerMode: true,
                    })
                    .then((willSend) => {
                        if (willSend) {
                            swal({
                                title: `${type === 0 ? 'Sending email' : 'Sending whatsapp'}, please wait...`,
                                text: "The process is ongoing.",
                                icon: "info",
                                buttons: false,
                                closeOnClickOutside: false,
                            });

                            fetch(route, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        ids: selectedCheckboxes,
                                        division_id: selectedDivision
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    const totalSent = data.sent;
                                    const totalSelected = data.total_selected;

                                    if (totalSent > 0) {
                                        var content = document.createElement('div');
                                        content.innerHTML =
                                            `${type === 0 ? 'Email' : 'Whatsapp'} has been successfully sent to <b>${totalSent} out of ${totalSelected} people.</b>`;

                                        swal({
                                            title: "Success!",
                                            content: content,
                                            icon: "success",
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    } else {
                                        swal("Failed!",
                                            `${type === 0 ? 'Emails' : 'Whatsapps'} failed to send.`,
                                            "error").then(() => {
                                            window.location.reload();
                                        });
                                    }
                                })
                                .catch((error) => {
                                    swal("Failed!",
                                        `An error occurred while sending the ${type === 0 ? 'email' : 'whatsapp'}.`,
                                        "error").then(() => {
                                        // window.location.reload();
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
                            url: "{{ route('delete-multiple-master-user') }}",
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedCheckboxes
                            },
                            success: function(response) {
                                if (response.message) {
                                    swal('Failed',
                                        'The user is not allowed to delete other divisions.',
                                        'warning');
                                } else if (response.success) {
                                    selectedCheckboxes.forEach(id => {
                                        $('input[value="' + id + '"]')
                                            .closest(
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
