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
                        {{-- <li class="breadcrumb-item active">{{ Breadcrumbs::render('books') }}</li> --}}
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="container-fluid mb-3 d-flex justify-content-end">
        <div class="row">
            <div class="col-12">
                {{-- @can('book-module') --}}
                    <button class="btn btn-sm bg-navy" id="createNewItem">Tambah <i class="fa fa-plus"></i></button>
                    <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">Hapus Semua</button>
                {{-- @endcan --}}
            </div>
        </div>
    </div>

    <div class="container">
        @include('components.alerts')
        <div class="card card-navy">
            <div class="card-header">
                <h3 class="card-title">Data Pengurus</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="data-table" class="table table-sm table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 1%">No.</th>
                            <th class="text-center"><input type="checkbox" name="main_checkbox"><label></label></th>
                            <th>Nama Pengurus</th>
                            <th>Jabatan</th>
                            <th>Gambar</th>
                            <th class="text-center" style="width: 5%"><i class="fas fa-cogs"></i> </th>
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
                    <input type="hidden" name="member_id" id="member_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Pengurus <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm mr-2" name="name" id="name"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="position">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm mr-2" name="position" id="position"
                                required>
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
    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/sweetalert2/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('asset') }}/plugins/toastr/toastr.min.css">
@endsection
@section('custom-scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('asset') }}/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/toastr/toastr.min.js"></script>
    <script src="{{ asset('asset') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            bsCustomFileInput.init();

            let table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                ajax: "{{ route('members.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'dt-body-center'
                    },
                    {
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false,
                        className: 'dt-body-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'position',
                        name: 'position'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'dt-body-center'
                    },
                ],
            }).on('draw', function() {
                $('input[name="checkbox"]').each(function() {
                    this.checked = false;
                });
                $('input[name="main_checkbox"]').prop('checked', false);
                $('button#deleteAllBtn').addClass('d-none');
            });

            $('#createNewItem').click(function() {
                setTimeout(function() {
                    $('#name').focus();
                }, 500);
                $('#saveBtn').removeAttr('disabled');
                $('#saveBtn').html("Simpan");
                $('#member_id').val('');
                $('#itemForm').trigger("reset");
                $('#modal-title').html("Tambah Pengurus");
                $('#modal-md').modal('show');
            });

            $('body').on('click', '#editMember', function() {
                var member_id = $(this).data('id');
                $.get("{{ route('members.index') }}" + '/' + member_id + '/edit', function(data) {
                    $('#modal-md').modal('show');
                    setTimeout(function() {
                        $('#name').focus();
                    }, 500);
                    $('#modal-title').html("Edit Barang");
                    $('#saveBtn').removeAttr('disabled');
                    $('#saveBtn').html("Simpan");
                    $('#member_id').val(data.id);
                    $('#name').val(data.name);
                    $('#position').val(data.position);
                    $('#image').val(data.image);
                })
            });

            $('#saveBtn').click(function(e) {
                e.preventDefault();
                var formData = new FormData($('#itemForm')[0]);
                $.ajax({
                    data: formData,
                    url: "{{ route('members.store') }}",
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

            $(document).on('click', 'input[name="main_checkbox"]', function() {
                if (this.checked) {
                    $('input[name="checkbox"]').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('input[name="checkbox"]').each(function() {
                        this.checked = false;
                    });
                }
                toggledeleteAllBtn();
            });

            $(document).on('change', 'input[name="checkbox"]', function() {
                if ($('input[name="checkbox"]').length == $('input[name="checkbox"]:checked').length) {
                    $('input[name="main_checkbox"]').prop('checked', true);
                } else {
                    $('input[name="main_checkbox"]').prop('checked', false);
                }
                toggledeleteAllBtn();
            });

            function toggledeleteAllBtn() {
                if ($('input[name="checkbox"]:checked').length > 0) {
                    $('button#deleteAllBtn').text('Hapus (' + $('input[name="checkbox"]:checked').length + ')')
                        .removeClass('d-none');
                } else {
                    $('button#deleteAllBtn').addClass('d-none');
                }
            }

            $(document).on('click', 'button#deleteAllBtn', function() {
                var checkedItem = [];
                $('input[name="checkbox"]:checked').each(function() {
                    checkedItem.push($(this).data('id'));
                });
                var url = '{{ route('members.deleteSelected') }}';
                if (checkedItem.length > 0) {
                    swal.fire({
                        title: 'Apakah yakin?',
                        html: 'Ingin menghapus <b>(' + checkedItem.length + ')</b> pengurus',
                        showCancelButton: true,
                        showCloseButton: true,
                        confirmButtonText: 'Ya Hapus',
                        cancelButtonText: 'Tidak',
                        confirmButtonColor: '#556ee6',
                        cancelButtonColor: '#d33',
                        width: 300,
                        allowOutsideClick: false
                    }).then(function(result) {
                        if (result.value) {
                            $.post(url, {
                                id: checkedItem
                            }, function(data) {
                                if (data.code == 1) {
                                    // $('#data-table').DataTable().ajax.reload(null, true);
                                    table.draw();
                                    toastr.success(data.msg);
                                }
                            }, 'json');
                        }
                    })
                }
            });

        });
    </script>
@endsection
