@extends('layouts.master', ['title' => $title, 'breadcrumbs' => $breadcrumbs])

@push('style')
<link href="{{ asset('/') }}plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link href="{{ asset('/') }}plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
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
        <a href="#modal-dialog" id="btn-add" class="btn btn-sm btn-primary mb-3" data-route="{{ route('products.store') }}" data-bs-toggle="modal"><i class="fas fa-plus"></i> Add Product</a>

        <table id="datatable" class="table table-striped table-bordered align-middle">
            <thead>
                <tr>
                    <th class="text-nowrap">No</th>
                    <th class="text-nowrap">Image</th>
                    <th class="text-nowrap">Line</th>
                    <th class="text-nowrap">Art Code</th>
                    <th class="text-nowrap">Product Code</th>
                    <th class="text-nowrap">Product Name</th>
                    <th class="text-nowrap">Standar Speed</th>
                    <th class="text-nowrap">Pcs/Kanban</th>
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
                <h4 class="modal-title">Form Product</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <form action="" method="post" id="form-product" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="line">Line</label>
                        <select name="line" id="line" class="form-control">
                            <option disabled selected>-- Select Line --</option>
                            @foreach($lines as $line)
                            <option value="{{ $line->id }}">{{ $line->name }}</option>
                            @endforeach
                        </select>

                        @error('line')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="part_code">Part Code</label>
                        <input type="text" name="part_code" id="part_code" class="form-control" value="{{ old('part_code') }}">

                        @error('part_code')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="product_code">Product Code</label>
                        <input type="text" name="product_code" id="product_code" class="form-control" value="{{ old('product_code') }}">

                        @error('product_code')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="product_name">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="{{ old('product_name') }}">

                        @error('product_name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="stdspeed">Standar Speed</label>
                        <input type="text" name="stdspeed" id="stdspeed" class="form-control" value="{{ old('stdspeed') }}">

                        @error('stdspeed')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="pcs">Pcs/Kanban</label>
                        <input type="text" name="pcs" id="pcs" class="form-control" value="{{ old('pcs') }}">

                        @error('pcs')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-2">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*">

                        @error('image')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <img id="imagePreview" src="#" alt="Image Preview" style="max-width: 100%; max-height: 200px; display: none;">
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
<script src="{{ asset('/') }}plugins/select2/dist/js/select2.min.js"></script>

<script>
    $("#line").select2({
        dropdownParent: $('#modal-dialog'),
        placeholder: "Select lines"
    });

    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function() {
        const file = imageInput.files[0];

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';
            };

            reader.readAsDataURL(file);
        } else {
            imagePreview.src = '#';
            imagePreview.style.display = 'none';
        }
    });

    var table = $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('products.list') }}",
        deferRender: true,
        pagination: true,
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                orderable: false,
                searchable: false
            },
            {
                data: 'image',
                name: 'image'
            },
            {
                data: 'line',
                name: 'line'
            },
            {
                data: 'art_code',
                name: 'art_code'
            },
            {
                data: 'product_code',
                name: 'product_code'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'stdspeed',
                name: 'stdspeed'
            },
            {
                data: 'pcskarton',
                name: 'pcskarton'
            },
            {
                data: 'action',
                name: 'action',
            },
        ]
    });

    $("#btn-add").on('click', function() {
        let route = $(this).attr('data-route')
        $("#form-product").append(`<input type="hidden" name="_method" value="POST">`);
        $("#form-product").attr('action', route)

        $("#line").val("")
        $("#product_code").val("")
        $("#product_name").val("")
        $("#part_code").val("")
        $("#stdspeed").val("")
        $("#pcs").val("")
    })

    $("#btn-close").on('click', function() {
        $("#form-product").removeAttr('action')
    })

    $("#datatable").on('click', '.btn-edit', function() {
        let route = $(this).attr('data-route')
        let id = $(this).attr('id')

        $("#form-product").attr('action', route)
        $("#form-product").append(`<input type="hidden" name="_method" value="PUT">`);

        $.ajax({
            url: "/products/" + id,
            type: 'GET',
            method: 'GET',
            success: function(response) {
                let product = response.product;

                $("#line").val(product.line_id).trigger('change')
                $("#product_code").val(product.product_code)
                $("#product_name").val(product.name)
                $("#part_code").val(product.art_code)
                $("#stdspeed").val(product.stdspeed)
                $("#pcs").val(product.pcskarton)
            }
        })
    })

    $("#datatable").on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let route = $(this).attr('data-route')
        $("#form-delete").attr('action', route)

        swal({
            title: 'Delete Product?',
            text: 'Permanently delete Product.',
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