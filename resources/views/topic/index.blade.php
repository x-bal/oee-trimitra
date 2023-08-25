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
        <a href="#modal-dialog" id="btn-add" class="btn btn-sm btn-primary mb-3" data-route="{{ route('topics.store') }}" data-bs-toggle="modal"><i class="fas fa-plus"></i> Add Topic</a>

        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Machine Name</th>
                    <th class="text-nowrap">Topics</th>
                    <th class="text-nowrap">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal-dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Form Line Process</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="" method="post" id="form-line" enctype="multipart/form-data">
                @csrf

                <div class="modal-body row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm btn-primary mb-2" id="btnAddRow">Add Row</button>

                        <table class="table table-bordered" id="dynamicTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Topic</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
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
    $("#btnAddRow").click(function() {
        var newRow = `
            <tr>
            <td><input type="text" name="name[]" placeholder="Topic Name" id="" class="form-control form-control-sm"></td>
            <td><input type="text" name="topic[]" placeholder="Topic" id="" class="form-control form-control-sm"></td>
            <td><button type="button" class="btn btn-sm btn-danger btnRemoveRow"><i class="fas fa-times"></i></button></td>
            </tr>
        `;

        $("#dynamicTable tbody").append(newRow);
    });

    // Remove Row Button
    $(document).on("click", ".btnRemoveRow", function() {
        $(this).closest("tr").remove();
    });

    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('topics.list') }}",
        deferRender: true,
        pagination: true,
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'topic',
                name: 'topic'
            },
            {
                data: 'action',
                name: 'action',
            },
        ]
    });

    $("#btn-add").on('click', function() {
        let route = $(this).attr('data-route')
        $("#form-line").append(`<input type="hidden" name="_method" value="POST">`);
        $("#form-line").attr('action', route)
        $("#name").val("")
        $("#btnAddRow").show()

        let row = `<tr>
                        <td><input type="text" name="name[]" placeholder="Topic Name" id="" class="form-control form-control-sm"></td>
                        <td><input type="text" name="topic[]" placeholder="Topic" id="" class="form-control form-control-sm"></td>
                        <td>Not Available</td>
                    </tr>`;

        $("#dynamicTable tbody").empty().append(row);

    })

    $("#btn-close").on('click', function() {
        $("#form-line").removeAttr('action')
    })

    $("#datatable").on('click', '.btn-edit', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#btnAddRow").hide()
        $("#form-line").attr('action', route)
        $("#form-line").append(`<input type="hidden" name="_method" value="PUT">`);

        $.ajax({
            url: "/topics/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let topic = response.topic;

                rows = `<tr>
                    <td><input type="text" name="name[]" id="" class="form-control form-control-sm" value="` + topic.name + `"></td>
                    <td><input type="text" name="topic[]"  id="" class="form-control form-control-sm" value="` + topic.topic + `"></td>
                    <td>Not Available</td>
                    </tr>`;

                $("#dynamicTable tbody").empty().append(rows);
            }
        })
    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Delete line process?',
            text: 'Permanently delete line process.',
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