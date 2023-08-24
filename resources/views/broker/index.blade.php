@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="panel panel-inverse">
    <!-- BEGIN panel-heading -->
    <div class="panel-heading">
        <h4 class="panel-title">Data {{ $title }}</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-default" data-toggle="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-success" data-toggle="panel-reload"><i class="fa fa-redo"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-warning" data-toggle="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-danger" data-toggle="panel-remove"><i class="fa fa-times"></i></a>
        </div>
    </div>
    <!-- END panel-heading -->
    <!-- BEGIN panel-body -->
    <div class="panel-body">
        <a href="#modal-dialog" id="btn-add" class="btn btn-sm btn-primary mb-3" data-route="{{ route('brokers.store') }}" data-bs-toggle="modal"><i class="fas fa-plus"></i> Add Broker</a>

        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Host</th>
                    <th class="text-nowrap">Port</th>
                    <th class="text-nowrap">WS Port</th>
                    <th class="text-nowrap">Username</th>
                    <th class="text-nowrap">Password</th>
                    <th class="text-nowrap">Status</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Broker</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="" method="post" id="form-broker" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="host">Host</label>
                        <input type="text" name="host" id="host" class="form-control" value="">

                        @error('host')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="port">Port</label>
                        <input type="text" name="port" id="port" class="form-control" value="">

                        @error('port')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="wsport">WS Port</label>
                        <input type="text" name="wsport" id="wsport" class="form-control" value="">

                        @error('wsport')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="">

                        @error('username')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="text" name="password" id="password" class="form-control" value="">

                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:;" id="btn-close" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form action="" class=" d-none" id="form-delete" method="post">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('script')
<script src="{{ asset('/') }}plugins/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('/') }}plugins/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
<script src="{{ asset('/') }}plugins/sweetalert/dist/sweetalert.min.js"></script>

<script>
    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('brokers.list') }}",
        deferRender: true,
        pagination: true,
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'host',
                name: 'host'
            },
            {
                data: 'port',
                name: 'port'
            },
            {
                data: 'wsport',
                name: 'wsport'
            },
            {
                data: 'username',
                name: 'username'
            },
            {
                data: 'password',
                name: 'password'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'action',
                name: 'action',
            },
        ]
    });

    $("#btn-add").on('click', function() {
        let route = $(this).attr('data-route')
        $("#form-broker").append(`<input type="hidden" name="_method" value="POST">`);
        $("#form-broker").attr('action', route)

        $("#host").val("")
        $("#port").val("")
        $("#wsport").val("")
        $("#username").val("")
        $("#password").val("")
    })

    $("#btn-close").on('click', function() {
        $("#form-broker").removeAttr('action')
    })

    $("#datatable").on('click', '.btn-edit', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#form-broker").attr('action', route)
        $("#form-broker").append(`<input type="hidden" name="_method" value="PUT">`);

        $.ajax({
            url: "/brokers/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let broker = response.broker;

                $("#host").val(broker.host)
                $("#port").val(broker.port)
                $("#wsport").val(broker.wsport)
                $("#username").val(broker.username)
                $("#password").val(broker.password)
            }
        })
    })

    $("#datatable").on('change', '.check-status', function() {
        let id = $(this).attr('id');
        let is_active = 0;

        if ($(this).is(":checked")) {
            is_active = 1;
        } else {
            is_active = 0;
        }

        $.ajax({
            url: "/brokers/" + id + "/change-status",
            type: 'GET',
            method: 'GET',
            data: {
                is_active: is_active
            },
            success: function(response) {
                if (response.status == "success") {
                    setTimeout(function() {
                        window.location.reload()
                    }, 1000)
                }
            },
        })
    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Delete broker?',
            text: 'Permanently delete broker.',
            icon: 'error',
            buttons: {
                cancel: {
                    text: 'Cancel',
                    value: null,
                    visible: true,
                    className: 'btn btn-default',
                    closeModal: true,
                },
                confirm: {
                    text: 'Yes',
                    value: true,
                    visible: true,
                    className: 'btn btn-danger',
                    closeModal: true
                }
            }
        }).then((result) => {
            if (result) {
                $("#form-delete").submit()
            } else {
                $("#form-delete").attr('action', '')
            }
        });
    })
</script>
@endpush