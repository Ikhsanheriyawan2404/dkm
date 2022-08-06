@extends('layouts.app', compact('title'))

@section('content')
@include('sweetalert::alert')


<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1 class="m-0">{{ $title ?? '' }}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            {{-- <li class="breadcrumb-item active">{{ Breadcrumbs::render('students') }}</li> --}}
        </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="container-fluid mb-3 d-flex justify-content-end">
    <div class="row">
        <div class="col-12">
            @can('student-create')
                <a href="{{ route('students.create') }}" class="btn btn-sm btn-primary">Tambah <i class="fa fa-plus"></i></a>
                <a class="btn btn-sm btn-success" data-toggle="modal" data-target="#importExcel">Impor <i class="fa fa-file-import"></i></a>
                <a href="{{ route('students.export') }}" class="btn btn-sm btn-success" target="_blank">Ekspor <i class="fa fa-file-export"></i></a>
                <a href="{{ route('students.printpdf') }}" class="btn btn-sm btn-danger">Print PDF <i class="fa fa-file-pdf"></i></a>
            @endcan
        </div>
    </div>
</div>

<div class="container">
    @include('components.alerts')
    <div class="card card-navy">
        <div class="card-header">
            <h3 class="card-title">Data Profil</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body table-responsive">
            <table id="data-table" class="table table-sm table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 1%">No.</th>
                        <th>Nama</th>
                        <th>Logo</th>
                        <th>Alamat</th>
                        <th class="text-center" style="width: 5%"><i class="fas fa-cogs"></i></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<!-- MODAL -->
<div class="modal fade" id="modal-md">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="itemForm" name="itemForm">
                @csrf
                <input type="hidden" name="profile_id" id="profile_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm mr-2" name="name" id="name"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat <span class="text-danger">*</span></label>
                        <textarea type="text" class="form-control form-control-sm mr-2" name="address" id="address"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Gambar</label>
                        <input type="file" class="form-control form-control-sm mr-2" name="image" id="image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm bg-navy" id="saveBtn" value="create">Save</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection

@section('custom-styles')

<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('asset')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('asset')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endsection

@section('custom-scripts')

<!-- DataTables  & Plugins -->
<script src="{{ asset('asset')}}/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('asset')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('asset')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{ asset('asset')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- bs-custom-file-input -->
<script src="{{ asset('asset') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

<script>
    $(function () {
        bsCustomFileInput.init();

        let table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,

            ajax: "{{ route('profiles.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'image', name: 'image'},
                {data: 'address', name: 'address'},
                {data: 'action', name: 'action', orderable: true, searchable: true, className: 'dt-body-center'},
            ]
        });

        $('body').on('click', '#editProfile', function() {
            var profile_id = $(this).data('id');
            $.get("{{ route('profiles.index') }}" + '/' + profile_id + '/edit', function(data) {
                $('#modal-md').modal('show');
                setTimeout(function() {
                    $('#name').focus();
                }, 500);
                $('#modal-title').html("Edit Profile");
                $('#saveBtn').removeAttr('disabled');
                $('#saveBtn').html("Simpan");
                $('#profile_id').val(data.id);
                $('#name').val(data.name);
                $('#price').val(data.price);
                $('#address').val(data.address);
                $('#image').val(data.image);
                $('#booklocation_id').val(data.booklocation_id);
            })
        });

        $('#saveBtn').click(function(e) {
            e.preventDefault();
            var formData = new FormData($('#itemForm')[0]);
            $.ajax({
                data: formData,
                url: "{{ route('profiles.store') }}",
                contentType: false,
                processData: false,
                type: "POST",
                success: function(data) {
                    $('#saveBtn').attr('disabled', 'disabled');
                    $('#saveBtn').html('Simpan ...');
                    $('#itemForm').trigger("reset");
                    $('#modal-md').modal('hide');
                    table.draw();
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Data masih kosong!',
                    });
                }
            });
        });
    });
</script>

@endsection
